<?php
/**
 * Starts the session before any page output.
 * Wave I1: isolate Control cookie from staff modules.
 * Required because many legacy pages emit HTML before including top_header.php.
 */
@ini_set('display_errors', '0');
@error_reporting(E_ALL & ~E_NOTICE & ~E_WARNING & ~E_DEPRECATED);

if (PHP_SAPI !== 'cli' && session_status() !== PHP_SESSION_ACTIVE) {
	$uri = (string) ($_SERVER['REQUEST_URI'] ?? '');
	$script = (string) ($_SERVER['SCRIPT_NAME'] ?? '');
	$host = strtolower((string) ($_SERVER['HTTP_HOST'] ?? ''));
	$host = preg_replace('/:\\d+$/', '', $host);
	$controlHost = strtolower((string) (getenv('CONTROL_PUBLIC_HOST') ?: 'console.tessera.co.tz'));

	$isControl = str_starts_with($uri, '/control')
		|| str_starts_with($script, '/control')
		|| ($host !== '' && ($host === $controlHost || $host === 'console.tessera.co.tz'));

	session_name($isControl ? 'tessera_control' : 'tessera_staff');
	session_set_cookie_params([
		'lifetime' => 0,
		'path' => '/',
		'secure' => true,
		'httponly' => true,
		'samesite' => 'Lax',
	]);
	session_start();
}

/* Unknown {slug}.tessera.co.tz → friendly 404 (wildcard DNS otherwise serves blank console) */
if (PHP_SAPI !== 'cli') {
	$host = strtolower((string) ($_SERVER['HTTP_HOST'] ?? ''));
	$host = preg_replace('/:\\d+$/', '', $host) ?? '';
	$controlHost = strtolower((string) (getenv('CONTROL_PUBLIC_HOST') ?: 'console.tessera.co.tz'));
	$uri = (string) ($_SERVER['REQUEST_URI'] ?? '');
	$isControl = str_starts_with($uri, '/control')
		|| ($host !== '' && ($host === $controlHost || $host === 'console.tessera.co.tz'));
	if (!$isControl && preg_match('/^[a-z0-9-]+\\.tessera\\.co\\.tz$/', $host)) {
		$tenantInc = '/var/www/html/tickets/includes/tessera_tenant.php';
		if (!is_file($tenantInc)) {
			$tenantInc = dirname(__DIR__, 2) . '/services/tickets/includes/tessera_tenant.php';
		}
		if (is_file($tenantInc)) {
			require_once $tenantInc;
			if (function_exists('tessera_abort_unknown_tenant')) {
				tessera_abort_unknown_tenant();
			}
		}
	}
}
