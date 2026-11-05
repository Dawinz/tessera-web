<?php
/**
 * GET /tickets/api/console/notifications.php — messaging settings (S-03).
 */
declare(strict_types=1);

require_once dirname(__DIR__, 2) . '/includes/tessera_console_api.php';
require_once dirname(__DIR__, 2) . '/includes/tessera_rbac.php';
require_once dirname(__DIR__, 2) . '/includes/tessera_tenant.php';
require_once dirname(__DIR__, 2) . '/includes/tessera_settings_notifications.php';

tessera_console_boot('GET, OPTIONS');
tessera_console_require_method('GET');
$username = tessera_console_require_session();

require_once dirname(__DIR__, 2) . '/connect.php';
if (!isset($db) || !($db instanceof mysqli) || $db->connect_error) {
	tessera_console_error('db_unavailable', 'Database unavailable', 503);
}
tessera_console_require_capability($db, $username, 'notifications');

$tenantId = function_exists('tessera_current_tenant_id') ? (int) tessera_current_tenant_id() : 0;
if ($tenantId < 1) {
	$row = function_exists('tessera_tenant_row') ? tessera_tenant_row() : null;
	$tenantId = (int) ($row['id'] ?? 1);
}

$result = tessera_settings_notifications_get($db, $tenantId);
if (empty($result['ok'])) {
	tessera_console_error('not_found', (string) ($result['error'] ?? 'Not found'), 404);
}

tessera_console_ok(['item' => $result['item']]);
