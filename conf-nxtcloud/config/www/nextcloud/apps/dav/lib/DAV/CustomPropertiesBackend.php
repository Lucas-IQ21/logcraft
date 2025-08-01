<?php
/**
 * SPDX-FileCopyrightText: 2017 Nextcloud GmbH and Nextcloud contributors
 * SPDX-FileCopyrightText: 2016 ownCloud, Inc.
 * SPDX-License-Identifier: AGPL-3.0-only
 */

namespace OCA\DAV\DAV;

use Exception;
use OCA\DAV\CalDAV\Calendar;
use OCA\DAV\CalDAV\DefaultCalendarValidator;
use OCA\DAV\Connector\Sabre\Directory;
use OCA\DAV\Connector\Sabre\FilesPlugin;
use OCP\DB\QueryBuilder\IQueryBuilder;
use OCP\IDBConnection;
use OCP\IUser;
use Sabre\DAV\Exception as DavException;
use Sabre\DAV\PropertyStorage\Backend\BackendInterface;
use Sabre\DAV\PropFind;
use Sabre\DAV\PropPatch;
use Sabre\DAV\Server;
use Sabre\DAV\Tree;
use Sabre\DAV\Xml\Property\Complex;
use Sabre\DAV\Xml\Property\Href;
use Sabre\DAV\Xml\Property\LocalHref;
use Sabre\Xml\ParseException;
use Sabre\Xml\Service as XmlService;

use function array_intersect;

class CustomPropertiesBackend implements BackendInterface {

	/** @var string */
	private const TABLE_NAME = 'properties';

	/**
	 * Value is stored as string.
	 */
	public const PROPERTY_TYPE_STRING = 1;

	/**
	 * Value is stored as XML fragment.
	 */
	public const PROPERTY_TYPE_XML = 2;

	/**
	 * Value is stored as a property object.
	 */
	public const PROPERTY_TYPE_OBJECT = 3;

	/**
	 * Value is stored as a {DAV:}href string.
	 */
	public const PROPERTY_TYPE_HREF = 4;

	/**
	 * Ignored properties
	 *
	 * @var string[]
	 */
	private const IGNORED_PROPERTIES = [
		'{DAV:}getcontentlength',
		'{DAV:}getcontenttype',
		'{DAV:}getetag',
		'{DAV:}quota-used-bytes',
		'{DAV:}quota-available-bytes',
		'{http://owncloud.org/ns}permissions',
		'{http://owncloud.org/ns}downloadURL',
		'{http://owncloud.org/ns}dDC',
		'{http://owncloud.org/ns}size',
		'{http://nextcloud.org/ns}is-encrypted',

		// Currently, returning null from any propfind handler would still trigger the backend,
		// so we add all known Nextcloud custom properties in here to avoid that

		// text app
		'{http://nextcloud.org/ns}rich-workspace',
		'{http://nextcloud.org/ns}rich-workspace-file',
		// groupfolders
		'{http://nextcloud.org/ns}acl-enabled',
		'{http://nextcloud.org/ns}acl-can-manage',
		'{http://nextcloud.org/ns}acl-list',
		'{http://nextcloud.org/ns}inherited-acl-list',
		'{http://nextcloud.org/ns}group-folder-id',
		// files_lock
		'{http://nextcloud.org/ns}lock',
		'{http://nextcloud.org/ns}lock-owner-type',
		'{http://nextcloud.org/ns}lock-owner',
		'{http://nextcloud.org/ns}lock-owner-displayname',
		'{http://nextcloud.org/ns}lock-owner-editor',
		'{http://nextcloud.org/ns}lock-time',
		'{http://nextcloud.org/ns}lock-timeout',
		'{http://nextcloud.org/ns}lock-token',
		// photos
		'{http://nextcloud.org/ns}realpath',
		'{http://nextcloud.org/ns}nbItems',
		'{http://nextcloud.org/ns}face-detections',
		'{http://nextcloud.org/ns}face-preview-image',
	];

	/**
	 * Properties set by one user, readable by all others
	 *
	 * @var string[]
	 */
	private const PUBLISHED_READ_ONLY_PROPERTIES = [
		'{urn:ietf:params:xml:ns:caldav}calendar-availability',
		'{urn:ietf:params:xml:ns:caldav}schedule-default-calendar-URL',
	];

