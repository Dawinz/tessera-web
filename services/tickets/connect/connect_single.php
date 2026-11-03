<?php
# Tessera connect shim - env-based mysqli
require_once dirname(__DIR__) . '/includes/sr_env.php';
$cfg = sr_db_config(getenv('SR_DB_NAME') ?: 'ticket');
$db = @mysqli_connect($cfg['host'], $cfg['user'], $cfg['pass'], $cfg['name'], $cfg['port']);
if (!$db) {
	http_response_code(503);
	die('Database unavailable');
}
mysqli_set_charset($db, 'utf8mb4');
