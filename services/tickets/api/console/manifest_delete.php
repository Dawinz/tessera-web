<?php
/**
 * POST /tickets/api/console/manifest_delete.php
 * Body JSON: { id }
 * Deletes a manifest only when it has zero seats (classic delete_manifest.php rule).
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
if ($id < 1) {
	tessera_console_error('validation_error', 'Manifest id is required', 422, [
		'fields' => ['id' => 'required'],
	]);
}

$sel = mysqli_query($db, 'SELECT id, seats, bus, trip_date FROM manifest WHERE id=' . $id . ' LIMIT 1');
if (!$sel || !($row = mysqli_fetch_assoc($sel))) {
	tessera_console_error('not_found', 'Manifest not found', 404);
}

$seats = (int) ($row['seats'] ?? 0);
if ($seats !== 0) {
	tessera_console_error('conflict', 'You can only delete a manifest with zero seats', 409, [
		'seats' => $seats,
	]);
}

if (!mysqli_query($db, 'DELETE FROM manifest WHERE id=' . $id . ' LIMIT 1')) {
	tessera_console_error('server_error', 'Could not delete manifest', 500);
}

tessera_console_ok([
	'id' => $id,
	'bus' => (string) ($row['bus'] ?? ''),
	'trip_date' => (string) ($row['trip_date'] ?? ''),
]);
