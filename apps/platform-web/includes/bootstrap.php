<?php
/**
 * Bootstrap Control session (isolated from Tickets).
 */
if (session_status() !== PHP_SESSION_ACTIVE) {
	session_name('tessera_control');
	session_set_cookie_params([
		'lifetime' => 0,
		'path' => '/',
		'secure' => (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off')
			|| (isset($_SERVER['HTTP_X_FORWARDED_PROTO']) && $_SERVER['HTTP_X_FORWARDED_PROTO'] === 'https'),
		'httponly' => true,
		'samesite' => 'Lax',
	]);
	session_start();
}

require_once __DIR__ . '/control_config.php';
require_once __DIR__ . '/connect.php';
require_once __DIR__ . '/tessera_password.php';
require_once __DIR__ . '/control_crypto.php';
require_once __DIR__ . '/control_data.php';
require_once __DIR__ . '/control_metrics.php';
require_once __DIR__ . '/control_ui.php';
require_once __DIR__ . '/control_auth.php';
require_once __DIR__ . '/control_payments.php';
require_once __DIR__ . '/control_payouts.php';
require_once __DIR__ . '/control_staff.php';
require_once __DIR__ . '/control_golive.php';
require_once __DIR__ . '/control_ops.php';
require_once __DIR__ . '/control_alerts.php';

control_enforce_host_isolation();
