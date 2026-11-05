<?php
/** POST /tickets/api/console/pinned_delete.php — remove one of your pinned trips. */
declare(strict_types=1);

require_once dirname(__DIR__, 2) . '/includes/tessera_console_api.php';
require_once dirname(__DIR__, 2) . '/includes/tessera_rbac.php';
require_once dirname(__DIR__, 2) . '/includes/tessera_tenant.php';
require_once dirname(__DIR__, 2) . '/includes/tessera_tickets_leftovers.php';

tessera_console_boot('POST, OPTIONS');
tessera_console_require_method('POST');
$username = tessera_console_require_session();
require_once dirname(__DIR__, 2) . '/connect.php';
if (!isset($db) || !($db instanceof mysqli) || $db->connect_error) {
	tessera_console_error('db_unavailable', 'Database unavailable', 503);
}
tessera_console_require_capability($db, $username, 'tickets');

$raw = file_get_contents('php://input');
$payload = json_decode($raw ?: '[]', true);
if (!is_array($payload)) {
	tessera_console_error('validation_error', 'Invalid JSON body', 422);
}
$id = (int) ($payload['id'] ?? 0);
if ($id < 1) {
	tessera_console_error('validation_error', 'Pinned seat id is required', 422, ['fields' => ['id' => 'required']]);
}
$result = tessera_tickets_pinned_delete($db, $username, $id);
if (empty($result['ok'])) {
	tessera_console_error('not_found', (string) ($result['error'] ?? 'Pinned seat was not found'), 404);
}
tessera_console_ok(['id' => $id]);
