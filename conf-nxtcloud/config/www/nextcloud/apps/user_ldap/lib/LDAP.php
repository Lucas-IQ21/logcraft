<?php

/**
 * SPDX-FileCopyrightText: 2016-2024 Nextcloud GmbH and Nextcloud contributors
 * SPDX-FileCopyrightText: 2016 ownCloud, Inc.
 * SPDX-License-Identifier: AGPL-3.0-only
 */
namespace OCA\User_LDAP;

use OC\ServerNotAvailableException;
use OCA\User_LDAP\DataCollector\LdapDataCollector;
use OCA\User_LDAP\Exceptions\ConstraintViolationException;
use OCP\IConfig;
use OCP\ILogger;
use OCP\Profiler\IProfiler;
use OCP\Server;
use Psr\Log\LoggerInterface;

class LDAP implements ILDAPWrapper {
	protected array $curArgs = [];
	protected LoggerInterface $logger;
	protected IConfig $config;

	private ?LdapDataCollector $dataCollector = null;

	public function __construct(
		protected string $logFile = '',
	) {
		/** @var IProfiler $profiler */
		$profiler = \OC::$server->get(IProfiler::class);
		if ($profiler->isEnabled()) {
			$this->dataCollector = new LdapDataCollector();
			$profiler->add($this->dataCollector);
		}

		$this->logger = Server::get(LoggerInterface::class);
		$this->config = Server::get(IConfig::class);
	}

	/**
	 * {@inheritDoc}
	 */
	public function bind($link, $dn, $password) {
		return $this->invokeLDAPMethod('bind', $link, $dn, $password);
	}

	/**
	 * {@inheritDoc}
	 */
	public function connect($host, $port) {
		$pos = strpos($host, '://');
		if ($pos === false) {
			$host = 'ldap://' . $host;
			$pos = 4;
		}
		if (strpos($host, ':', $pos + 1) === false && !empty($port)) {
			//ldap_connect ignores port parameter when URLs are passed
			$host .= ':' . $port;
		}
		return $this->invokeLDAPMethod('connect', $host);
	}

	/**
	 * {@inheritDoc}
	 */
	public function controlPagedResultResponse($link, $result, &$cookie): bool {
		$errorCode = 0;
		$errorMsg = '';
		$controls = [];
		$matchedDn = null;
		$referrals = [];

		/** Cannot use invokeLDAPMethod because arguments are passed by reference */
		$this->preFunctionCall('ldap_parse_result', [$link, $result]);
		$success = ldap_parse_result($link, $result,
			$errorCode,
			$matchedDn,
			$errorMsg,
			$referrals,
			$controls);
		if ($errorCode !== 0) {
			$this->processLDAPError($link, 'ldap_parse_result', $errorCode, $errorMsg);
		}
		if ($this->dataCollector !== null) {
			$this->dataCollector->stopLastLdapRequest();
		}

		$cookie = $controls[LDAP_CONTROL_PAGEDRESULTS]['value']['cookie'] ?? '';

		return $success;
	}

	/**
	 * {@inheritDoc}
	 */
	public function countEntries($link, $result) {
		return $this->invokeLDAPMethod('count_entries', $link, $result);
	}

	/**
	 * {@inheritDoc}
	 */
	public function errno($link) {
		return $this->invokeLDAPMethod('errno', $link);
	}

	/**
	 * {@inheritDoc}
	 */
	public function error($link) {
		return $this->invokeLDAPMethod('error', $link);
	}

	/**
	 * Splits DN into its component parts
	 * @param string $dn
	 * @param int $withAttrib
	 * @return array|false
	 * @link https://www.php.net/manual/en/function.ldap-explode-dn.php
	 */
	public function explodeDN($dn, $withAttrib) {
		return $this->invokeLDAPMethod('explode_dn', $dn, $withAttrib);
	}

	/**
	 * {@inheritDoc}
	 */
	public function firstEntry($link, $result) {
		return $this->invokeLDAPMethod('first_entry', $link, $result);
	}

	/**
	 * {@inheritDoc}
	 */
	public function getAttributes($link, $result) {
		return $this->invokeLDAPMethod('get_attributes', $link, $result);
	}

	/**
	 * {@inheritDoc}
	 */
	public function getDN($link, $result) {
		return $this->invokeLDAPMethod('get_dn', $link, $result);
	}

