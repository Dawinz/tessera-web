<?php
session_start();
if (empty($_SESSION['username'])) {
	http_response_code(401);
	echo 'unauthorized';
	exit;
}
include '../connect/connect.php';
header('Content-Type: text/plain; charset=utf-8');

$categories = $_POST['category'] ?? [];
$affects = $_POST['affect'] ?? [];
$names = $_POST['name'] ?? [];
if (!is_array($categories) || count($categories) < 1) {
	echo 'invalid';
	exit;
}

$any = false;
for ($count = 0; $count < count($categories); $count++) {
	$category = trim((string) ($categories[$count] ?? ''));
	$affect = trim((string) ($affects[$count] ?? ''));
	$name = trim((string) ($names[$count] ?? ''));
	if ($category === '' || $name === '') {
		continue;
	}

	$particular_category = '';
	$name_cat = '';
	$front_name = $category;
	$catEsc = mysqli_real_escape_string($db, $category);
	$select = mysqli_query($db, "SELECT category, name, front_name FROM particular_category WHERE front_name='$catEsc' LIMIT 1");
	if ($select && ($get = mysqli_fetch_assoc($select))) {
		$particular_category = (string) $get['category'];
		$name_cat = (string) $get['name'];
		$front_name = (string) $get['front_name'];
	} else {
		echo 'category missing';
		continue;
	}

	$nameEsc = mysqli_real_escape_string($db, $name);
	$select_route = mysqli_query($db, "SELECT id FROM particulars WHERE category='$catEsc' AND name='$nameEsc' LIMIT 1");
	if ($select_route && mysqli_num_rows($select_route) > 0) {
		echo 'found';
		continue;
	}

	$sub_particulars = '';
	$sqlQ = 'INSERT INTO particulars (name, category, account, cat_group, group_name, affect, sub_particulars) VALUES (?,?,?,?,?,?,?)';
	$stmt = $db->prepare($sqlQ);
	if (!$stmt) {
		http_response_code(500);
		echo 'prepare failed: ' . $db->error;
		exit;
	}
	$stmt->bind_param('sssssss', $name, $name_cat, $particular_category, $front_name, $name_cat, $affect, $sub_particulars);
	if (!$stmt->execute()) {
		http_response_code(500);
		echo 'insert failed: ' . $stmt->error;
		exit;
	}

	$select = mysqli_query($db, "SELECT particulars_detail FROM particular_category WHERE name='" . mysqli_real_escape_string($db, $name_cat) . "' AND particulars_detail != '' LIMIT 1");
	if ($select && ($get = mysqli_fetch_assoc($select)) && trim((string) $get['particulars_detail']) !== '') {
		$new_detail = $get['particulars_detail'] . ',' . $name;
	} else {
		$new_detail = $name;
	}
	$newEsc = mysqli_real_escape_string($db, $new_detail);
	mysqli_query($db, "UPDATE particular_category SET particulars_detail='$newEsc' WHERE front_name='$catEsc'");
	$any = true;
}

if ($any) {
	echo 'succesfull';
}
