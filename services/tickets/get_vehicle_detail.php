<?php
require_once __DIR__ . '/includes/tessera_staff_boot.php';
require_once __DIR__ . '/includes/vehicle_seat_layout.php';
include 'connect.php';

header('Content-Type: application/json; charset=utf-8');

$id = (int) ($_GET['id'] ?? 0);
$tenantId = (int) tessera_current_tenant_id();
if ($id < 1 || $tenantId < 1) {
	http_response_code(400);
	echo json_encode(['error' => 'invalid']);
	exit;
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
	http_response_code(404);
	echo json_encode(['error' => 'not_found']);
	exit;
}

$patterns = tessera_vehicle_row_patterns($row);
$extras = tessera_vehicle_layout_extras($row);
$grid = tessera_patterns_to_grid($patterns);
$labels = tessera_layout_labels_for_vehicle($patterns, $extras);
$layoutSeats = tessera_count_seats_in_patterns($patterns, $extras);

$assignments = ['templates' => 0, 'manifests' => 0, 'bookings' => 0];
$nameEsc = mysqli_real_escape_string($db, (string) $row['name']);
$tq = mysqli_query($db, "SELECT COUNT(*) AS c FROM route_day WHERE buss='$nameEsc' AND " . tessera_sql_tenant_eq());
if ($tq && ($tr = mysqli_fetch_assoc($tq))) {
	$assignments['templates'] = (int) $tr['c'];
}
$mq = mysqli_query($db, "SELECT COUNT(*) AS c FROM manifest WHERE buss='$nameEsc' AND " . tessera_sql_tenant_eq());
if ($mq && ($mr = mysqli_fetch_assoc($mq))) {
	$assignments['manifests'] = (int) $mr['c'];
}
$bq = mysqli_query($db, "SELECT COUNT(*) AS c FROM booking WHERE bus='$nameEsc' AND delete_book='' AND " . tessera_sql_tenant_eq());
if ($bq && ($br = mysqli_fetch_assoc($bq))) {
	$assignments['bookings'] = (int) $br['c'];
}

echo json_encode([
	'vehicle' => [
		'id' => (int) $row['id'],
		'name' => (string) $row['name'],
		'driver' => (string) $row['driver'],
		'conductor' => (string) $row['conductor'],
		'seats' => (int) $row['seats'],
		'detail' => base64_decode((string) $row['detail']),
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
	],
	'assignments' => $assignments,
], JSON_UNESCAPED_UNICODE);
