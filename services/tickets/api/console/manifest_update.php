<?php
/**
 * POST /tickets/api/console/manifest_update.php
 * Body JSON: { id, driver, conductor, bus? }
 * Updates crew (+ optional vehicle) on a manifest row.
 */
declare(strict_types=1);

require_once dirname(__DIR__, 2) . '/includes/tessera_console_api.php';
require_once dirname(__DIR__, 2) . '/includes/tessera_rbac.php';
require_once dirname(__DIR__, 2) . '/includes/tessera_tenant.php';
require_once dirname(__DIR__, 2) . '/includes/tessera_manifests_list.php';

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
$driver = trim((string) ($payload['driver'] ?? ''));
$conductor = trim((string) ($payload['conductor'] ?? ''));
$newBus = trim((string) ($payload['bus'] ?? ''));

if ($id < 1) {
	tessera_console_error('validation_error', 'Manifest id is required', 422, [
		'fields' => ['id' => 'required'],
	]);
}
if ($driver === '' || $conductor === '') {
	tessera_console_error('validation_error', 'Driver and conductor are required', 422, [
		'fields' => [
			'driver' => $driver === '' ? 'required' : null,
			'conductor' => $conductor === '' ? 'required' : null,
		],
	]);
}

$sel = mysqli_query($db, "SELECT * FROM manifest WHERE id=$id LIMIT 1");
if (!$sel || !($row = mysqli_fetch_assoc($sel))) {
	tessera_console_error('not_found', 'Manifest not found', 404);
}

$prevBus = (string) ($row['bus'] ?? '');
$tripDate = (string) ($row['trip_date'] ?? '');
$routeDayId = (int) ($row['route_day_id'] ?? 0);
$busToSet = $newBus !== '' ? $newBus : $prevBus;

$driverEsc = mysqli_real_escape_string($db, $driver);
$condEsc = mysqli_real_escape_string($db, $conductor);
$busEsc = mysqli_real_escape_string($db, $busToSet);
$prevEsc = mysqli_real_escape_string($db, $prevBus);
$dateEsc = mysqli_real_escape_string($db, $tripDate);

$db->begin_transaction();
try {
	if ($busToSet !== $prevBus) {
		$clash = mysqli_query(
			$db,
			"SELECT id FROM manifest WHERE bus='$busEsc' AND trip_date='$dateEsc' AND id<>$id LIMIT 1"
		);
		if ($clash && mysqli_num_rows($clash) > 0) {
			$db->rollback();
			tessera_console_error('conflict', 'That vehicle already has a manifest on this date', 409);
		}

		$ok = mysqli_query(
			$db,
			"UPDATE manifest SET bus='$busEsc', driver='$driverEsc', conductor='$condEsc' WHERE id=$id"
		);
		if (!$ok) {
			throw new RuntimeException('manifest');
		}
		if ($routeDayId > 0) {
			mysqli_query($db, "UPDATE route_day SET bus='$busEsc' WHERE id=$routeDayId");
			mysqli_query(
				$db,
				'UPDATE booking SET bus=\'' . $busEsc . '\' WHERE ' . tessera_sql_tenant_eq()
				. " AND route_day_id=$routeDayId AND trip_date='$dateEsc'"
			);
		}
	} else {
		$ok = mysqli_query(
			$db,
			"UPDATE manifest SET driver='$driverEsc', conductor='$condEsc' WHERE id=$id"
		);
		if (!$ok) {
			throw new RuntimeException('manifest');
		}
	}

	if ($prevBus !== '') {
		mysqli_query(
			$db,
			"UPDATE vehicle SET driver='$driverEsc', conductor='$condEsc' WHERE name='$prevEsc' OR name='$busEsc'"
		);
	}

	$db->commit();
} catch (Throwable $e) {
	$db->rollback();
	tessera_console_error('server_error', 'Could not update manifest', 500);
}

$fresh = mysqli_query($db, "SELECT * FROM manifest WHERE id=$id LIMIT 1");
$item = $fresh && ($fr = mysqli_fetch_assoc($fresh)) ? tessera_manifest_row_to_item($fr) : null;

tessera_console_ok([
	'manifest' => $item,
]);
