<?php
require_once __DIR__ . '/_bootstrap.php';
$key = v1_require_key($db, 'tickets:read');
require_once dirname(__DIR__) . '/db.php';

$ref = trim($_GET['ref'] ?? '');
$phone = trim($_GET['phone'] ?? '');
if ($ref === '' || $phone === '') {
	v1_json(['ok' => false, 'error' => 'ref_phone_required'], 400);
}
$tenantId = (int) $key['tenant_id'];
$stmt = $pdo->prepare(
	'SELECT booking_ref, trip_id, seat_number, first_name, last_name, phone, status, payment_status, fare, levy, total, channel
	 FROM mobile_bookings WHERE tenant_id = ? AND booking_ref = ? AND phone = ? LIMIT 1'
);
$stmt->execute([$tenantId, $ref, $phone]);
$row = $stmt->fetch(PDO::FETCH_ASSOC);
if (!$row) {
	v1_json(['ok' => false, 'error' => 'not_found'], 404);
}
v1_json(['ok' => true, 'booking' => $row]);