	/**
	 * Map of custom XML elements to parse when trying to deserialize an instance of
	 * \Sabre\DAV\Xml\Property\Complex to find a more specialized PROPERTY_TYPE_*
	 */
	private const COMPLEX_XML_ELEMENT_MAP = [
		'{urn:ietf:params:xml:ns:caldav}schedule-default-calendar-URL' => Href::class,
	];

	/**
	 * Properties cache
	 *
	 * @var array
	 */
	private $userCache = [];
	private XmlService $xmlService;

	/**
	 * @param Tree $tree node tree
	 * @param IDBConnection $connection database connection
	 * @param IUser $user owner of the tree and properties
	 */
	public function __construct(
		private Server $server,
		private Tree $tree,
		private IDBConnection $connection,
		private IUser $user,
		private DefaultCalendarValidator $defaultCalendarValidator,
	) {
		$this->xmlService = new XmlService();
		$this->xmlService->elementMap = array_merge(
			$this->xmlService->elementMap,
			self::COMPLEX_XML_ELEMENT_MAP,
		);
	}

	/**
	 * Fetches properties for a path.
	 *
	 * @param string $path
	 * @param PropFind $propFind
	 * @return void
	 */
	public function propFind($path, PropFind $propFind) {
		$requestedProps = $propFind->get404Properties();

		// these might appear
		$requestedProps = array_diff(
			$requestedProps,
			self::IGNORED_PROPERTIES,
		);
		$requestedProps = array_filter(
			$requestedProps,
			fn ($prop) => !str_starts_with($prop, FilesPlugin::FILE_METADATA_PREFIX),
		);

		// substr of calendars/ => path is inside the CalDAV component
		// two '/' => this a calendar (no calendar-home nor calendar object)
		if (str_starts_with($path, 'calendars/') && substr_count($path, '/') === 2) {
			$allRequestedProps = $propFind->getRequestedProperties();
			$customPropertiesForShares = [
				'{DAV:}displayname',
				'{urn:ietf:params:xml:ns:caldav}calendar-description',
				'{urn:ietf:params:xml:ns:caldav}calendar-timezone',
				'{http://apple.com/ns/ical/}calendar-order',
				'{http://apple.com/ns/ical/}calendar-color',
				'{urn:ietf:params:xml:ns:caldav}schedule-calendar-transp',
			];

			foreach ($customPropertiesForShares as $customPropertyForShares) {
				if (in_array($customPropertyForShares, $allRequestedProps)) {
					$requestedProps[] = $customPropertyForShares;
				}
			}
		}

		// substr of addressbooks/ => path is inside the CardDAV component
		// three '/' => this a addressbook (no addressbook-home nor contact object)
		if (str_starts_with($path, 'addressbooks/') && substr_count($path, '/') === 3) {
			$allRequestedProps = $propFind->getRequestedProperties();
			$customPropertiesForShares = [
				'{DAV:}displayname',
			];

			foreach ($customPropertiesForShares as $customPropertyForShares) {
				if (in_array($customPropertyForShares, $allRequestedProps, true)) {
					$requestedProps[] = $customPropertyForShares;
				}
			}
		}

		// substr of principals/users/ => path is a user principal
		// two '/' => this a principal collection (and not some child object)
		if (str_starts_with($path, 'principals/users/') && substr_count($path, '/') === 2) {
			$allRequestedProps = $propFind->getRequestedProperties();
			$customProperties = [
				'{urn:ietf:params:xml:ns:caldav}schedule-default-calendar-URL',
			];

			foreach ($customProperties as $customProperty) {
				if (in_array($customProperty, $allRequestedProps, true)) {
					$requestedProps[] = $customProperty;
				}
			}
		}

		if (empty($requestedProps)) {
			return;
		}

		$node = $this->tree->getNodeForPath($path);
		if ($node instanceof Directory && $propFind->getDepth() !== 0) {
			$this->cacheDirectory($path, $node);
		}

		// First fetch the published properties (set by another user), then get the ones set by
		// the current user. If both are set then the latter as priority.
		foreach ($this->getPublishedProperties($path, $requestedProps) as $propName => $propValue) {
			try {
				$this->validateProperty($path, $propName, $propValue);
			} catch (DavException $e) {
				continue;
			}
			$propFind->set($propName, $propValue);
		}
		foreach ($this->getUserProperties($path, $requestedProps) as $propName => $propValue) {
			try {
				$this->validateProperty($path, $propName, $propValue);
			} catch (DavException $e) {
				continue;
			}
			$propFind->set($propName, $propValue);
		}
	}

