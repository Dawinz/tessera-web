<?php
/**
 * Shared boot for /login/ — resolves tickets helpers + optional cargo/accounts DBs.
 */
if (session_status() !== PHP_SESSION_ACTIVE) {
	session_start();
}

$ticketsRoot = null;
foreach ([
	dirname(__DIR__) . '/tickets',                    // Railway: /var/www/html/login → ../tickets
	dirname(__DIR__, 2) . '/services/tickets',         // Monorepo: apps/operator-login → services/tickets
	dirname(__DIR__, 2) . '/tickets',
] as $candidate) {
	if (is_file($candidate . '/connect.php')) {
		$ticketsRoot = $candidate;
		break;
	}
}
if ($ticketsRoot === null) {
	http_response_code(500);
	echo 'Tickets service not found.';
	exit;
}

require_once $ticketsRoot . '/includes/sr_env.php';
require_once $ticketsRoot . '/includes/tessera_tenant.php';
require_once $ticketsRoot . '/includes/tessera_password.php';
require_once $ticketsRoot . '/includes/tessera_rate_limit.php';
require_once $ticketsRoot . '/connect.php';

if (!function_exists('tessera_login_mysqli')) {
	function tessera_login_mysqli(string $dbName): ?mysqli {
		if (!function_exists('sr_db_config')) {
			return null;
		}
		$cfg = sr_db_config($dbName);
		mysqli_report(MYSQLI_REPORT_OFF);
		$m = mysqli_init();
		if (!$m) {
			return null;
		}
		@$m->options(MYSQLI_OPT_CONNECT_TIMEOUT, 5);
		@$m->real_connect($cfg['host'], $cfg['user'], $cfg['pass'], $cfg['name'], $cfg['port']);
		if ($m->connect_error) {
			return null;
		}
		$m->set_charset('utf8mb4');
		return $m;
	}
}

if (!function_exists('tessera_login_safe_next')) {
	/** Allow only in-app absolute paths. */
	function tessera_login_safe_next(?string $next): string {
		$next = trim((string) $next);
		if ($next === '' || $next[0] !== '/') {
			return '';
		}
		if (str_contains($next, '//') || str_contains($next, '..')) {
			return '';
		}
		$allowed = ['/tickets/', '/cargo/', '/accounts/', '/operator/', '/settings/', '/login/'];
		foreach ($allowed as $prefix) {
			if (str_starts_with($next, $prefix) || $next === rtrim($prefix, '/')) {
				return $next;
			}
		}
		return '';
	}
}

if (!function_exists('tessera_login_default_home')) {
	function tessera_login_default_home(string $source, string $role): string {
		$roleL = strtolower($role);
		$shell = dirname(__DIR__, 2) . '/services/tickets/includes/tessera_operator_spa.php';
		if (is_file($shell)) {
			require_once $shell;
		}
		if (function_exists('tessera_operator_shell_url')) {
			if ($source === 'ticket') {
				$page = (str_contains($roleL, 'agent') && !str_contains($roleL, 'admin'))
					? 'routes.php'
					: 'home.php';
				return tessera_operator_shell_url('tickets', $page);
			}
			if ($source === 'cargo') {
				$page = (str_contains($roleL, 'agent') && !str_contains($roleL, 'admin'))
					? 'routes.php'
					: 'home.php';
				return tessera_operator_shell_url('cargo', $page);
			}
			return tessera_operator_shell_url('accounts', 'manifest.php');
		}
		$roleL = strtolower($role);
		if ($source === 'ticket') {
			return (str_contains($roleL, 'agent') && !str_contains($roleL, 'admin'))
				? '/tickets/routes.php'
				: '/tickets/home.php';
		}
		if ($source === 'cargo') {
			return (str_contains($roleL, 'agent') && !str_contains($roleL, 'admin'))
				? '/cargo/routes.php'
				: '/cargo/home.php';
		}
		return '/accounts/manifest.php';
	}
}
