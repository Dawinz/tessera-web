<?php
/**
 * End an active Tessera support session and return to Control.
 */
if (session_status() !== PHP_SESSION_ACTIVE) {
	session_start();
}

$wasSupport = !empty($_SESSION['tessera_support']);
$_SESSION = [];
if (ini_get('session.use_cookies')) {
	$p = session_get_cookie_params();
	setcookie(session_name(), '', time() - 42000, $p['path'], $p['domain'], $p['secure'], $p['httponly']);
}
session_destroy();

$host = strtolower((string) ($_SERVER['HTTP_HOST'] ?? ''));
if ($host === 'localhost' || str_starts_with($host, '127.0.0.1')) {
	header('Location: http://localhost/control/operators.php');
} else {
	header('Location: /control/operators.php');
}
exit;
