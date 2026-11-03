<?php
/**
 * Bootstrap session + DB before any HTML output.
 * Include at the very top of Accounts pages (before DOCTYPE).
 */
ini_set('display_errors', '0');
error_reporting(E_ERROR | E_PARSE);

require_once __DIR__ . '/../connect/connect.php';

if (session_status() !== PHP_SESSION_ACTIVE) {
	session_start();
}

require_once __DIR__ . '/tessera_tenant.php';
tessera_deny_if_tenant_suspended('redirect');

$username = isset($_SESSION['username']) ? (string) $_SESSION['username'] : '';
if ($username === '') {
	header('Location: log_out.php');
	exit;
}

$footer = 'Tessera';
$firstname = '';
$surname = '';
$level = '';
$image = '';
$select2 = mysqli_query($db, "SELECT * FROM users where username='" . mysqli_real_escape_string($db, $username) . "'");
if ($select2 && mysqli_num_rows($select2) != 0) {
	while ($get12 = mysqli_fetch_array($select2)) {
		$firstname = $get12['firstname'];
		$surname = $get12['surname'];
		$system_user = $get12['username'];
		$firstStringCharacter = substr((string) $surname, 0, 1);
		$fullname = "$firstname.$firstStringCharacter.";
		$fullname_user = "$firstname $surname ";
		$level = $get12['role'];
		$image = $get12['image'];
	}
}
$this_page = basename($_SERVER['PHP_SELF'], '.php') . '.php';
$modification_time = @filemtime($this_page);
$url_time = '?v=' . ($modification_time ?: time());
$shell_v = @filemtime(__DIR__ . '/..//accounts/css/sr-platform-shell.css') ?: time();

require_once __DIR__ . '/sr_feather_icons.php';

if (!function_exists('tessera_operator_shell_redirect_legacy_get')) {
	require_once dirname(__DIR__, 3) . '/tickets/includes/tessera_operator_spa.php';
}
tessera_operator_shell_redirect_legacy_get();
