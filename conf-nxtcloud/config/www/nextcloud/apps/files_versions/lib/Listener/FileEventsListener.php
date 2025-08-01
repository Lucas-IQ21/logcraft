<?php

/**
 * SPDX-FileCopyrightText: 2017-2024 Nextcloud GmbH and Nextcloud contributors
 * SPDX-FileCopyrightText: 2016 ownCloud, Inc.
 * SPDX-License-Identifier: AGPL-3.0-only
 */
namespace OCA\Files_Versions\Listener;

use Doctrine\DBAL\Exception\UniqueConstraintViolationException;
use OC\DB\Exceptions\DbalException;
use OC\Files\Filesystem;
use OC\Files\Mount\MoveableMount;
use OC\Files\Node\NonExistingFile;
use OC\Files\Node\NonExistingFolder;
use OC\Files\View;
use OCA\Files_Versions\Storage;
use OCA\Files_Versions\Versions\INeedSyncVersionBackend;
use OCA\Files_Versions\Versions\IVersionManager;
use OCP\AppFramework\Db\DoesNotExistException;
use OCP\DB\Exception;
use OCP\EventDispatcher\Event;
use OCP\EventDispatcher\IEventListener;
use OCP\Files\Events\Node\BeforeNodeCopiedEvent;
use OCP\Files\Events\Node\BeforeNodeDeletedEvent;
use OCP\Files\Events\Node\BeforeNodeRenamedEvent;
use OCP\Files\Events\Node\BeforeNodeTouchedEvent;
use OCP\Files\Events\Node\BeforeNodeWrittenEvent;
use OCP\Files\Events\Node\NodeCopiedEvent;
use OCP\Files\Events\Node\NodeCreatedEvent;
use OCP\Files\Events\Node\NodeDeletedEvent;
use OCP\Files\Events\Node\NodeRenamedEvent;
use OCP\Files\Events\Node\NodeTouchedEvent;
use OCP\Files\Events\Node\NodeWrittenEvent;
use OCP\Files\File;
use OCP\Files\Folder;
use OCP\Files\IMimeTypeLoader;
use OCP\Files\IRootFolder;
use OCP\Files\Node;
use OCP\IUserSession;
use Psr\Log\LoggerInterface;

/** @template-implements IEventListener<BeforeNodeCopiedEvent|BeforeNodeDeletedEvent|BeforeNodeRenamedEvent|BeforeNodeTouchedEvent|BeforeNodeWrittenEvent|NodeCopiedEvent|NodeCreatedEvent|NodeDeletedEvent|NodeRenamedEvent|NodeTouchedEvent|NodeWrittenEvent> */
class FileEventsListener implements IEventListener {
	/**
	 * @var array<int, array>
	 */
	private array $writeHookInfo = [];
	/**
	 * @var array<int, Node>
	 */
	private array $nodesTouched = [];
	/**
	 * @var array<string, Node>
	 */
	private array $versionsDeleted = [];

	public function __construct(
		private IRootFolder $rootFolder,
		private IVersionManager $versionManager,
		private IMimeTypeLoader $mimeTypeLoader,
		private IUserSession $userSession,
		private LoggerInterface $logger,
	) {
	}

	public function handle(Event $event): void {
		if ($event instanceof NodeCreatedEvent) {
			$this->created($event->getNode());
		}

		if ($event instanceof BeforeNodeTouchedEvent) {
			$this->pre_touch_hook($event->getNode());
		}

		if ($event instanceof NodeTouchedEvent) {
			$this->touch_hook($event->getNode());
		}

		if ($event instanceof BeforeNodeWrittenEvent) {
			$this->write_hook($event->getNode());
		}

		if ($event instanceof NodeWrittenEvent) {
			$this->post_write_hook($event->getNode());
		}

		if ($event instanceof BeforeNodeDeletedEvent) {
			$this->pre_remove_hook($event->getNode());
		}

		if ($event instanceof NodeDeletedEvent) {
			$this->remove_hook($event->getNode());
		}

		if ($event instanceof NodeRenamedEvent) {
			$this->rename_hook($event->getSource(), $event->getTarget());
		}

		if ($event instanceof NodeCopiedEvent) {
			$this->copy_hook($event->getSource(), $event->getTarget());
		}

		if ($event instanceof BeforeNodeRenamedEvent) {
			$this->pre_renameOrCopy_hook($event->getSource(), $event->getTarget());
		}

		if ($event instanceof BeforeNodeCopiedEvent) {
			$this->pre_renameOrCopy_hook($event->getSource(), $event->getTarget());
		}
	}

	public function pre_touch_hook(Node $node): void {
		// Do not handle folders.
		if ($node instanceof Folder) {
			return;
		}

		// $node is a non-existing on file creation.
		if ($node instanceof NonExistingFile) {
			return;
		}

		$this->nodesTouched[$node->getId()] = $node;
	}

