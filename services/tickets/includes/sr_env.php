<?php
/**
 * Shared DB env resolution - works on XAMPP (defaults) and production (MYSQL* / SR_*).
 */
if (!function_exists('tessera_apply_local_env')) {
	/** Load config/local.php on a developer machine. No-op in containers. */
	function tessera_apply_local_env(): void {
		if (getenv('MYSQLHOST') || getenv('SR_MANAGED_HOST') === '1') {
			return;
		}
		$dir = __DIR__;
		for ($i = 0; $i < 8; $i++) {
			$candidate = $dir . '/config/local.php';
			if (is_file($candidate)) {
				require_once $candidate;
				return;
			}
			$parent = dirname($dir);
			if ($parent === $dir) {
				return;
			}
			$dir = $parent;
		}
	}
}
tessera_apply_local_env();

if (!function_exists('sr_is_managed_host')) {
	/** True when remote DB env is explicitly set. */
	function sr_is_managed_host() {
		if (getenv('MYSQLHOST') || getenv('SR_MANAGED_HOST') === '1') {
			return true;
		}
		$host = getenv('SR_DB_HOST');
		return ($host !== false && $host !== '' && $host !== '127.0.0.1' && strtolower((string) $host) !== 'localhost');
	}
}

if (!function_exists('sr_db_credentials')) {
	/** Shared host/user/pass (no database name). */
	function sr_db_credentials(): array {
		$host = getenv('SR_DB_HOST');
		if ($host === false || $host === '') {
			$host = getenv('MYSQLHOST') ?: '127.0.0.1';
		}
		$port = getenv('SR_DB_PORT');
		if ($port === false || $port === '') {
			$port = getenv('MYSQLPORT') ?: '3306';
		}
		$user = getenv('SR_DB_USER');
		if ($user === false || $user === '') {
			$user = getenv('MYSQLUSER') ?: 'root';
		}
		$pass = getenv('SR_DB_PASS');
		if ($pass === false || $pass === '') {
			$pass = getenv('MYSQLPASSWORD');
		}
		if ($pass === false || $pass === '') {
			/* Local XAMPP default only - never invent a password on managed hosts. */
			$pass = sr_is_managed_host() ? '' : 'Teccross@360';
		}
		return [
			'host' => $host,
			'port' => (int) $port,
			'user' => $user,
			'pass' => $pass,
		];
	}
}

if (!function_exists('sr_logical_db_default')) {
	/** Map logical module -> shared default database name. */
	function sr_logical_db_default($logical) {
		$logical = strtolower((string) $logical);
		if ($logical === 'cargo') {
			return getenv('SR_CARGO_DB') ?: 'cargo';
		}
		if ($logical === 'account' || $logical === 'accounts') {
			return getenv('SR_ACCOUNT_DB') ?: 'account';
		}
		if ($logical === 'ticket' || $logical === 'tickets' || $logical === '') {
			return getenv('SR_DB_NAME') ?: 'ticket';
		}
		/* Already a concrete DB name (e.g. t_kilimanjaro_ticket). */
		return $logical;
	}
}

if (!function_exists('sr_resolve_db_name')) {
	/**
	 * Hybrid routing: dedicated operator DB when tessera_tenants.db_* is set;
	 * otherwise shared ticket/cargo/account.
	 */
	function sr_resolve_db_name($logical, bool $platform = false) {
		$fallback = sr_logical_db_default($logical);
		if ($platform) {
			return $fallback;
		}
		$mod = strtolower((string) $logical);
		if (in_array($mod, ['ticket', 'tickets', 'cargo', 'account', 'accounts', ''], true)) {
			if (!function_exists('tessera_tenant_db_name')) {
				$tenantHelper = __DIR__ . '/tessera_tenant.php';
				if (is_file($tenantHelper)) {
					require_once $tenantHelper;
				}
			}
			if (function_exists('tessera_tenant_db_name')) {
				$resolved = tessera_tenant_db_name($mod === '' ? 'ticket' : $mod);
				if (is_string($resolved) && $resolved !== '') {
					return $resolved;
				}
			}
		}
		return $fallback;
	}
}

if (!function_exists('sr_db_config_platform')) {
	/** Always the shared platform / registry databases (never tenant-routed). */
	function sr_db_config_platform($defaultDb = null) {
		$creds = sr_db_credentials();
		$creds['name'] = sr_resolve_db_name($defaultDb !== null && $defaultDb !== '' ? $defaultDb : 'ticket', true);
		return $creds;
	}
}

