<?php
/**
 * POST /tickets/api/console/vehicle_delete.php
 * Body JSON: { id }
 * Deletes a vehicle when it has no active ticket bookings (classic delete_vehilce.php).
 */
declare(strict_types=1);

require_once dirname(__DIR__, 2) . '/includes/tessera_console_api.php';
require_once dirname(__DIR__, 2) . '/includes/tessera_rbac.php';
require_once dirname(__DIR__, 2) . '/includes/tessera_tenant.php';

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
$tenantId = (int) tessera_current_tenant_id();
if ($id < 1 || $tenantId < 1) {
	tessera_console_error('validation_error', 'Vehicle id is required', 422, [
		'fields' => ['id' => 'required'],
	]);
}

$stmt = $db->prepare('SELECT id, name FROM vehicle WHERE id = ? AND tenant_id = ? LIMIT 1');
$stmt->bind_param('ii', $id, $tenantId);
$stmt->execute();
$row = $stmt->get_result()->fetch_assoc();
$stmt->close();
if (!$row) {
	tessera_console_error('not_found', 'Vehicle not found', 404);
}

$name = (string) ($row['name'] ?? '');
$chk = $db->prepare('SELECT id FROM booking WHERE bus=? AND ' . tessera_sql_tenant_eq() . ' LIMIT 1');
$chk->bind_param('s', $name);
$chk->execute();
$used = $chk->get_result();
$hasBookings = $used && $used->num_rows > 0;
$chk->close();
if ($hasBookings) {
	tessera_console_error('conflict', 'This vehicle has ticket bookings and cannot be deleted', 409);
}

$del = $db->prepare('DELETE FROM vehicle WHERE id = ? AND tenant_id = ? LIMIT 1');
$del->bind_param('ii', $id, $tenantId);
if (!$del->execute()) {
	$del->close();
	tessera_console_error('server_error', 'Could not delete vehicle', 500);
}
$del->close();

tessera_console_ok([
	'id' => $id,
	'name' => $name,
]);
