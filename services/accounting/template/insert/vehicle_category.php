<?php
session_start();
if (empty($_SESSION['username'])) {
	http_response_code(401);
	echo 'unauthorized';
	exit;
}
include '../connect/connect.php';
header('Content-Type: text/plain; charset=utf-8');

$categroy = trim((string) ($_POST['categroy'] ?? $_POST['category'] ?? ''));
$detail = trim((string) ($_POST['detail'] ?? ''));
$partiuclar = trim((string) ($_POST['partiuclar'] ?? $_POST['particular'] ?? ''));

if ($categroy === '') {
	echo 'invalid';
	exit;
}

$cEsc = mysqli_real_escape_string($db, $categroy);
$select = mysqli_query($db, "SELECT name FROM vehicle_category WHERE name='$cEsc' LIMIT 1");
if ($select && mysqli_num_rows($select) > 0) {
	echo 'found';
	exit;
}

$sqlQ = 'INSERT INTO vehicle_category (name, detail, partiuclar) VALUES (?,?,?)';
$stmt = $db->prepare($sqlQ);
if (!$stmt) {
	http_response_code(500);
	echo 'prepare failed: ' . $db->error;
	exit;
}
$stmt->bind_param('sss', $categroy, $detail, $partiuclar);
if ($stmt->execute()) {
	echo 'succesfull';
} else {
	http_response_code(500);
	echo 'insert failed: ' . $stmt->error;
}
