<?php
require_once __DIR__ . '/_bootstrap.php';
$key = v1_require_key($db, 'tickets:read');
require_once dirname(__DIR__) . '/booking_api.php';
require_once dirname(__DIR__) . '/db.php';

$from = trim($_GET['from'] ?? '');
$to = trim($_GET['to'] ?? '');
$date = trim($_GET['date'] ?? date('Y-m-d'));
if ($from === '' || $to === '') {
	v1_json(['ok' => false, 'error' => 'from_to_required'], 400);
}
if (!preg_match('/^\d{4}-\d{2}-\d{2}$/', $date)) {
	$date = date('Y-m-d');
}
$tenantId = (int) $key['tenant_id'];

try {
	$stmt = $pdo->prepare("
		SELECT id, from_city, to_city, trip_date, departure_time, fare, bus_name, total_seats, route
		FROM mobile_trips
		WHERE tenant_id = :tenant_id
		  AND LOWER(from_city) = LOWER(:from)
		  AND LOWER(to_city) = LOWER(:to)
		  AND trip_date = :date
		ORDER BY departure_time ASC
	");
	$stmt->execute([':tenant_id' => $tenantId, ':from' => $from, ':to' => $to, ':date' => $date]);
	$rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
	$takenStmt = $pdo->prepare(
		"SELECT COUNT(*) FROM mobile_bookings WHERE trip_id = ? AND tenant_id = ? AND status IN ('confirmed','pending')"
	);
	foreach ($rows as &$row) {
		$takenStmt->execute([(int) $row['id'], $tenantId]);
		$row['available_seats'] = (int) $row['total_seats'] - (int) $takenStmt->fetchColumn();
	}
	unset($row);
	v1_json(['ok' => true, 'trips' => $rows]);
} catch (Throwable $e) {
	v1_json(['ok' => false, 'error' => 'server_error'], 500);
}