	/**
	 * Updates properties for a path
	 *
	 * @param string $path
	 * @param PropPatch $propPatch
	 *
	 * @return void
	 */
	public function propPatch($path, PropPatch $propPatch) {
		$propPatch->handleRemaining(function ($changedProps) use ($path) {
			return $this->updateProperties($path, $changedProps);
		});
	}

	/**
	 * This method is called after a node is deleted.
	 *
	 * @param string $path path of node for which to delete properties
	 */
	public function delete($path) {
		$statement = $this->connection->prepare(
			'DELETE FROM `*PREFIX*properties` WHERE `userid` = ? AND `propertypath` = ?'
		);
		$statement->execute([$this->user->getUID(), $this->formatPath($path)]);
		$statement->closeCursor();

		unset($this->userCache[$path]);
	}

	/**
	 * This method is called after a successful MOVE
	 *
	 * @param string $source
	 * @param string $destination
	 *
	 * @return void
	 */
	public function move($source, $destination) {
		$statement = $this->connection->prepare(
			'UPDATE `*PREFIX*properties` SET `propertypath` = ?' .
			' WHERE `userid` = ? AND `propertypath` = ?'
		);
		$statement->execute([$this->formatPath($destination), $this->user->getUID(), $this->formatPath($source)]);
		$statement->closeCursor();
	}

	/**
	 * Validate the value of a property. Will throw if a value is invalid.
	 *
	 * @throws DavException The value of the property is invalid
	 */
	private function validateProperty(string $path, string $propName, mixed $propValue): void {
		switch ($propName) {
			case '{urn:ietf:params:xml:ns:caldav}schedule-default-calendar-URL':
				/** @var Href $propValue */
				$href = $propValue->getHref();
				if ($href === null) {
					throw new DavException('Href is empty');
				}

				// $path is the principal here as this prop is only set on principals
				$node = $this->tree->getNodeForPath($href);
				if (!($node instanceof Calendar) || $node->getOwner() !== $path) {
					throw new DavException('No such calendar');
				}

				$this->defaultCalendarValidator->validateScheduleDefaultCalendar($node);
				break;
		}
	}

	/**
	 * @param string $path
	 * @param string[] $requestedProperties
	 *
	 * @return array
	 */
	private function getPublishedProperties(string $path, array $requestedProperties): array {
		$allowedProps = array_intersect(self::PUBLISHED_READ_ONLY_PROPERTIES, $requestedProperties);

		if (empty($allowedProps)) {
			return [];
		}

		$qb = $this->connection->getQueryBuilder();
		$qb->select('*')
			->from(self::TABLE_NAME)
			->where($qb->expr()->eq('propertypath', $qb->createNamedParameter($path)));
		$result = $qb->executeQuery();
		$props = [];
		while ($row = $result->fetch()) {
			$props[$row['propertyname']] = $this->decodeValueFromDatabase($row['propertyvalue'], $row['valuetype']);
		}
		$result->closeCursor();
		return $props;
	}

	/**
	 * prefetch all user properties in a directory
	 */
	private function cacheDirectory(string $path, Directory $node): void {
		$prefix = ltrim($path . '/', '/');
		$query = $this->connection->getQueryBuilder();
		$query->select('name', 'p.propertypath', 'p.propertyname', 'p.propertyvalue', 'p.valuetype')
			->from('filecache', 'f')
			->hintShardKey('storage', $node->getNode()->getMountPoint()->getNumericStorageId())
			->leftJoin('f', 'properties', 'p', $query->expr()->eq('p.propertypath', $query->func()->concat(
				$query->createNamedParameter($prefix),
				'f.name'
			)),
			)
			->where($query->expr()->eq('parent', $query->createNamedParameter($node->getInternalFileId(), IQueryBuilder::PARAM_INT)))
			->andWhere($query->expr()->orX(
				$query->expr()->eq('p.userid', $query->createNamedParameter($this->user->getUID())),
				$query->expr()->isNull('p.userid'),
			));
		$result = $query->executeQuery();

		$propsByPath = [];

		while ($row = $result->fetch()) {
			$childPath = $prefix . $row['name'];
			if (!isset($propsByPath[$childPath])) {
				$propsByPath[$childPath] = [];
			}
			if (isset($row['propertyname'])) {
				$propsByPath[$childPath][$row['propertyname']] = $this->decodeValueFromDatabase($row['propertyvalue'], $row['valuetype']);
			}
		}
		$this->userCache = array_merge($this->userCache, $propsByPath);
	}

