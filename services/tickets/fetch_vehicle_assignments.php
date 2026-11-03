<?php
require_once __DIR__ . '/includes/tessera_staff_boot.php';
include 'connect.php';

header('Content-Type: application/json; charset=utf-8');

$id = (int) ($_GET['vehicle_id'] ?? 0);
$tenantId = (int) tessera_current_tenant_id();
if ($id < 1 || $tenantId < 1) {
	http_response_code(400);
	echo json_encode(['error' => 'invalid']);
	exit;
}

$stmt = $db->prepare('SELECT name FROM vehicle WHERE id = ? AND tenant_id = ? LIMIT 1');
$stmt->bind_param('ii', $id, $tenantId);
$stmt->execute();
$res = $stmt->get_result();
$row = $res ? $res->fetch_assoc() : null;
$stmt->close();

if (!$row) {
	http_response_code(404);
	echo json_encode(['error' => 'not_found']);
	exit;
}

$bus = mysqli_real_escape_string($db, (string) $row['name']);
$tenantWhere = tessera_sql_tenant_eq();

$templates = [];
$tq = mysqli_query(
	$db,
	"SELECT rd.id, rd.day, rd.departure, rd.arrival, rd.status, r.fullname AS route_name
	 FROM route_day rd
	 LEFT JOIN routes r ON r.id = rd.route_id
	 WHERE rd.buss = '$bus' AND $tenantWhere
	 ORDER BY rd.day ASC, r.fullname ASC
	 LIMIT 100"
);
while ($tq && ($t = mysqli_fetch_assoc($tq))) {
	$templates[] = [
		'id' => (int) $t['id'],
		'day' => (string) $t['day'],
		'departure' => (string) $t['departure'],
		'arrival' => (string) $t['arrival'],
		'status' => (string) $t['status'],
		'route_name' => (string) ($t['route_name'] ?? ''),
		'manage_url' => 'register_trip.php',
	];
}

$manifests = [];
$mq = mysqli_query(
	$db,
	"SELECT id, trip_date, route_name, driver, conductor
	 FROM manifest
	 WHERE buss = '$bus' AND $tenantWhere
	 ORDER BY trip_date DESC
	 LIMIT 30"
);
while ($mq && ($m = mysqli_fetch_assoc($mq))) {
	$manifests[] = [
		'id' => (int) $m['id'],
		'trip_date' => (string) $m['trip_date'],
		'route_name' => (string) ($m['route_name'] ?? ''),
		'driver' => (string) ($m['driver'] ?? ''),
		'conductor' => (string) ($m['conductor'] ?? ''),
		'url' => 'manifest.php',
	];
}

echo json_encode([
	'templates' => $templates,
	'manifests' => $manifests,
], JSON_UNESCAPED_UNICODE);
