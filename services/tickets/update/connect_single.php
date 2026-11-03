<?php
# Tessera connect shim - env-based mysqli
require_once dirname(__DIR__) . '/includes/sr_env.php';
$cfg = sr_db_config(getenv('SR_DB_NAME') ?: 'ticket');
$connect = @mysqli_connect($cfg['host'], $cfg['user'], $cfg['pass'], $cfg['name'], $cfg['port']);
if (!$connect) {
	http_response_code(503);
	die('Database unavailable');
}
mysqli_set_charset($connect, 'utf8mb4');
