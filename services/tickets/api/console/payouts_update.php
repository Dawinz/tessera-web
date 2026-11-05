<?php
/**
 * POST /tickets/api/console/payouts_update.php — save payout profile (S-04).
 */
declare(strict_types=1);

require_once dirname(__DIR__, 2) . '/includes/tessera_console_api.php';
require_once dirname(__DIR__, 2) . '/includes/tessera_rbac.php';
require_once dirname(__DIR__, 2) . '/includes/tessera_tenant.php';
require_once dirname(__DIR__, 2) . '/includes/tessera_settings_payouts.php';

tessera_console_boot('POST, OPTIONS');
tessera_console_require_method('POST');
$username = tessera_console_require_session();

require_once dirname(__DIR__, 2) . '/connect.php';
if (!isset($db) || !($db instanceof mysqli) || $db->connect_error) {
	tessera_console_error('db_unavailable', 'Database unavailable', 503);
}
tessera_console_require_capability($db, $username, 'payouts');

$tenantId = function_exists('tessera_current_tenant_id') ? (int) tessera_current_tenant_id() : 0;
if ($tenantId < 1) {
	$row = function_exists('tessera_tenant_row') ? tessera_tenant_row() : null;
	$tenantId = (int) ($row['id'] ?? 1);
}

$raw = file_get_contents('php://input');
$json = is_string($raw) && $raw !== '' ? json_decode($raw, true) : null;
if (!is_array($json)) {
	$json = $_POST;
}
if (!is_array($json)) {
	tessera_console_error('validation_error', 'Expected JSON body', 422);
}

$result = tessera_settings_payouts_save($db, $tenantId, $json, $username);
if (empty($result['ok'])) {
	tessera_console_error('validation_error', (string) ($result['error'] ?? 'Save failed'), 422);
}

tessera_console_ok([
	'item' => $result['item'],
	'settlements' => $result['settlements'] ?? [],
	'message' => (string) ($result['message'] ?? 'Payout profile saved'),
]);