	/**
	 * Returns a list of properties for the given path and current user
	 *
	 * @param string $path
	 * @param array $requestedProperties requested properties or empty array for "all"
	 * @return array
	 * @note The properties list is a list of propertynames the client
	 * requested, encoded as xmlnamespace#tagName, for example:
	 * http://www.example.org/namespace#author If the array is empty, all
	 * properties should be returned
	 */
	private function getUserProperties(string $path, array $requestedProperties) {
		if (isset($this->userCache[$path])) {
			return $this->userCache[$path];
		}

		// TODO: chunking if more than 1000 properties
		$sql = 'SELECT * FROM `*PREFIX*properties` WHERE `userid` = ? AND `propertypath` = ?';

		$whereValues = [$this->user->getUID(), $this->formatPath($path)];
		$whereTypes = [null, null];

		if (!empty($requestedProperties)) {
			// request only a subset
			$sql .= ' AND `propertyname` in (?)';
			$whereValues[] = $requestedProperties;
			$whereTypes[] = IQueryBuilder::PARAM_STR_ARRAY;
		}

		$result = $this->connection->executeQuery(
			$sql,
			$whereValues,
			$whereTypes
		);

		$props = [];
		while ($row = $result->fetch()) {
			$props[$row['propertyname']] = $this->decodeValueFromDatabase($row['propertyvalue'], $row['valuetype']);
		}

		$result->closeCursor();

		$this->userCache[$path] = $props;
		return $props;
	}

	/**
	 * @throws Exception
	 */
	private function updateProperties(string $path, array $properties): bool {
		// TODO: use "insert or update" strategy ?
		$existing = $this->getUserProperties($path, []);
		try {
			$this->connection->beginTransaction();
			foreach ($properties as $propertyName => $propertyValue) {
				// common parameters for all queries
				$dbParameters = [
					'userid' => $this->user->getUID(),
					'propertyPath' => $this->formatPath($path),
					'propertyName' => $propertyName,
				];

				// If it was null, we need to delete the property
				if (is_null($propertyValue)) {
					if (array_key_exists($propertyName, $existing)) {
						$deleteQuery = $deleteQuery ?? $this->createDeleteQuery();
						$deleteQuery
							->setParameters($dbParameters)
							->executeStatement();
					}
				} else {
					[$value, $valueType] = $this->encodeValueForDatabase(
						$path,
						$propertyName,
						$propertyValue,
					);
					$dbParameters['propertyValue'] = $value;
					$dbParameters['valueType'] = $valueType;

					if (!array_key_exists($propertyName, $existing)) {
						$insertQuery = $insertQuery ?? $this->createInsertQuery();
						$insertQuery
							->setParameters($dbParameters)
							->executeStatement();
					} else {
						$updateQuery = $updateQuery ?? $this->createUpdateQuery();
						$updateQuery
							->setParameters($dbParameters)
							->executeStatement();
					}
				}
			}

			$this->connection->commit();
			unset($this->userCache[$path]);
		} catch (Exception $e) {
			$this->connection->rollBack();
			throw $e;
		}

		return true;
	}

	/**
	 * long paths are hashed to ensure they fit in the database
	 *
	 * @param string $path
	 * @return string
	 */
	private function formatPath(string $path): string {
		if (strlen($path) > 250) {
			return sha1($path);
		}

		return $path;
	}

