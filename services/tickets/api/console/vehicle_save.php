<?php
/**
 * POST /tickets/api/console/vehicle_save.php — create or update vehicle (T-P0-05).
 * Body JSON: { id?, name, driver, conductor, seats, detail?, state?, dedicate_seat_price?, active? }
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

$raw = file_get_contents('php://input');
$payload = json_decode($raw ?: '[]', true);
if (!is_array($payload)) {
	tessera_console_error('validation_error', 'Invalid JSON body', 422);
}

$id = (int) ($payload['id'] ?? 0);
$name = trim((string) ($payload['name'] ?? ''));
$driver = trim((string) ($payload['driver'] ?? ''));
$conductor = trim((string) ($payload['conductor'] ?? ''));
$seats = (int) ($payload['seats'] ?? 0);
$detail = trim((string) ($payload['detail'] ?? ''));
$state = strtolower(trim((string) ($payload['state'] ?? 'normal')));
$dedicate = trim((string) ($payload['dedicate_seat_price'] ?? ''));
$active = array_key_exists('active', $payload) ? (bool) $payload['active'] : true;
$status = $active ? 'active' : 'off';

if ($name === '' || $seats < 1 || $seats > 120) {
	tessera_console_error('validation_error', 'Vehicle number and seats (1–120) are required', 422, [
		'fields' => [
			'name' => $name === '' ? 'required' : null,
			'seats' => ($seats < 1 || $seats > 120) ? 'invalid' : null,
		],
	]);
}
if ($id < 1 && ($driver === '' || $conductor === '')) {
	tessera_console_error('validation_error', 'Driver and conductor are required when creating a vehicle', 422);
}
if (!in_array($state, ['normal', 'vip'], true)) {
	$state = 'normal';
}

$tenantId = (int) tessera_current_tenant_id();
if ($tenantId < 1) {
	tessera_console_error('forbidden', 'No tenant context', 403);
}

$detailB64 = base64_encode($detail);

if ($id > 0) {
	$stmt = $db->prepare(
		'UPDATE vehicle SET name=?, driver=?, conductor=?, seats=?, detail=?, status=?, state=?, dedicate_seat_price=?
		 WHERE id=? AND tenant_id=? LIMIT 1'
	);
	if (!$stmt) {
		tessera_console_error('server_error', 'Could not prepare update', 500);
	}
	$stmt->bind_param(
		'sssissssii',
		$name,
		$driver,
		$conductor,
		$seats,
		$detailB64,
		$status,
		$state,
		$dedicate,
		$id,
		$tenantId
	);
	$ok = $stmt->execute();
	$affected = $stmt->affected_rows;
	$stmt->close();
	if (!$ok || $affected < 0) {
		tessera_console_error('server_error', 'Could not save vehicle', 500);
	}
	/* affected_rows can be 0 if values unchanged */
	$check = $db->prepare('SELECT id FROM vehicle WHERE id=? AND tenant_id=? LIMIT 1');
	$check->bind_param('ii', $id, $tenantId);
	$check->execute();
	$exists = $check->get_result();
	$found = $exists && $exists->num_rows > 0;
	$check->close();
	if (!$found) {
		tessera_console_error('not_found', 'Vehicle not found', 404);
	}
} else {
	$dup = $db->prepare('SELECT id FROM vehicle WHERE name=? AND tenant_id=? LIMIT 1');
	$dup->bind_param('si', $name, $tenantId);
	$dup->execute();
	$dupRes = $dup->get_result();
	if ($dupRes && $dupRes->num_rows > 0) {
		$dup->close();
		tessera_console_error('conflict', 'That vehicle number is already registered', 409);
	}
	$dup->close();

	$location = '';
	$routeId = '';
	$layoutJson = null;
	if (tessera_vehicle_has_layout_column($db)) {
		$patterns = tessera_default_seat_patterns($seats);
		$extras = in_array($seats, [45, 59], true) ? ['xt1', 'xt2'] : [];
		$layoutJson = tessera_vehicle_layout_to_json($patterns, $extras);
	}

	if ($layoutJson !== null) {
		$ins = $db->prepare(
			'INSERT INTO vehicle (name, location, driver, conductor, seats, route_id, detail, seat_layout_json, status, state, dedicate_seat_price, tenant_id)
			 VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)'
		);
		if (!$ins) {
			tessera_console_error('server_error', 'Could not prepare insert', 500);
		}
		$ins->bind_param(
			'ssssissssssi',
			$name,
			$location,
			$driver,
			$conductor,
			$seats,
			$routeId,
			$detailB64,
			$layoutJson,
			$status,
			$state,
			$dedicate,
			$tenantId
		);
	} else {
		$ins = $db->prepare(
			'INSERT INTO vehicle (name, location, driver, conductor, seats, route_id, detail, status, state, dedicate_seat_price, tenant_id)
			 VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)'
		);
		if (!$ins) {
			tessera_console_error('server_error', 'Could not prepare insert', 500);
		}
		$ins->bind_param(
			'ssssisssssi',
			$name,
			$location,
			$driver,
			$conductor,
			$seats,
			$routeId,
			$detailB64,
			$status,
			$state,
			$dedicate,
			$tenantId
		);
	}
	if (!$ins->execute()) {
		$ins->close();
		tessera_console_error('server_error', 'Could not create vehicle', 500);
	}
	$id = (int) $ins->insert_id;
	$ins->close();
}

tessera_console_ok([
	'id' => $id,
	'name' => $name,
], [], $id > 0 && (int) ($payload['id'] ?? 0) < 1 ? 201 : 200);
