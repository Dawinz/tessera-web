<?php
/**
 * Cargo AJAX login - must return ONLY the role string (or "nothing").
 */
ini_set('display_errors', '0');
error_reporting(E_ERROR | E_PARSE);

include 'connect.php';
require_once __DIR__ . '/includes/tessera_password.php';
require_once __DIR__ . '/includes/tessera_rate_limit.php';
require_once __DIR__ . '/includes/tessera_tenant.php';

if (!tessera_rate_limit_allow('cargo_login')) {
	echo 'nothing';
	exit;
}

if (session_status() !== PHP_SESSION_ACTIVE) {
	session_start();
}

header('Content-Type: text/plain; charset=utf-8');

if (tessera_tenant_blocks_access()) {
	tessera_audit_blocked_access('login', isset($_POST['username']) ? trim((string) $_POST['username']) : '');
	echo 'suspended';
	exit;
}

$username = isset($_POST['username']) ? trim((string) $_POST['username']) : '';
$password = isset($_POST['password']) ? (string) $_POST['password'] : '';

if ($username === '' || $password === '') {
	echo 'nothing';
	exit;
}

$u = mysqli_real_escape_string($db, $username);
$select1 = mysqli_query(
	$db,
	"SELECT username, role, password FROM users WHERE status='active' AND (username='$u' OR email='$u' OR number='$u') LIMIT 1"
);

if ($select1 && ($row = mysqli_fetch_assoc($select1)) && tessera_password_verify($password, $row['password'])) {
	$_SESSION['username'] = $row['username'];
	if (!preg_match('/^\$(2[ay]|argon2)/', (string) $row['password'])) {
		$h = mysqli_real_escape_string($db, tessera_password_hash($password));
		$un = mysqli_real_escape_string($db, $row['username']);
		@mysqli_query($db, "UPDATE users SET password='$h' WHERE username='$un' LIMIT 1");
	}
	echo $row['role'];
	exit;
}

echo 'nothing';