	public function touch_hook(Node $node): void {
		// Do not handle folders.
		if ($node instanceof Folder) {
			return;
		}

		if ($node instanceof NonExistingFile) {
			$this->logger->error(
				'Failed to create or update version for {path}, node does not exist',
				[
					'path' => $node->getPath(),
				]
			);

			return;
		}

		$previousNode = $this->nodesTouched[$node->getId()] ?? null;

		if ($previousNode === null) {
			return;
		}

		unset($this->nodesTouched[$node->getId()]);

		try {
			if ($node instanceof File && $this->versionManager instanceof INeedSyncVersionBackend) {
				// We update the timestamp of the version entity associated with the previousNode.
				$this->versionManager->updateVersionEntity($node, $previousNode->getMTime(), ['timestamp' => $node->getMTime()]);
			}
		} catch (DbalException $ex) {
			// Ignore UniqueConstraintViolationException, as we are probably in the middle of a rollback
			// Where the previous node would temporary have the mtime of the old version, so the rollback touches it to fix it.
			if (!($ex->getPrevious() instanceof UniqueConstraintViolationException)) {
				throw $ex;
			}
		} catch (DoesNotExistException $ex) {
			// Ignore DoesNotExistException, as we are probably in the middle of a rollback
			// Where the previous node would temporary have a wrong mtime, so the rollback touches it to fix it.
		}
	}

	public function created(Node $node): void {
		// Do not handle folders.
		if (!($node instanceof File)) {
			return;
		}

		if ($node instanceof NonExistingFile) {
			$this->logger->error(
				'Failed to create version for {path}, node does not exist',
				[
					'path' => $node->getPath(),
				]
			);

			return;
		}

		if ($this->versionManager instanceof INeedSyncVersionBackend) {
			$this->versionManager->createVersionEntity($node);
		}
	}

	/**
	 * listen to write event.
	 */
	public function write_hook(Node $node): void {
		// Do not handle folders.
		if ($node instanceof Folder) {
			return;
		}

		// $node is a non-existing on file creation.
		if ($node instanceof NonExistingFile) {
			return;
		}

		$path = $this->getPathForNode($node);
		$result = Storage::store($path);

		// Store the result of the version creation so it can be used in post_write_hook.
		$this->writeHookInfo[$node->getId()] = [
			'previousNode' => $node,
			'versionCreated' => $result !== false
		];
	}

	/**
	 * listen to post_write event.
	 */
	public function post_write_hook(Node $node): void {
		// Do not handle folders.
		if ($node instanceof Folder) {
			return;
		}

		if ($node instanceof NonExistingFile) {
			$this->logger->error(
				'Failed to create or update version for {path}, node does not exist',
				[
					'path' => $node->getPath(),
				]
			);

			return;
		}

		$writeHookInfo = $this->writeHookInfo[$node->getId()] ?? null;

		if ($writeHookInfo === null) {
			return;
		}

		if (
			$writeHookInfo['versionCreated']
			&& $node->getMTime() !== $writeHookInfo['previousNode']->getMTime()
		) {
			// If a new version was created, insert a version in the DB for the current content.
			// If both versions have the same mtime, it means the latest version file simply got overrode,
			// so no need to create a new version.
			$this->created($node);
		} else {
			try {
				// If no new version was stored in the FS, no new version should be added in the DB.
				// So we simply update the associated version.
				if ($node instanceof File && $this->versionManager instanceof INeedSyncVersionBackend) {
					$this->versionManager->updateVersionEntity(
						$node,
						$writeHookInfo['previousNode']->getMtime(),
						[
							'timestamp' => $node->getMTime(),
							'size' => $node->getSize(),
							'mimetype' => $this->mimeTypeLoader->getId($node->getMimetype()),
						],
					);
				}
			} catch (DoesNotExistException $e) {
				// This happens if the versions app was not enabled while the file was created or updated the last time.
				// meaning there is no such revision and we need to create this file.
				if ($writeHookInfo['versionCreated']) {
					$this->created($node);
				} else {
					// Normally this should not happen so we re-throw the exception to not hide any potential issues.
					throw $e;
				}
			} catch (Exception $e) {
				$this->logger->error('Failed to update existing version for ' . $node->getPath(), [
					'exception' => $e,
					'versionCreated' => $writeHookInfo['versionCreated'],
					'previousNode' => [
						'size' => $writeHookInfo['previousNode']->getSize(),
						'mtime' => $writeHookInfo['previousNode']->getMTime(),
					],
					'node' => [
						'size' => $node->getSize(),
						'mtime' => $node->getMTime(),
					]
				]);
				throw $e;
			}
		}

		unset($this->writeHookInfo[$node->getId()]);
	}

	/**
	 * Erase versions of deleted file
	 *
	 * This function is connected to the delete signal of OC_Filesystem
	 * cleanup the versions directory if the actual file gets deleted
	 */
	public function remove_hook(Node $node): void {
		// Need to normalize the path as there is an issue with path concatenation in View.php::getAbsolutePath.
		$path = Filesystem::normalizePath($node->getPath());
		if (!array_key_exists($path, $this->versionsDeleted)) {
			return;
		}
		$node = $this->versionsDeleted[$path];
		$relativePath = $this->getPathForNode($node);
		unset($this->versionsDeleted[$path]);
		Storage::delete($relativePath);
		// If no new version was stored in the FS, no new version should be added in the DB.
		// So we simply update the associated version.
		if ($node instanceof File && $this->versionManager instanceof INeedSyncVersionBackend) {
			$this->versionManager->deleteVersionsEntity($node);
		}
	}

