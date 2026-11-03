<?php
session_start();
if (empty($_SESSION['username'])) {
	http_response_code(401);
	echo 'unauthorized';
	exit;
}
include '../connect/connect.php';
header('Content-Type: text/plain; charset=utf-8');

$first = trim((string) ($_POST['first'] ?? ''));
$secondary = trim((string) ($_POST['secondary'] ?? ''));
$value = preg_replace('/[^0-9.]+/', '', (string) ($_POST['value'] ?? '0'));

if ($first === '' || $secondary === '' || $first === $secondary) {
	echo 'invalid';
	exit;
}
if ($value === '' || (float) $value == 0) {
	$value = '1';
}

$f = mysqli_real_escape_string($db, $first);
$s = mysqli_real_escape_string($db, $secondary);
$select = mysqli_query($db, "SELECT id FROM currency_value WHERE first_point='$f' AND second_point='$s' LIMIT 1");
if ($select && mysqli_num_rows($select) > 0) {
	echo 'found';
	exit;
}

$sqlQ = 'INSERT INTO currency_value (first_point, second_point, value) VALUES (?,?,?)';
$stmt = $db->prepare($sqlQ);
if (!$stmt) {
	http_response_code(500);
	echo 'prepare failed: ' . $db->error;
	exit;
}
$stmt->bind_param('sss', $first, $secondary, $value);
if ($stmt->execute()) {
	echo 'succesfull';
} else {
	http_response_code(500);
	echo 'insert failed: ' . $stmt->error;
}
