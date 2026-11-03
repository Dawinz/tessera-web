<?php
/**
 * GET /api/trips.php?from=&to=&date=YYYY-MM-DD
 */
require_once __DIR__ . '/booking_api.php';
booking_api_boot('GET, OPTIONS');
booking_api_require_method('GET');
require_once __DIR__ . '/db.php';

$from = trim($_GET['from'] ?? '');
$to   = trim($_GET['to'] ?? '');
$date = trim($_GET['date'] ?? date('Y-m-d'));

if ($from === '' || $to === '') {
    booking_api_json([
        'success' => false,
        'trips'   => [],
        'error'   => 'from_to_required',
        'message' => 'from and to are required',
    ], 400);
}

if (!preg_match('/^\d{4}-\d{2}-\d{2}$/', $date)) {
    $date = date('Y-m-d');
}

try {
    $tenantId = booking_api_tenant_id();
    $stmt = $pdo->prepare("
        SELECT * FROM mobile_trips
        WHERE tenant_id = :tenant_id
          AND LOWER(from_city) = LOWER(:from)
          AND LOWER(to_city)   = LOWER(:to)
          AND trip_date        = :date
        ORDER BY departure_time ASC
    ");
    $stmt->execute([
        ':tenant_id' => $tenantId,
        ':from' => $from,
        ':to' => $to,
        ':date' => $date,
    ]);
    $rows = $stmt->fetchAll();

    $trips = [];
    foreach ($rows as $row) {
        $seatStmt = $pdo->prepare(
            "SELECT seat_number FROM mobile_bookings
             WHERE trip_id = ? AND tenant_id = ? AND status IN ('confirmed','pending')"
        );
        $seatStmt->execute([$row['id'], $tenantId]);
        $takenSeats = array_column($seatStmt->fetchAll(), 'seat_number');

        $trips[] = [
            'id'              => (string) $row['id'],
            'bus_name'        => $row['bus_name'],
            'route'           => $row['route'],
            'from'            => $row['from_city'],
            'to'              => $row['to_city'],
            'via'             => $row['via_city'],
            'from_terminal'   => $row['from_terminal'],
            'to_terminal'     => $row['to_terminal'],
            'dep_time'        => substr((string) $row['departure_time'], 0, 5),
            'arr_time'        => substr((string) $row['arrival_time'], 0, 5),
            'arr_next_day'    => (bool) $row['arr_next_day'],
            'duration'        => $row['duration'],
            'fare'            => (string) $row['fare'],
            'seat_class'      => $row['seat_class'],
            'total_seats'     => (int) $row['total_seats'],
            'available_seats' => (int) $row['total_seats'] - count($takenSeats),
            'driver'          => $row['driver'],
            'conductor'       => $row['conductor'],
            'trip_date'       => $row['trip_date'],
            'amenities'       => json_decode($row['amenities'] ?? '[]', true) ?: [],
            'boarding_stops'  => json_decode($row['boarding_stops'] ?? '[]', true) ?: [],
            'dropping_stops'  => json_decode($row['dropping_stops'] ?? '[]', true) ?: [],
            'taken_seats'     => $takenSeats,
        ];
    }

    booking_api_json([
        'success' => true,
        'tenant_id' => $tenantId,
        'trips'   => $trips,
    ]);
} catch (Exception $e) {
    booking_api_json([
        'success' => false,
        'trips'   => [],
        'error'   => 'server_error',
        'message' => 'Server error',
    ], 500);
}
