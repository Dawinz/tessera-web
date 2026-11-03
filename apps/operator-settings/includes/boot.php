<?php
/**
 * Operator settings boot — cross-module app at /settings/ (not under /tickets).
 */
if (session_status() !== PHP_SESSION_ACTIVE) {
	session_start();
}

$ticketsRoot = null;
foreach ([
	dirname(__DIR__, 2) . '/tickets',                 // Railway: /var/www/html/tickets
	dirname(__DIR__, 3) . '/services/tickets',         // Monorepo: services/tickets
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

if (!isset($_SESSION['username'])) {
	header('Location: /login/?next=' . rawurlencode('/settings/'));
	exit;
}

$sessionUser = (string) $_SESSION['username'];
require_once $ticketsRoot . '/includes/sr_env.php';
require_once $ticketsRoot . '/includes/tessera_tenant.php';
require_once $ticketsRoot . '/includes/tessera_rbac.php';
require_once $ticketsRoot . '/includes/tessera_vault.php';
require_once $ticketsRoot . '/connect.php';

if (!isset($db) || !($db instanceof mysqli) || $db->connect_error) {
	http_response_code(500);
	echo 'Database unavailable.';
	exit;
}

$username = $sessionUser;
$tenantId = function_exists('tessera_current_tenant_id') ? (int) tessera_current_tenant_id() : 1;
$settings_role = tessera_user_role_code($db, $username);
$settings_caps = tessera_user_capabilities($db, $username);
$SETTINGS_BASE = '/settings';
$TICKETS_WEB = '/tickets';
$ticketsIncludes = $ticketsRoot . '/includes';

$u = $db->real_escape_string($username);
$q = @$db->query("SELECT firstname, surname, role, image FROM users WHERE username='{$u}' LIMIT 1");
$row = $q ? $q->fetch_assoc() : null;
$firstname = (string) ($row['firstname'] ?? $username);
$surname = (string) ($row['surname'] ?? '');
$level = (string) ($row['role'] ?? $settings_role);
$image = (string) ($row['image'] ?? '');
$firstStringCharacter = $surname !== '' ? substr($surname, 0, 1) : '';
$Figure = $firstname . ($firstStringCharacter !== '' ? '.' . $firstStringCharacter . '.' : '');

if (!function_exists('settings_h')) {
	function settings_h(?string $s): string {
		return htmlspecialchars((string) $s, ENT_QUOTES, 'UTF-8');
	}
}

if (!function_exists('settings_can')) {
	function settings_can(string $cap): bool {
		global $db, $username;
		return function_exists('tessera_can') && tessera_can($db, (string) $username, $cap);
	}
}

if (
	!settings_can('settings') && !settings_can('payouts') && !settings_can('staff')
	&& !settings_can('notifications') && !settings_can('api_keys') && !settings_can('reports')
) {
	http_response_code(403);
	echo 'Access denied for this role.';
	exit;
}
