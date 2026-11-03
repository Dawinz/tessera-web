<?php
/**
 * GET /api/get_booking.php?ref=&phone=
 */
require_once __DIR__ . '/booking_api.php';
booking_api_boot('GET, OPTIONS');
booking_api_require_method('GET');
require_once __DIR__ . '/db.php';

$ref   = strtoupper(trim($_GET['ref'] ?? ''));
$phone = trim($_GET['phone'] ?? '');

if ($ref === '' || $phone === '') {
    booking_api_json([
        'success' => false,
        'found'   => false,
        'error'   => 'ref_phone_required',
        'message' => 'ref and phone are required',
    ], 400);
}

$phoneDigits = booking_api_phone_digits($phone);
$tenantId = booking_api_tenant_id();

try {
    $stmt = $pdo->prepare("
        SELECT b.*, t.route, t.from_city, t.to_city, t.bus_name,
               t.departure_time, t.arrival_time, t.trip_date
        FROM mobile_bookings b
        JOIN mobile_trips t ON t.id = b.trip_id AND t.tenant_id = b.tenant_id
        WHERE b.tenant_id = ?
          AND b.booking_ref = ?
          AND (
            b.phone = ?
            OR REPLACE(REPLACE(REPLACE(b.phone, '+', ''), ' ', ''), '-', '') = ?
          )
        LIMIT 1
    ");
    $stmt->execute([$tenantId, $ref, $phone, $phoneDigits]);
    $row = $stmt->fetch();

    if (!$row) {
        booking_api_json([
            'success' => false,
            'found'   => false,
            'error'   => 'not_found',
            'message' => 'Booking not found',
        ]);
    }

    booking_api_json([
        'success'         => true,
        'found'           => true,
        'booking_ref'     => $row['booking_ref'],
        'seat_number'     => $row['seat_number'],
        'passenger_name'  => $row['first_name'] . ' ' . $row['last_name'],
        'phone'           => $row['phone'],
        'route'           => $row['route'],
        'from'            => $row['from_city'],
        'to'              => $row['to_city'],
        'bus_name'        => $row['bus_name'],
        'trip_date'       => $row['trip_date'],
        'dep_time'        => substr((string) $row['departure_time'], 0, 5),
        'arr_time'        => substr((string) $row['arrival_time'], 0, 5),
        'boarding_stop'   => $row['boarding_stop'],
        'dropping_stop'   => $row['dropping_stop'],
        'payment_method'  => $row['payment_method'],
        'payment_status'  => $row['payment_status'],
        'fare'            => $row['fare'],
        'levy'            => $row['levy'],
        'total'           => $row['total'],
        'status'          => $row['status'],
        'channel'         => $row['channel'],
        'agent_username'  => $row['agent_username'],
        'booked_at'       => $row['booked_at'],
    ]);
} catch (Exception $e) {
    booking_api_json([
        'success' => false,
        'found'   => false,
        'error'   => 'server_error',
        'message' => 'Server error',
    ], 500);
}
