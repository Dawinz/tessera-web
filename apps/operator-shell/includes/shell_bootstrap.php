<?php
/**
 * Shared bootstrap for operator shell (session + user row from Tickets DB).
 */
define('TESSERA_OPERATOR_SHELL_APP', true);

require_once __DIR__ . '/shell_paths.php';
$__roots = tessera_operator_module_roots();
$ticketsRoot = $__roots['tickets'];
require_once $ticketsRoot . '/includes/tessera_staff_boot.php';
require_once $ticketsRoot . '/includes/tessera_operator_spa.php';

if (session_status() !== PHP_SESSION_ACTIVE) {
	session_start();
}
if (!isset($_SESSION['username'])) {
	$next = $_SERVER['REQUEST_URI'] ?? '/operator/app.php';
	header('Location: /login/?next=' . rawurlencode($next));
	exit;
}

require_once $ticketsRoot . '/connect.php';
require_once $ticketsRoot . '/includes/tessera_tenant.php';
require_once $ticketsRoot . '/includes/tessera_rbac.php';

tessera_abort_unknown_tenant();
tessera_deny_if_tenant_suspended('redirect');

$username = (string) $_SESSION['username'];
$firstname = '';
$surname = '';
$level = '';
$image = '';
$select2 = mysqli_query($db, "SELECT * FROM users WHERE username='" . mysqli_real_escape_string($db, $username) . "'");
if ($select2 && mysqli_num_rows($select2) !== 0) {
	while ($get12 = mysqli_fetch_array($select2)) {
		$firstname = $get12['firstname'];
		$surname = $get12['surname'];
		$firstStringCharacter = substr((string) $surname, 0, 1);
		$fullname = "$firstname.$firstStringCharacter.";
		$level = $get12['role'];
		$image = $get12['image'];
	}
} else {
	header('Location: /tickets/log_out.php');
	exit;
}