if (!function_exists('sr_db_config')) {
	function sr_db_config($defaultDb, bool $platform = false) {
		$creds = sr_db_credentials();
		$logical = $defaultDb !== null && $defaultDb !== '' ? $defaultDb : 'ticket';
		$creds['name'] = sr_resolve_db_name($logical, $platform);
		return $creds;
	}
}

if (!function_exists('sr_pdo')) {
	/** @return PDO */
	function sr_pdo($defaultDb, bool $platform = false) {
		$cfg = sr_db_config($defaultDb, $platform);
		$dsn = sprintf(
			'mysql:host=%s;port=%d;dbname=%s;charset=utf8mb4',
			$cfg['host'],
			$cfg['port'],
			$cfg['name']
		);
		return new PDO($dsn, $cfg['user'], $cfg['pass'], [
			PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
			PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
			PDO::ATTR_EMULATE_PREPARES => false,
		]);
	}
}

if (!function_exists('sr_mysqli_platform')) {
	/** Shared registry / Control connection (never tenant-routed). */
	function sr_mysqli_platform($defaultDb = null) {
		$cfg = sr_db_config_platform($defaultDb !== null && $defaultDb !== '' ? $defaultDb : 'ticket');
		$mysqli = @mysqli_connect($cfg['host'], $cfg['user'], $cfg['pass'], $cfg['name'], $cfg['port']);
		if ($mysqli) {
			@$mysqli->set_charset('utf8mb4');
		}
		return $mysqli;
	}
}

if (!function_exists('sr_mysqli')) {
	/**
	 * Env-aware mysqli connection (XAMPP defaults / MYSQL* / SR_*).
	 * Routes to the current host’s dedicated operator DB when provisioned.
	 * @return mysqli|false
	 */
	function sr_mysqli($defaultDb = null, bool $platform = false) {
		$cfg = $platform
			? sr_db_config_platform($defaultDb !== null && $defaultDb !== '' ? $defaultDb : 'ticket')
			: sr_db_config($defaultDb !== null && $defaultDb !== '' ? $defaultDb : 'ticket');
		$mysqli = @mysqli_connect($cfg['host'], $cfg['user'], $cfg['pass'], $cfg['name'], $cfg['port']);
		if ($mysqli) {
			@$mysqli->set_charset('utf8mb4');
		}
		return $mysqli;
	}
}

if (!function_exists('sr_public_base_url')) {
	function sr_public_base_url() {
		/* Prefer the host the browser is on (custom domain or public URL). */
		if (!empty($_SERVER['HTTP_HOST'])) {
			$host = strtolower((string) $_SERVER['HTTP_HOST']);
			$hostNoPort = preg_replace('/:\d+$/', '', $host);
			if ($hostNoPort !== 'localhost' && $hostNoPort !== '127.0.0.1') {
				$https = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off')
					|| (isset($_SERVER['HTTP_X_FORWARDED_PROTO']) && $_SERVER['HTTP_X_FORWARDED_PROTO'] === 'https');
				return ($https ? 'https' : 'http') . '://' . $_SERVER['HTTP_HOST'];
			}
		}
		foreach (['TESSERA_PUBLIC_URL', 'SR_PUBLIC_URL'] as $key) {
			$base = getenv($key);
			if ($base !== false && $base !== '') {
				return rtrim($base, '/');
			}
		}
		return 'http://localhost';
	}
}

if (!function_exists('tessera_module_path')) {
	/** Public URL path for a Tessera module: tickets | cargo | accounts */
	function tessera_module_path($module) {
		$map = [
			'tickets' => '/tickets',
			'cargo' => '/cargo',
			'accounts' => '/accounts',
		];
		$key = strtolower((string) $module);
		return $map[$key] ?? '/tickets';
	}
}

if (!function_exists('tessera_operator_spa_enabled')) {
	require_once __DIR__ . '/tessera_operator_spa.php';
}

if (!function_exists('tessera_module_url')) {
	function tessera_module_url($module, $suffix = '/') {
		if (function_exists('tessera_operator_spa_enabled') && tessera_operator_spa_enabled()) {
			$suffix = (string) $suffix;
			$page = ($suffix === '' || $suffix === '/') ? '' : ltrim($suffix, '/');
			if ($page === 'app.php') {
				$page = '';
			}
			return tessera_operator_shell_url((string) $module, $page);
		}
		$base = sr_public_base_url() . tessera_module_path($module);
		$suffix = (string) $suffix;
		if ($suffix === '' || $suffix === '/') {
			return $base . '/';
		}
		return $base . (strpos($suffix, '/') === 0 ? $suffix : '/' . $suffix);
	}
}