	/**
	 * @throws ParseException If parsing a \Sabre\DAV\Xml\Property\Complex value fails
	 * @throws DavException If the property value is invalid
	 */
	private function encodeValueForDatabase(string $path, string $name, mixed $value): array {
		// Try to parse a more specialized property type first
		if ($value instanceof Complex) {
			$xml = $this->xmlService->write($name, [$value], $this->server->getBaseUri());
			$value = $this->xmlService->parse($xml, $this->server->getBaseUri()) ?? $value;
		}

		if ($name === '{urn:ietf:params:xml:ns:caldav}schedule-default-calendar-URL') {
			$value = $this->encodeDefaultCalendarUrl($value);
		}

		try {
			$this->validateProperty($path, $name, $value);
		} catch (DavException $e) {
			throw new DavException(
				"Property \"$name\" has an invalid value: " . $e->getMessage(),
				0,
				$e,
			);
		}

		if (is_scalar($value)) {
			$valueType = self::PROPERTY_TYPE_STRING;
		} elseif ($value instanceof Complex) {
			$valueType = self::PROPERTY_TYPE_XML;
			$value = $value->getXml();
		} elseif ($value instanceof Href) {
			$valueType = self::PROPERTY_TYPE_HREF;
			$value = $value->getHref();
		} else {
			$valueType = self::PROPERTY_TYPE_OBJECT;
			// serialize produces null character
			// these can not be properly stored in some databases and need to be replaced
			$value = str_replace(chr(0), '\x00', serialize($value));
		}
		return [$value, $valueType];
	}

	/**
	 * @return mixed|Complex|string
	 */
	private function decodeValueFromDatabase(string $value, int $valueType) {
		switch ($valueType) {
			case self::PROPERTY_TYPE_XML:
				return new Complex($value);
			case self::PROPERTY_TYPE_HREF:
				return new Href($value);
			case self::PROPERTY_TYPE_OBJECT:
				// some databases can not handel null characters, these are custom encoded during serialization
				// this custom encoding needs to be first reversed before unserializing
				return unserialize(str_replace('\x00', chr(0), $value));
			case self::PROPERTY_TYPE_STRING:
			default:
				return $value;
		}
	}

	private function encodeDefaultCalendarUrl(Href $value): Href {
		$href = $value->getHref();
		if ($href === null) {
			return $value;
		}

		if (!str_starts_with($href, '/')) {
			return $value;
		}

		try {
			// Build path relative to the dav base URI to be used later to find the node
			$value = new LocalHref($this->server->calculateUri($href) . '/');
		} catch (DavException\Forbidden) {
			// Not existing calendars will be handled later when the value is validated
		}

		return $value;
	}

	private function createDeleteQuery(): IQueryBuilder {
		$deleteQuery = $this->connection->getQueryBuilder();
		$deleteQuery->delete('properties')
			->where($deleteQuery->expr()->eq('userid', $deleteQuery->createParameter('userid')))
			->andWhere($deleteQuery->expr()->eq('propertypath', $deleteQuery->createParameter('propertyPath')))
			->andWhere($deleteQuery->expr()->eq('propertyname', $deleteQuery->createParameter('propertyName')));
		return $deleteQuery;
	}

	private function createInsertQuery(): IQueryBuilder {
		$insertQuery = $this->connection->getQueryBuilder();
		$insertQuery->insert('properties')
			->values([
				'userid' => $insertQuery->createParameter('userid'),
				'propertypath' => $insertQuery->createParameter('propertyPath'),
				'propertyname' => $insertQuery->createParameter('propertyName'),
				'propertyvalue' => $insertQuery->createParameter('propertyValue'),
				'valuetype' => $insertQuery->createParameter('valueType'),
			]);
		return $insertQuery;
	}

	private function createUpdateQuery(): IQueryBuilder {
		$updateQuery = $this->connection->getQueryBuilder();
		$updateQuery->update('properties')
			->set('propertyvalue', $updateQuery->createParameter('propertyValue'))
			->set('valuetype', $updateQuery->createParameter('valueType'))
			->where($updateQuery->expr()->eq('userid', $updateQuery->createParameter('userid')))
			->andWhere($updateQuery->expr()->eq('propertypath', $updateQuery->createParameter('propertyPath')))
			->andWhere($updateQuery->expr()->eq('propertyname', $updateQuery->createParameter('propertyName')));
		return $updateQuery;
	}
}
