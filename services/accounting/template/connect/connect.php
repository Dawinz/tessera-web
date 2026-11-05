<?php
/**
 * Primary DB connection for accounting (account) + cargo helper.
 */
require_once __DIR__ . '/../includes/sr_env.php';
require_once __DIR__ . '/../includes/tessera_tenant.php';
require_once __DIR__ . '/../includes/tessera_legacy_request.php';
$cfg = sr_db_config('account');
$host = $cfg['host'];
$port = $cfg['port'];
/* Never use $username — it clobbers staff session vars after include. */
$dbUser = $cfg['user'];
$pass = $cfg['pass'];
$dbname = $cfg['name'];

mysqli_report(MYSQLI_REPORT_OFF);

$db = mysqli_init();
if ($db) {
	@$db->options(MYSQLI_OPT_CONNECT_TIMEOUT, 5);
	@$db->real_connect($host, $dbUser, $pass, $dbname, $port);
}

if (!$db || $db->connect_error) {
	$db = mysqli_init();
	if ($db) {
		@$db->options(MYSQLI_OPT_CONNECT_TIMEOUT, 5);
		@$db->real_connect($host, 'root', $pass, $dbname, $port);
	}
}

if (!$db || $db->connect_error) {
	http_response_code(503);
	die('Database unavailable. Check MariaDB / production MySQL, then refresh.');
}

$db->set_charset('utf8mb4');

// Gate mutator endpoints that only include connect.php (update/delete/upload).
if (PHP_SAPI !== 'cli') {
	$scriptDir = basename(dirname((string) ($_SERVER['SCRIPT_FILENAME'] ?? '')));
	if (in_array($scriptDir, ['update', 'delete', 'upload'], true)) {
		require_once __DIR__ . '/../includes/tessera_staff_boot.php';
	}
}

if (!function_exists('cargo_db')) {
	function cargo_db() {
		$cfg = sr_db_config('cargo');
		mysqli_report(MYSQLI_REPORT_OFF);
		$db = mysqli_init();
		if ($db) {
			@$db->options(MYSQLI_OPT_CONNECT_TIMEOUT, 5);
			@$db->real_connect($cfg['host'], $cfg['user'], $cfg['pass'], $cfg['name'], $cfg['port']);
		}
		if (!$db || $db->connect_error) {
			$db = mysqli_init();
			if ($db) {
				@$db->options(MYSQLI_OPT_CONNECT_TIMEOUT, 5);
				@$db->real_connect($cfg['host'], 'root', $cfg['pass'], $cfg['name'], $cfg['port']);
			}
		}
		if (!$db || $db->connect_error) {
			http_response_code(503);
			die('Cargo database unavailable. Check MariaDB / production MySQL, then refresh.');
		}
		$db->set_charset('utf8mb4');
		return $db;
	}
}
