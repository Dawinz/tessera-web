<?php
/**
 * DB for Tessera Control — same ticket DB as tenants registry.
 */
$srEnvCandidates = [
	__DIR__ . '/sr_env.php',
	dirname(__DIR__, 2) . '/services/tickets/includes/sr_env.php', /* local monorepo */
	'/var/www/html/tickets/includes/sr_env.php', /* Docker image */
];
$srEnvLoaded = false;
foreach ($srEnvCandidates as $srEnv) {
	if (is_file($srEnv)) {
		require_once $srEnv;
		$srEnvLoaded = true;
		break;
	}
}
if (!$srEnvLoaded || !function_exists('sr_db_config')) {
	http_response_code(500);
	header('Content-Type: text/plain; charset=utf-8');
	echo "Tessera Control: sr_env.php not found.\n";
	exit;
}

/* Control always uses the shared platform registry DB. */
$cfg = function_exists('sr_db_config_platform')
	? sr_db_config_platform(getenv('SR_DB_NAME') ?: 'ticket')
	: sr_db_config(getenv('SR_DB_NAME') ?: 'ticket', true);
$host = $cfg['host'];
$port = $cfg['port'];
$user = $cfg['user'];
$pass = $cfg['pass'];
$dbname = $cfg['name'];

mysqli_report(MYSQLI_REPORT_OFF);

$db = mysqli_init();
if ($db) {
	@$db->options(MYSQLI_OPT_CONNECT_TIMEOUT, 5);
	@$db->real_connect($host, $user, $pass, $dbname, $port);
}
if (!$db || $db->connect_error) {
	$db = mysqli_init();
	if ($db) {
		@$db->options(MYSQLI_OPT_CONNECT_TIMEOUT, 5);
		@$db->real_connect($host, 'root', $pass, $dbname, $port);
	}
}

$control_db_ok = ($db && !$db->connect_error);
if ($control_db_ok) {
	$db->set_charset('utf8mb4');
}
