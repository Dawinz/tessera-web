<?php
/**
 * One-off helper script - uses env-aware DB (not for web requests).
 */
require_once __DIR__ . '/includes/sr_env.php';
$conn = sr_mysqli('ticket');
if (!$conn) {
	die('Connection failed');
}

$sql = "INSERT INTO route_transit (route_prev) VALUES ('route_prev')";
if (mysqli_query($conn, $sql)) {
	$last_id = mysqli_insert_id($conn);
	echo 'New record created successfully. Last inserted ID is: ' . $last_id;
} else {
	echo 'Error: ' . $sql . '<br>' . mysqli_error($conn);
}
mysqli_close($conn);