	/**
	 * {@inheritDoc}
	 */
	public function getEntries($link, $result) {
		return $this->invokeLDAPMethod('get_entries', $link, $result);
	}

	/**
	 * {@inheritDoc}
	 */
	public function nextEntry($link, $result) {
		return $this->invokeLDAPMethod('next_entry', $link, $result);
	}

	/**
	 * {@inheritDoc}
	 */
	public function read($link, $baseDN, $filter, $attr) {
		return $this->invokeLDAPMethod('read', $link, $baseDN, $filter, $attr, 0, -1);
	}

	/**
	 * {@inheritDoc}
	 */
	public function search($link, $baseDN, $filter, $attr, $attrsOnly = 0, $limit = 0, int $pageSize = 0, string $cookie = '') {
		if ($pageSize > 0 || $cookie !== '') {
			$serverControls = [[
				'oid' => LDAP_CONTROL_PAGEDRESULTS,
				'value' => [
					'size' => $pageSize,
					'cookie' => $cookie,
				],
				'iscritical' => false,
			]];
		} else {
			$serverControls = [];
		}

		/** @psalm-suppress UndefinedVariable $oldHandler is defined when the closure is called but psalm fails to get that */
		$oldHandler = set_error_handler(function ($no, $message, $file, $line) use (&$oldHandler) {
			if (str_contains($message, 'Partial search results returned: Sizelimit exceeded')) {
				return true;
			}
			$oldHandler($no, $message, $file, $line);
			return true;
		});
		try {
			$result = $this->invokeLDAPMethod('search', $link, $baseDN, $filter, $attr, $attrsOnly, $limit, -1, LDAP_DEREF_NEVER, $serverControls);

			restore_error_handler();
			return $result;
		} catch (\Exception $e) {
			restore_error_handler();
			throw $e;
		}
	}

	/**
	 * {@inheritDoc}
	 */
	public function modReplace($link, $userDN, $password) {
		return $this->invokeLDAPMethod('mod_replace', $link, $userDN, ['userPassword' => $password]);
	}

	/**
	 * {@inheritDoc}
	 */
	public function exopPasswd($link, string $userDN, string $oldPassword, string $password) {
		return $this->invokeLDAPMethod('exop_passwd', $link, $userDN, $oldPassword, $password);
	}

	/**
	 * {@inheritDoc}
	 */
	public function setOption($link, $option, $value) {
		return $this->invokeLDAPMethod('set_option', $link, $option, $value);
	}

	/**
	 * {@inheritDoc}
	 */
	public function startTls($link) {
		return $this->invokeLDAPMethod('start_tls', $link);
	}

	/**
	 * {@inheritDoc}
	 */
	public function unbind($link) {
		return $this->invokeLDAPMethod('unbind', $link);
	}

	/**
	 * Checks whether the server supports LDAP
	 * @return boolean if it the case, false otherwise
	 * */
	public function areLDAPFunctionsAvailable() {
		return function_exists('ldap_connect');
	}

	/**
	 * {@inheritDoc}
	 */
	public function isResource($resource) {
		return is_resource($resource) || is_object($resource);
	}

	/**
	 * Checks whether the return value from LDAP is wrong or not.
	 *
	 * When using ldap_search we provide an array, in case multiple bases are
	 * configured. Thus, we need to check the array elements.
	 *
	 * @param mixed $result
	 */
	protected function isResultFalse(string $functionName, $result): bool {
		if ($result === false) {
			return true;
		}

		if ($functionName === 'ldap_search' && is_array($result)) {
			foreach ($result as $singleResult) {
				if ($singleResult === false) {
					return true;
				}
			}
		}

		return false;
	}

	/**
	 * @param array $arguments
	 * @return mixed
	 */
	protected function invokeLDAPMethod(string $func, ...$arguments) {
		$func = 'ldap_' . $func;
		if (function_exists($func)) {
			$this->preFunctionCall($func, $arguments);
			$result = call_user_func_array($func, $arguments);
			if ($this->isResultFalse($func, $result)) {
				$this->postFunctionCall($func);
			}
			if ($this->dataCollector !== null) {
				$this->dataCollector->stopLastLdapRequest();
			}
			return $result;
		}
		return null;
	}

