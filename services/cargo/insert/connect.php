<?php
require_once __DIR__ . '/includes/sr_env.php';
$cfg = sr_db_config('sr');
$host = $cfg['host'];
$username = $cfg['user'];
$pass = $cfg['pass'];
$db = $cfg['name'];

$db = new mysqli($host,$username,$pass,$db);

if ($db->connect_error) {
	 die("Connection Failed". $db->connect_error);
}

?>