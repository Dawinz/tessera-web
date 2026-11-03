<?php
require_once __DIR__ . '/includes/tessera_staff_boot.php';
session_start();
header('Content-Type: text/plain; charset=utf-8');

if (empty($_SESSION['username'])) {
	http_response_code(401);
	echo 'unauthorized';
	exit;
}

$l_name = trim((string) ($_POST['l_name'] ?? ''));
if ($l_name === '') {
	echo 'invalid';
	exit;
}

include 'connect.php';
$name = mysqli_real_escape_string($db, $l_name);
$select1 = mysqli_query($db, "SELECT id FROM locations_names WHERE name='$name' LIMIT 1");
if ($select1 && mysqli_num_rows($select1) > 0) {
	echo 'found';
	exit;
}

$nextId = 1;
$maxQ = mysqli_query($db, 'SELECT MAX(id) AS m FROM locations_names');
if ($maxQ && ($maxRow = mysqli_fetch_assoc($maxQ)) && $maxRow['m'] !== null) {
	$nextId = ((int) $maxRow['m']) + 1;
}

$insert = mysqli_query($db, "INSERT INTO locations_names (id, name) VALUES ('$nextId', '$name')");
if ($insert) {
	echo 'succesfull';
} else {
	http_response_code(500);
	echo 'insert failed: ' . mysqli_error($db);
}
