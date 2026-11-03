<?php

/**
 * Route details for the "view" button on route_manager.php.
 *
 * Returns one route together with everything the wizard stored alongside it:
 * departure points, arrival points, ticket prices, stops and trip
 * requirements. Only plain values are sent back; the panel markup is built by
 * js/filter/read/route_manager/route_details.js.
 */

include_once '../../../connection/connect.php';
include_once '../../../read/general/Read.php';
include_once '../../../auth/session/check_session.php';

header('Content-Type: application/json');

if (!empty($session_check) && $session_check === 'expired-session') {
    http_response_code(401);
    echo json_encode([
        'success' => false,
        'logout'  => true,
        'message' => 'Your session has expired. Please log in again.',
    ]);
    exit;
}

/**
 * Child rows that belong to a route, in the order they were stored.
 *
 * @param string $table  One of the route_* child tables.
 * @param array  $select Columns to read.
 *
 * @return array
 */
function read_route_children(PDO $pdo, $table, array $select, $route_id)
{
    $children = new Read([
        'select' => $select,
        'pdo'    => $pdo,
        'where'  => ['route_id' => $route_id],
        'table'  => $table,
        'order'  => 'ORDER BY id ASC',
        'limit'  => '',
    ]);

    return $children->rows;
}

// The wizard identifies a route by its route_id ("DL1SRC"), not by the primary key.
$route_id = trim((string) ($_POST['route_id'] ?? $_GET['route_id'] ?? ''));

if ($route_id === '') {
    http_response_code(400);
    echo json_encode(['success' => false, 'message' => 'No route was requested.']);
    exit;
}

$route = new Read([
    'select' => [
        'id',
        'route_id',
        'route_name',
        'route_acronym_name',
        'route_initial_letters',
        'start_route',
        'end_route',
        'journey_duration_value',
        'journey_duration_unit',
        'distance',
        'map_coordinates',
        'detail',
        'state',
        'uploader',
    ],
    'pdo'    => $pdo,
    'where'  => ['route_id' => $route_id],
    'table'  => 'routes',
    'order'  => '',
    'limit'  => 'LIMIT 1',
]);

if ($route->available_row == 0) {
    http_response_code(404);
    echo json_encode(['success' => false, 'message' => 'This route could not be found.']);
    exit;
}

$row = $route->rows[0];

$departure_points = array_column(
    read_route_children($pdo, 'route_depature_points', ['name'], $route_id),
    'name'
);

$arrival_points = array_column(
    read_route_children($pdo, 'route_arrival_points', ['name'], $route_id),
    'name'
);

$requirements = array_column(
    read_route_children($pdo, 'Trip_requirment', ['name'], $route_id),
    'name'
);

$ticket_prices = array_map(
    function (array $price) {
        return [
            'class'    => $price['class'],
            'amount'   => (float) $price['amount'],
            'currency' => $price['currency'],
        ];
    },
    read_route_children($pdo, 'route_fair', ['class', 'amount', 'currency'], $route_id)
);

// route_stops only keeps the stop id, so the readable name, category and
// location have to come from the `stops` table.
$stops_statement = $pdo->prepare(
    'SELECT s.name, s.category, s.location, rs.duration'
    . ' FROM route_stops rs'
    . ' INNER JOIN stops s ON s.id = rs.stop_id'
    . ' WHERE rs.route_id = :route_id'
    . ' ORDER BY rs.id ASC'
);
$stops_statement->execute(['route_id' => $route_id]);
$stops = $stops_statement->fetchAll(PDO::FETCH_ASSOC);

echo json_encode([
    'success'          => true,
    'route'            => [
        'id'                     => (int) $row['id'],
        'route_id'               => $row['route_id'],
        'route_name'             => $row['route_name'],
        'route_acronym_name'     => $row['route_acronym_name'],
        'route_initial_letters'  => $row['route_initial_letters'],
        'start_route'            => $row['start_route'],
        'end_route'              => $row['end_route'],
        'journey_duration_value' => (float) $row['journey_duration_value'],
        'journey_duration_unit'  => $row['journey_duration_unit'],
        'distance'               => (float) $row['distance'],
        'map_coordinates'        => $row['map_coordinates'],
        'description'            => base64_decode($row['detail']),
        'state'                  => (int) $row['state'],
        'uploader'               => $row['uploader'],
    ],
    'departure_points' => $departure_points,
    'arrival_points'   => $arrival_points,
    'ticket_prices'    => $ticket_prices,
    'stops'            => $stops,
    'requirements'     => $requirements,
]);
