<?php
require_once __DIR__ . '/includes/sr_env.php';
$cfg = sr_db_config('ticket');
$sname = $cfg['host'];
$uname = $cfg['user'];
$password = $cfg['pass'];
$db_name = $cfg['name'];

$conn = sr_mysqli($db_name);
if (!$conn) {
	echo 'Connection failed!';
	exit();
}