	/**
	 * Turn resources into string, and removes potentially problematic cookie string to avoid breaking logfiles
	 */
	private function sanitizeFunctionParameters(array $args): array {
		return array_map(function ($item) {
			if ($this->isResource($item)) {
				return '(resource)';
			}
			if (isset($item[0]['value']['cookie']) && $item[0]['value']['cookie'] !== '') {
				$item[0]['value']['cookie'] = '*opaque cookie*';
			}
			return $item;
		}, $args);
	}

	private function preFunctionCall(string $functionName, array $args): void {
		$this->curArgs = $args;
		if (strcasecmp($functionName, 'ldap_bind') === 0 || strcasecmp($functionName, 'ldap_exop_passwd') === 0) {
			// The arguments are not key value pairs
			// \OCA\User_LDAP\LDAP::bind passes 3 arguments, the 3rd being the pw
			// Remove it via direct array access for now, although a better solution could be found mebbe?
			// @link https://github.com/nextcloud/server/issues/38461
			$args[2] = IConfig::SENSITIVE_VALUE;
		}

		if ($this->config->getSystemValue('loglevel') === ILogger::DEBUG) {
			/* Only running this if debug loglevel is on, to avoid processing parameters on production */
			$this->logger->debug('Calling LDAP function {func} with parameters {args}', [
				'app' => 'user_ldap',
				'func' => $functionName,
				'args' => $this->sanitizeFunctionParameters($args),
			]);
		}

		if ($this->dataCollector !== null) {
			$backtrace = debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS);
			$this->dataCollector->startLdapRequest($functionName, $this->sanitizeFunctionParameters($args), $backtrace);
		}

		if ($this->logFile !== '' && is_writable(dirname($this->logFile)) && (!file_exists($this->logFile) || is_writable($this->logFile))) {
			file_put_contents(
				$this->logFile,
				$functionName . '::' . json_encode($this->sanitizeFunctionParameters($args)) . "\n",
				FILE_APPEND
			);
		}
	}

	/**
	 * Analyzes the returned LDAP error and acts accordingly if not 0
	 *
	 * @param \LDAP\Connection $resource the LDAP Connection resource
	 * @throws ConstraintViolationException
	 * @throws ServerNotAvailableException
	 * @throws \Exception
	 */
	private function processLDAPError($resource, string $functionName, int $errorCode, string $errorMsg): void {
		$this->logger->debug('LDAP error {message} ({code}) after calling {func}', [
			'app' => 'user_ldap',
			'message' => $errorMsg,
			'code' => $errorCode,
			'func' => $functionName,
		]);
		if ($functionName === 'ldap_get_entries'
			&& $errorCode === -4) {
		} elseif ($errorCode === 32) {
			//for now
		} elseif ($errorCode === 10) {
			//referrals, we switch them off, but then there is AD :)
		} elseif ($errorCode === -1) {
			throw new ServerNotAvailableException('Lost connection to LDAP server.');
		} elseif ($errorCode === 52) {
			throw new ServerNotAvailableException('LDAP server is shutting down.');
		} elseif ($errorCode === 48) {
			throw new \Exception('LDAP authentication method rejected', $errorCode);
		} elseif ($errorCode === 1) {
			throw new \Exception('LDAP Operations error', $errorCode);
		} elseif ($errorCode === 19) {
			ldap_get_option($resource, LDAP_OPT_ERROR_STRING, $extended_error);
			throw new ConstraintViolationException(!empty($extended_error) ? $extended_error : $errorMsg, $errorCode);
		}
	}

	/**
	 * Called after an ldap method is run to act on LDAP error if necessary
	 * @throws \Exception
	 */
	private function postFunctionCall(string $functionName): void {
		if ($this->isResource($this->curArgs[0])) {
			$resource = $this->curArgs[0];
		} elseif (
			$functionName === 'ldap_search'
			&& is_array($this->curArgs[0])
			&& $this->isResource($this->curArgs[0][0])
		) {
			// we use always the same LDAP connection resource, is enough to
			// take the first one.
			$resource = $this->curArgs[0][0];
		} else {
			return;
		}

		$errorCode = ldap_errno($resource);
		if ($errorCode === 0) {
			return;
		}
		$errorMsg = ldap_error($resource);

		$this->processLDAPError($resource, $functionName, $errorCode, $errorMsg);

		$this->curArgs = [];
	}
}
