<?php
/**
 * POST /tickets/api/console/vehicle_layout.php — save seat layout (T-P0-05).
 * Body JSON: { id, patterns?: string[], grid?: string[][], extras?: string[] }
 */
declare(strict_types=1);

require_once dirname(__DIR__, 2) . '/includes/tessera_console_api.php';
require_once dirname(__DIR__, 2) . '/includes/tessera_rbac.php';
require_once dirname(__DIR__, 2) . '/includes/tessera_tenant.php';
require_once dirname(__DIR__, 2) . '/includes/vehicle_seat_layout.php';

tessera_console_boot('POST, OPTIONS');
tessera_console_require_method('POST');
$username = tessera_console_require_session();

require_once dirname(__DIR__, 2) . '/connect.php';
if (!isset($db) || !($db instanceof mysqli) || $db->connect_error) {
	tessera_console_error('db_unavailable', 'Database unavailable', 503);
}
tessera_console_require_capability($db, $username, 'tickets');

if (!tessera_vehicle_has_layout_column($db)) {
	tessera_console_error('unavailable', 'Seat layout column missing — run migration 019', 503);
}

$raw = file_get_contents('php://input');
$payload = json_decode($raw ?: '[]', true);
if (!is_array($payload)) {
	tessera_console_error('validation_error', 'Invalid JSON body', 422);
}

$id = (int) ($payload['id'] ?? 0);
$tenantId = (int) tessera_current_tenant_id();
if ($id < 1 || $tenantId < 1) {
	tessera_console_error('validation_error', 'Vehicle id is required', 422);
}

$patterns = [];
if (!empty($payload['patterns']) && is_array($payload['patterns'])) {
	foreach ($payload['patterns'] as $p) {
		$clean = preg_replace('/[^i\-]/', '', (string) $p);
		if ($clean !== '') {
			$patterns[] = $clean;
		}
	}
} elseif (!empty($payload['grid']) && is_array($payload['grid'])) {
	$patterns = tessera_grid_to_patterns($payload['grid']);
}

$extras = [];
if (!empty($payload['extras']) && is_array($payload['extras'])) {
	foreach ($payload['extras'] as $label) {
		$label = trim((string) $label);
		if ($label !== '') {
			$extras[] = $label;
		}
	}
}

if ($patterns === []) {
	tessera_console_error('validation_error', 'Layout cannot be empty', 422);
}

$stmt = $db->prepare('SELECT seats FROM vehicle WHERE id = ? AND tenant_id = ? LIMIT 1');
$stmt->bind_param('ii', $id, $tenantId);
$stmt->execute();
$res = $stmt->get_result();
$row = $res ? $res->fetch_assoc() : null;
$stmt->close();
if (!$row) {
	tessera_console_error('not_found', 'Vehicle not found', 404);
}

$layoutSeats = tessera_count_seats_in_patterns($patterns, $extras);
$capacity = (int) $row['seats'];
if ($layoutSeats !== $capacity) {
	tessera_console_error('conflict', "Layout has $layoutSeats seats but capacity is $capacity", 409, [
		'layout_seats' => $layoutSeats,
		'capacity' => $capacity,
	]);
}

$json = tessera_vehicle_layout_to_json($patterns, $extras);
$upd = $db->prepare('UPDATE vehicle SET seat_layout_json = ? WHERE id = ? AND tenant_id = ? LIMIT 1');
$upd->bind_param('sii', $json, $id, $tenantId);
$ok = $upd->execute();
$upd->close();
if (!$ok) {
	tessera_console_error('server_error', 'Could not save layout', 500);
}

tessera_console_ok([
	'layout_seats' => $layoutSeats,
	'layout_ok' => true,
	'patterns' => $patterns,
	'extras' => $extras,
	'labels' => tessera_layout_labels_for_vehicle($patterns, $extras),
	'grid' => tessera_patterns_to_grid($patterns),
]);
