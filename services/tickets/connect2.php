<?php
require_once __DIR__ . '/includes/sr_env.php';
$cfg = sr_db_config(getenv('SR_DB_NAME') ?: 'ticket');
$host = $cfg['host'];
$user = $cfg['user'];
$pass = $cfg['pass'];
$dbname = $cfg['name'];
$port = $cfg['port'];
$db = @mysqli_connect($host, $user, $pass, $dbname, $port);
if (!$db) {
	http_response_code(503);
	die('Database unavailable');
}
mysqli_set_charset($db, 'utf8mb4');
