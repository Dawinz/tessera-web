<?php
/**
 * Primary DB connection for cargo (cargo).
 */
require_once __DIR__ . '/includes/sr_env.php';
require_once __DIR__ . '/includes/tessera_tenant.php';
require_once __DIR__ . '/includes/tessera_legacy_request.php';
$cfg = sr_db_config('cargo');
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

if (!function_exists('tessera_cargo_latest_trip')) {
	function tessera_cargo_latest_trip($db) {
		$res = mysqli_query($db, "SELECT Name, date, Driver, Conductor, code, Route, status FROM vehicle_name WHERE date >= '2020-01-01' ORDER BY date DESC LIMIT 1");
		if ($res && ($row = mysqli_fetch_assoc($res))) {
			return $row;
		}
		return null;
	}
}
if (!function_exists('tessera_cargo_date_label')) {
	function tessera_cargo_date_label($date) {
		$ts = $date ? strtotime($date) : false;
		if (!$ts || (int) date('Y', $ts) < 2000) {
			return 'No trip selected';
		}
		return date('jS M Y', $ts);
	}
}
