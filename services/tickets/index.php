<?php
/**
 * Tickets entry - unified operator login on the tenant console host.
 */
if (session_status() !== PHP_SESSION_ACTIVE) {
	session_start();
}

require_once __DIR__ . '/includes/sr_env.php';
require_once __DIR__ . '/includes/tessera_tenant.php';

$next = '/tickets/';
if (function_exists('tessera_host_is_platform_marketing') && tessera_host_is_platform_marketing()) {
	header('Location: ' . tessera_operator_console_url('/login/?next=' . rawurlencode($next)));
	exit;
}

if (!empty($_SESSION['username'])) {
	require_once __DIR__ . '/includes/tessera_operator_spa.php';
	header('Location: ' . tessera_operator_shell_url('tickets', 'home.php'));
	exit;
}

$dest = '/login/?next=' . rawurlencode($next);
if (isset($_GET['suspended'])) {
	$dest .= '&suspended=1';
}
header('Location: ' . $dest);
exit;
