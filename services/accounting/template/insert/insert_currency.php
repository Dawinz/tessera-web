<?php
session_start();
if (empty($_SESSION['username'])) {
	http_response_code(401);
	echo 'unauthorized';
	exit;
}
include '../connect/connect.php';
header('Content-Type: text/plain; charset=utf-8');

$name = trim((string) ($_POST['name'] ?? ''));
$value = preg_replace('/[^0-9.]+/', '', (string) ($_POST['value'] ?? '0'));
$symbol = trim((string) ($_POST['symbol'] ?? ''));
$detail = str_replace(["'", ','], ['_', '~'], (string) ($_POST['detail'] ?? ''));
$status = '<span class="badge badge-secondary">Secondary</span>';

if ($name === '' || $symbol === '') {
	echo 'invalid';
	exit;
}
if ($value === '') {
	$value = '1';
}

$nameEsc = mysqli_real_escape_string($db, $name);
$select = mysqli_query($db, "SELECT name FROM currency WHERE name='$nameEsc' LIMIT 1");
if ($select && mysqli_num_rows($select) > 0) {
	echo 'found';
	exit;
}

$sqlQ = 'INSERT INTO currency (name, value_no, symbol, detail, status) VALUES (?,?,?,?,?)';
$stmt = $db->prepare($sqlQ);
if (!$stmt) {
	http_response_code(500);
	echo 'prepare failed: ' . $db->error;
	exit;
}
$stmt->bind_param('sssss', $name, $value, $symbol, $detail, $status);
if ($stmt->execute()) {
	echo 'succesfull';
} else {
	http_response_code(500);
	echo 'insert failed: ' . $stmt->error;
}
