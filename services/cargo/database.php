<?php
require_once __DIR__ . '/includes/sr_env.php';
$con=sr_mysqli('sr');

if(mysqli_connect_errno())
{
	echo 'Failed to connect '.mysqli_connect_error();
}
?>