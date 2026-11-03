<?php
/**
 * Accounts entry — unified operator login on the tenant console host.
 */
if (session_status() !== PHP_SESSION_ACTIVE) {
	session_start();
}

$tenantInc = __DIR__ . '/template/includes/tessera_tenant.php';
if (is_file(__DIR__ . '/template/includes/sr_env.php')) {
	require_once __DIR__ . '/template/includes/sr_env.php';
}
if (is_file($tenantInc)) {
	require_once $tenantInc;
} elseif (is_file('/var/www/html/tickets/includes/tessera_tenant.php')) {
	require_once '/var/www/html/tickets/includes/tessera_tenant.php';
}

$next = '/accounts/app.php';
if (function_exists('tessera_host_is_platform_marketing') && tessera_host_is_platform_marketing()) {
	header('Location: ' . tessera_operator_console_url('/login/?next=' . rawurlencode($next)));
	exit;
}

if (!empty($_SESSION['username'])) {
	header('Location: /accounts/app.php');
	exit;
}
header('Location: /login/?next=' . rawurlencode($next));
exit;