	/**
	 * mark file as "deleted" so that we can clean up the versions if the file is gone
	 */
	public function pre_remove_hook(Node $node): void {
		$path = $this->getPathForNode($node);
		Storage::markDeletedFile($path);
		$this->versionsDeleted[$node->getPath()] = $node;
	}

	/**
	 * rename/move versions of renamed/moved files
	 *
	 * This function is connected to the rename signal of OC_Filesystem and adjust the name and location
	 * of the stored versions along the actual file
	 */
	public function rename_hook(Node $source, Node $target): void {
		$sourceBackend = $this->versionManager->getBackendForStorage($source->getParent()->getStorage());
		$targetBackend = $this->versionManager->getBackendForStorage($target->getStorage());
		// If different backends, do nothing.
		if ($sourceBackend !== $targetBackend) {
			return;
		}

		$oldPath = $this->getPathForNode($source);
		$newPath = $this->getPathForNode($target);
		Storage::renameOrCopy($oldPath, $newPath, 'rename');
	}

	/**
	 * copy versions of copied files
	 *
	 * This function is connected to the copy signal of OC_Filesystem and copies the
	 * the stored versions to the new location
	 */
	public function copy_hook(Node $source, Node $target): void {
		$sourceBackend = $this->versionManager->getBackendForStorage($source->getParent()->getStorage());
		$targetBackend = $this->versionManager->getBackendForStorage($target->getStorage());
		// If different backends, do nothing.
		if ($sourceBackend !== $targetBackend) {
			return;
		}

		$oldPath = $this->getPathForNode($source);
		$newPath = $this->getPathForNode($target);
		Storage::renameOrCopy($oldPath, $newPath, 'copy');
	}

	/**
	 * Remember owner and the owner path of the source file.
	 * If the file already exists, then it was a upload of a existing file
	 * over the web interface and we call Storage::store() directly
	 *
	 *
	 */
	public function pre_renameOrCopy_hook(Node $source, Node $target): void {
		$sourceBackend = $this->versionManager->getBackendForStorage($source->getStorage());
		$targetBackend = $this->versionManager->getBackendForStorage($target->getParent()->getStorage());
		// If different backends, do nothing.
		if ($sourceBackend !== $targetBackend) {
			return;
		}

		// if we rename a movable mount point, then the versions don't have to be renamed
		$oldPath = $this->getPathForNode($source);
		$newPath = $this->getPathForNode($target);
		if ($oldPath === null || $newPath === null) {
			return;
		}

		$user = $this->userSession->getUser()?->getUID();
		if ($user === null) {
			return;
		}

		$absOldPath = Filesystem::normalizePath('/' . $user . '/files' . $oldPath);
		$manager = Filesystem::getMountManager();
		$mount = $manager->find($absOldPath);
		$internalPath = $mount->getInternalPath($absOldPath);
		if ($internalPath === '' and $mount instanceof MoveableMount) {
			return;
		}

		$view = new View($user . '/files');
		if ($view->file_exists($newPath)) {
			Storage::store($newPath);
		} else {
			Storage::setSourcePathAndUser($oldPath);
		}
	}

	/**
	 * Retrieve the path relative to the current user root folder.
	 * If no user is connected, try to use the node's owner.
	 */
	private function getPathForNode(Node $node): ?string {
		$user = $this->userSession->getUser()?->getUID();
		if ($user) {
			$path = $this->rootFolder
				->getUserFolder($user)
				->getRelativePath($node->getPath());

			if ($path !== null) {
				return $path;
			}
		}

		try {
			$owner = $node->getOwner()?->getUid();
		} catch (\OCP\Files\NotFoundException) {
			$owner = null;
		}

		// If no owner, extract it from the path.
		// e.g. /user/files/foobar.txt
		if (!$owner) {
			$parts = explode('/', $node->getPath(), 4);
			if (count($parts) === 4) {
				$owner = $parts[1];
			}
		}

		if ($owner) {
			$path = $this->rootFolder
				->getUserFolder($owner)
				->getRelativePath($node->getPath());

			if ($path !== null) {
				return $path;
			}
		}

		if (!($node instanceof NonExistingFile) && !($node instanceof NonExistingFolder)) {
			$this->logger->debug('Failed to compute path for node', [
				'node' => [
					'path' => $node->getPath(),
					'owner' => $owner,
					'fileid' => $node->getId(),
					'size' => $node->getSize(),
					'mtime' => $node->getMTime(),
				]
			]);
		} else {
			$this->logger->debug('Failed to compute path for node', [
				'node' => [
					'path' => $node->getPath(),
					'owner' => $owner,
				]
			]);
		}
		return null;
	}
}
