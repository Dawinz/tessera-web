<?php
$host = '192.168.100.5';
$username = 'root';
$pass = 'Teccross@360';
$db = 'ticket';

$db = new mysqli($host,$username,$pass,$db);

if ($db->connect_error) {
	 die("Connection Failed". $db->connect_error);
}

?>