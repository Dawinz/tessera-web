<?php
/**
 * Accounts login entry - unified operator login at /login/.
 */
if (session_status() !== PHP_SESSION_ACTIVE) {
	session_start();
}
if (!empty($_SESSION['username'])) {
	require_once dirname(__DIR__, 2) . '/tickets/includes/tessera_operator_spa.php';
	header('Location: ' . tessera_operator_shell_url('accounts', 'manifest.php'));
	exit;
}
header('Location: /login/?next=' . rawurlencode('/operator/app.php?module=accounts&page=manifest.php'));
exit;
