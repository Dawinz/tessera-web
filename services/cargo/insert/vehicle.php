<?php
require_once __DIR__ . '/../includes/tessera_staff_boot.php';
header('Content-Type: text/plain; charset=utf-8');

include __DIR__ . '/../connect.php';

$vehicle = trim((string) ($_POST['vehicle'] ?? ''));
$driver = trim((string) ($_POST['driver'] ?? ''));
$conductor = trim((string) ($_POST['conductor'] ?? ''));

if ($vehicle === '') {
	echo 'invalid';
	exit;
}

$v = mysqli_real_escape_string($db, $vehicle);
$d = mysqli_real_escape_string($db, $driver !== '' ? $driver : '-');
$c = mysqli_real_escape_string($db, $conductor !== '' ? $conductor : '-');

$select = mysqli_query($db, "SELECT name FROM vehicles WHERE name='$v' LIMIT 1");
if ($select && mysqli_num_rows($select) > 0) {
	echo 'found';
	exit;
}

$insert = mysqli_query($db, "INSERT INTO vehicles (name, driver, conductor) VALUES ('$v','$d','$c')");
if ($insert) {
	echo 'succesfull';
} else {
	http_response_code(500);
	echo 'insert failed: ' . mysqli_error($db);
}
