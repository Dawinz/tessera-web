<?php
$host = 'localhost';
$username = 'root';
$pass = 'Teccross@360';
$db = 'cargo';

$db = new mysqli($host,$username,$pass,$db);

if ($db->connect_error) {
	 die("Connection Failed". $db->connect_error);
}

?>