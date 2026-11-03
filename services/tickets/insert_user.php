<?php
require_once __DIR__ . '/includes/tessera_staff_boot.php';
require_once __DIR__ . '/connect.php';
require_once __DIR__ . '/includes/tessera_staff_sync.php';

$firstname = trim((string) ($_POST['firstname'] ?? $_POST['fullname'] ?? ''));
$lastname = trim((string) ($_POST['lastname'] ?? ''));
$username = trim((string) ($_POST['username'] ?? ''));
$password_no = (string) ($_POST['password_no'] ?? '');
$number = trim((string) ($_POST['number'] ?? ''));
$email = trim((string) ($_POST['email'] ?? ''));
$role = trim((string) ($_POST['role'] ?? ''));

if ($username === '' || $password_no === '' || $role === '') {
	echo 'invalid';
	exit;
}

$code = tessera_map_legacy_role($role);
$res = tessera_create_staff_everywhere([
	'username' => $username,
	'password' => $password_no,
	'firstname' => $firstname !== '' ? $firstname : $username,
	'surname' => $lastname,
	'email' => $email,
	'number' => $number,
	'status' => 'active',
], $code);

echo !empty($res['ok']) ? 'succesfull' : (($res['message'] ?? '') === 'Username already exists.' ? 'found' : 'error');
