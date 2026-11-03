<?php
session_start();
if (empty($_SESSION['username'])) {
	http_response_code(401);
	echo 'unauthorized';
	exit;
}
include '../connect/connect.php';
header('Content-Type: text/plain; charset=utf-8');

$particulars = $_POST['particular'] ?? [];
$categories = $_POST['category'] ?? [];
$groups = $_POST['group'] ?? [];
if (!is_array($particulars) || count($particulars) < 1) {
	echo 'invalid';
	exit;
}

$any = false;
for ($count = 0; $count < count($particulars); $count++) {
	$particular = trim((string) ($particulars[$count] ?? ''));
	$category = trim((string) ($categories[$count] ?? ''));
	$group = trim((string) ($groups[$count] ?? ''));
	if ($particular === '' || $category === '') {
		continue;
	}

	$pEsc = mysqli_real_escape_string($db, $particular);
	$cEsc = mysqli_real_escape_string($db, $category);
	$select = mysqli_query($db, "SELECT id FROM particular_category WHERE name='$pEsc' AND category='$cEsc' LIMIT 1");
	if ($select && mysqli_num_rows($select) > 0) {
		echo 'found';
		continue;
	}

	$front_name = $particular . '(' . $group . ')';
	$particulars_detail = '';
	$sqlQ = 'INSERT INTO particular_category (name, particulars_detail, category, cat_group, front_name) VALUES (?,?,?,?,?)';
	$stmt = $db->prepare($sqlQ);
	if (!$stmt) {
		http_response_code(500);
		echo 'prepare failed: ' . $db->error;
		exit;
	}
	$stmt->bind_param('sssss', $particular, $particulars_detail, $category, $group, $front_name);
	if (!$stmt->execute()) {
		http_response_code(500);
		echo 'insert failed: ' . $stmt->error;
		exit;
	}
	$any = true;
}

if ($any) {
	echo 'succesfull';
}
