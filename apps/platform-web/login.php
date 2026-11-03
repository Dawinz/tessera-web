<?php
/**
 * Tessera Control AJAX login — returns "ok" or "nothing".
 */
ini_set('display_errors', '0');
error_reporting(E_ERROR | E_PARSE);

require_once __DIR__ . '/includes/bootstrap.php';

header('Content-Type: text/plain; charset=utf-8');

$email = isset($_POST['username']) ? strtolower(trim((string) $_POST['username'])) : '';
$password = isset($_POST['password']) ? (string) $_POST['password'] : '';

$result = control_attempt_login($db ?? null, !empty($control_db_ok), $email, $password);
if (empty($result['ok'])) {
	echo 'nothing';
	exit;
}

$_SESSION['control_email'] = $result['email'];
$_SESSION['control_name'] = $result['name'];
$_SESSION['control_role'] = $result['role'];
$_SESSION['control_admin_id'] = (int) ($result['id'] ?? 0);
echo 'ok';
