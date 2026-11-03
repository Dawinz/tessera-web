<?php
/**
 * Seed mobile_trips for the next 14 days from curated corridors (+ buses).
 * Run once: php seed_trips.php   OR open in browser.
 */
require_once __DIR__ . '/db.php';

header('Content-Type: application/json');

$corridors = [
    [
        'from' => 'Dar es Salaam', 'to' => 'Nairobi', 'via' => 'Arusha',
        'fare' => 80000, 'dep' => '06:00:00', 'arr' => '18:00:00', 'duration' => '12 hrs',
        'from_terminal' => 'UBUNGO BUS TERMINAL', 'to_terminal' => 'NAIROBI BUS STATION',
        'class' => 'VIP', 'legacy' => 7,
        'board' => [['name' => 'UBUNGO', 'time' => '05:30'], ['name' => 'KIBAHA', 'time' => '06:20']],
        'drop'  => [['name' => 'NAMANGA', 'time' => '15:00'], ['name' => 'NAIROBI BUS STATION', 'time' => '18:00']],
    ],
    [
        'from' => 'Dar es Salaam', 'to' => 'Kampala', 'via' => 'Masaka',
        'fare' => 130000, 'dep' => '07:00:00', 'arr' => '22:00:00', 'duration' => '15 hrs',
        'from_terminal' => 'UBUNGO BUS TERMINAL', 'to_terminal' => 'KAMPALA BUS PARK',
        'class' => 'VIP', 'legacy' => 9,
        'board' => [['name' => 'UBUNGO', 'time' => '06:30']],
        'drop'  => [['name' => 'KAMPALA BUS PARK', 'time' => '22:00']],
    ],
    [
        'from' => 'Dar es Salaam', 'to' => 'Lubumbashi', 'via' => 'Mbeya',
        'fare' => 190000, 'dep' => '05:30:00', 'arr' => '06:00:00', 'duration' => '24 hrs+',
        'from_terminal' => 'UBUNGO BUS TERMINAL', 'to_terminal' => 'LUBUMBASHI TERMINAL',
        'class' => 'VIP', 'legacy' => 3, 'arr_next_day' => 1,
        'board' => [['name' => 'UBUNGO', 'time' => '05:00']],
        'drop'  => [['name' => 'LUBUMBASHI TERMINAL', 'time' => '06:00']],
    ],
    [
        'from' => 'Nairobi', 'to' => 'Dar es Salaam', 'via' => 'Arusha',
        'fare' => 80000, 'dep' => '07:30:00', 'arr' => '19:30:00', 'duration' => '12 hrs',
        'from_terminal' => 'NAIROBI BUS STATION', 'to_terminal' => 'UBUNGO BUS TERMINAL',
        'class' => 'VIP', 'legacy' => 10,
        'board' => [['name' => 'NAIROBI BUS STATION', 'time' => '07:00']],
        'drop'  => [['name' => 'UBUNGO', 'time' => '19:30']],
    ],
    [
        'from' => 'Dar es Salaam', 'to' => 'Arusha', 'via' => 'Chalinze',
        'fare' => 35000, 'dep' => '06:00:00', 'arr' => '14:00:00', 'duration' => '8 hrs',
        'from_terminal' => 'UBUNGO BUS TERMINAL', 'to_terminal' => 'ARUSHA BUS STAND',
        'class' => 'VIP', 'legacy' => null,
        'board' => [
            ['name' => 'UBUNGO', 'time' => '05:30'],
            ['name' => 'KIMARA', 'time' => '05:50'],
            ['name' => 'CHALINZE', 'time' => '07:30'],
        ],
        'drop' => [
            ['name' => 'MOSHI', 'time' => '13:00'],
            ['name' => 'ARUSHA CENTRE', 'time' => '14:00'],
        ],
    ],
    [
        'from' => 'Dar es Salaam', 'to' => 'Arusha', 'via' => 'Chalinze',
        'fare' => 32000, 'dep' => '10:30:00', 'arr' => '18:30:00', 'duration' => '8 hrs',
        'from_terminal' => 'UBUNGO BUS TERMINAL', 'to_terminal' => 'ARUSHA BUS STAND',
        'class' => 'Economy', 'legacy' => null,
        'board' => [
            ['name' => 'UBUNGO', 'time' => '10:00'],
            ['name' => 'CHALINZE', 'time' => '11:40'],
        ],
        'drop' => [
            ['name' => 'ARUSHA CENTRE', 'time' => '18:30'],
        ],
    ],
];

$buses = ['6543AB07', '2354AB07', '3835AB14', '5609AE19', '3190AB14'];
$amenities = json_encode(['wifi', 'ac', 'toilet', 'charging']);

$inserted = 0;
$skipped = 0;

$check = $pdo->prepare("
    SELECT id FROM mobile_trips
    WHERE tenant_id = ? AND from_city = ? AND to_city = ? AND trip_date = ? AND departure_time = ? AND bus_name = ?
    LIMIT 1
");
$insert = $pdo->prepare("
    INSERT INTO mobile_trips
      (tenant_id, bus_name, route, from_city, to_city, via_city, from_terminal, to_terminal,
       departure_time, arrival_time, arr_next_day, duration, fare, seat_class,
       total_seats, driver, conductor, trip_date, amenities, boarding_stops,
       dropping_stops, legacy_route_id)
    VALUES
      (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, 49, ?, ?, ?, ?, ?, ?, ?)
");

$tenantId = 1;
try {
    $tidStmt = $pdo->query("SELECT id FROM tessera_tenants WHERE slug = 'sr-classic' LIMIT 1");
    if ($tidStmt && ($tid = $tidStmt->fetchColumn())) {
        $tenantId = (int) $tid;
    }
} catch (Throwable $e) {
    $tenantId = 1;
}

for ($d = 0; $d < 14; $d++) {
    $date = date('Y-m-d', strtotime("+{$d} day"));
    foreach ($corridors as $i => $c) {
        $bus = $buses[$i % count($buses)];
        $check->execute([$tenantId, $c['from'], $c['to'], $date, $c['dep'], $bus]);
        if ($check->fetch()) {
            $skipped++;
            continue;
        }
        $insert->execute([
            $tenantId,
            $bus,
            $c['from'] . ' to ' . $c['to'],
            $c['from'],
            $c['to'],
            $c['via'],
            $c['from_terminal'],
            $c['to_terminal'],
            $c['dep'],
            $c['arr'],
            $c['arr_next_day'] ?? 0,
            $c['duration'],
            $c['fare'],
            $c['class'],
            'Driver',
            'Conductor',
            $date,
            $amenities,
            json_encode($c['board']),
            json_encode($c['drop']),
            $c['legacy'],
        ]);
        $inserted++;
    }
}

echo json_encode([
    'success'  => true,
    'inserted' => $inserted,
    'skipped'  => $skipped,
]);
