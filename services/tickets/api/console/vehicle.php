<?php
/**
 * GET /tickets/api/console/vehicle.php?id= — vehicle detail + layout (T-P0-05).
 */
declare(strict_types=1);

require_once dirname(__DIR__, 2) . '/includes/tessera_console_api.php';
require_once dirname(__DIR__, 2) . '/includes/tessera_rbac.php';
require_once dirname(__DIR__, 2) . '/includes/tessera_tenant.php';
require_once dirname(__DIR__, 2) . '/includes/vehicle_seat_layout.php';

tessera_console_boot('GET, OPTIONS');
tessera_console_require_method('GET');
$username = tessera_console_require_session();

require_once dirname(__DIR__, 2) . '/connect.php';
if (!isset($db) || !($db instanceof mysqli) || $db->connect_error) {
	tessera_console_error('db_unavailable', 'Database unavailable', 503);
}
tessera_console_require_capability($db, $username, 'tickets');

$id = (int) ($_GET['id'] ?? 0);
$tenantId = (int) tessera_current_tenant_id();
if ($id < 1 || $tenantId < 1) {
	tessera_console_error('validation_error', 'Vehicle id is required', 422);
}

$cols = 'id, name, driver, conductor, seats, detail, status, state, dedicate_seat_price';
if (tessera_vehicle_has_layout_column($db)) {
	$cols .= ', seat_layout_json';
}
$stmt = $db->prepare("SELECT $cols FROM vehicle WHERE id = ? AND tenant_id = ? LIMIT 1");
$stmt->bind_param('ii', $id, $tenantId);
$stmt->execute();
$result = $stmt->get_result();
$row = $result ? $result->fetch_assoc() : null;
$stmt->close();
if (!$row) {
	tessera_console_error('not_found', 'Vehicle not found', 404);
}

$patterns = tessera_vehicle_row_patterns($row);
$extras = tessera_vehicle_layout_extras($row);
$grid = tessera_patterns_to_grid($patterns);
$labels = tessera_layout_labels_for_vehicle($patterns, $extras);
$layoutSeats = tessera_count_seats_in_patterns($patterns, $extras);

$nameEsc = mysqli_real_escape_string($db, (string) $row['name']);
$assignments = ['templates' => 0, 'manifests' => 0, 'bookings' => 0];
$tq = mysqli_query($db, "SELECT COUNT(*) AS c FROM route_day WHERE bus='$nameEsc' AND " . tessera_sql_tenant_eq());
if ($tq && ($tr = mysqli_fetch_assoc($tq))) {
	$assignments['templates'] = (int) $tr['c'];
}
$mq = mysqli_query($db, "SELECT COUNT(*) AS c FROM manifest WHERE bus='$nameEsc'");
if ($mq && ($mr = mysqli_fetch_assoc($mq))) {
	$assignments['manifests'] = (int) $mr['c'];
}
$bq = mysqli_query(
	$db,
	"SELECT COUNT(*) AS c FROM booking WHERE bus='$nameEsc' AND (delete_book='' OR delete_book IS NULL) AND " . tessera_sql_tenant_eq()
);
if ($bq && ($br = mysqli_fetch_assoc($bq))) {
	$assignments['bookings'] = (int) $br['c'];
}
/* Classic delete blocks on any booking row for this bus (including soft-deleted). */
$canDelete = true;
$anyBook = mysqli_query(
	$db,
	"SELECT id FROM booking WHERE bus='$nameEsc' AND " . tessera_sql_tenant_eq() . ' LIMIT 1'
);
if ($anyBook && mysqli_num_rows($anyBook) > 0) {
	$canDelete = false;
}

$detailRaw = (string) ($row['detail'] ?? '');
$detail = $detailRaw !== '' ? (string) (base64_decode($detailRaw, true) ?: $detailRaw) : '';

tessera_console_ok([
	'vehicle' => [
		'id' => (int) $row['id'],
		'name' => (string) $row['name'],
		'driver' => (string) $row['driver'],
		'conductor' => (string) $row['conductor'],
		'seats' => (int) $row['seats'],
		'detail' => $detail,
		'status' => (string) $row['status'],
		'state' => (string) ($row['state'] ?? 'normal'),
		'dedicate_seat_price' => (string) ($row['dedicate_seat_price'] ?? ''),
		'has_custom_layout' => !empty($row['seat_layout_json']),
	],
	'layout' => [
		'patterns' => $patterns,
		'extras' => $extras,
		'grid' => $grid,
		'labels' => $labels,
		'layout_seats' => $layoutSeats,
		'layout_ok' => $layoutSeats === (int) $row['seats'],
		'templates' => [37, 41, 45, 47, 49, 53, 57, 59],
	],
	'assignments' => $assignments,
	'can_delete' => $canDelete,
]);
