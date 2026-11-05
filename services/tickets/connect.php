<?php
/**
 * Primary DB connection for Tickets service.
 * Uses TCP; in production reads MYSQL* / SR_* env vars.
 */
require_once __DIR__ . '/includes/sr_env.php';
require_once __DIR__ . '/includes/tessera_tenant.php';
require_once __DIR__ . '/includes/tessera_legacy_request.php';
/* Hybrid: routes to t_{slug}_ticket when operator has dedicated DBs. */
$cfg = sr_db_config('ticket');
$host = $cfg['host'];
$port = $cfg['port'];
/* Do not use $username — that clobbers staff session username in including scripts. */
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
