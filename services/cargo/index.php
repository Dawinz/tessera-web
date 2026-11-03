<?php
/**
 * Cargo entry - unified operator login on the tenant console host.
 */
if (session_status() !== PHP_SESSION_ACTIVE) {
	session_start();
}

$ticketsInc = __DIR__ . '/includes/tessera_tenant.php';
if (!is_file($ticketsInc)) {
	$ticketsInc = dirname(__DIR__) . '/tickets/includes/tessera_tenant.php';
}
if (is_file(__DIR__ . '/includes/sr_env.php')) {
	require_once __DIR__ . '/includes/sr_env.php';
}
if (is_file($ticketsInc)) {
	require_once $ticketsInc;
}

$next = '/cargo/';
if (function_exists('tessera_host_is_platform_marketing') && tessera_host_is_platform_marketing()) {
	header('Location: ' . tessera_operator_console_url('/login/?next=' . rawurlencode($next)));
	exit;
}

if (!empty($_SESSION['username'])) {
	require_once dirname(__DIR__) . '/tickets/includes/tessera_operator_spa.php';
	header('Location: ' . tessera_operator_shell_url('cargo', 'home.php'));
	exit;
}
header('Location: /login/?next=' . rawurlencode($next));
exit;
