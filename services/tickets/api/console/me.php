<?php
/**
 * GET /tickets/api/console/me.php — current staff (session required).
 */
declare(strict_types=1);

require_once dirname(__DIR__, 2) . '/includes/tessera_console_api.php';
require_once dirname(__DIR__, 2) . '/includes/tessera_rbac.php';

tessera_console_boot('GET, OPTIONS');
tessera_console_require_method('GET');
$username = tessera_console_require_session();

$role = 'tickets_agent';
$capabilities = [];
$dbOk = false;

$connect = dirname(__DIR__, 2) . '/connect.php';
if (is_file($connect)) {
	require_once $connect;
	if (isset($db) && $db instanceof mysqli && !$db->connect_error) {
		$dbOk = true;
		$role = tessera_user_role_code($db, $username);
		$capabilities = tessera_user_capabilities($db, $username);
	}
}

if (!$dbOk) {
	tessera_console_error('db_unavailable', 'Database unavailable', 503);
}

$tenant = [
	'id' => function_exists('tessera_current_tenant_id') ? tessera_current_tenant_id() : null,
	'slug' => function_exists('tessera_resolve_tenant_slug') ? tessera_resolve_tenant_slug() : null,
];

tessera_console_ok([
	'username' => $username,
	'role' => $role,
	'capabilities' => $capabilities,
	'tenant' => $tenant,
	'session' => [
		'module_hint' => $_SESSION['module'] ?? null,
	],
]);
