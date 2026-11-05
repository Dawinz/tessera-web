<?php
/**
 * GET /tickets/api/console/api_keys.php — list API keys (S-05).
 */
declare(strict_types=1);

require_once dirname(__DIR__, 2) . '/includes/tessera_console_api.php';
require_once dirname(__DIR__, 2) . '/includes/tessera_rbac.php';
require_once dirname(__DIR__, 2) . '/includes/tessera_tenant.php';
require_once dirname(__DIR__, 2) . '/includes/tessera_settings_api_keys.php';

tessera_console_boot('GET, OPTIONS');
tessera_console_require_method('GET');
$username = tessera_console_require_session();

require_once dirname(__DIR__, 2) . '/connect.php';
if (!isset($db) || !($db instanceof mysqli) || $db->connect_error) {
	tessera_console_error('db_unavailable', 'Database unavailable', 503);
}
tessera_console_require_capability($db, $username, 'api_keys');

$tenantId = function_exists('tessera_current_tenant_id') ? (int) tessera_current_tenant_id() : 0;
if ($tenantId < 1) {
	$row = function_exists('tessera_tenant_row') ? tessera_tenant_row() : null;
	$tenantId = (int) ($row['id'] ?? 1);
}

$result = tessera_settings_api_keys_list($db, $tenantId);
if (empty($result['ok'])) {
	tessera_console_error('not_found', (string) ($result['error'] ?? 'Not found'), 404);
}

tessera_console_ok([
	'items' => $result['items'] ?? [],
	'meta' => $result['meta'] ?? [],
]);
