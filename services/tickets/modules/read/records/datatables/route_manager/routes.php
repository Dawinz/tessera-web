<?php

/**
 * Server side data source for the routes DataTable on route_manager.php.
 *
 * Answers a DataTables 1.13 request (draw/start/length/order/search) with one
 * page of the `routes` table. Only plain column values are sent back; the cell
 * markup is built by js/filter/read/route_manager/routes.js.
 */

include_once '../../../../connection/connect.php';
include_once '../../../../connection/general_db.php';
include_once '../../../../read/general/Read.php';
include_once '../../../../auth/session/check_session.php';

header('Content-Type: application/json');

$draw = isset($_POST['draw']) ? (int) $_POST['draw'] : 0;

// A 401 here would drop DataTables into its own error handling and leave the
// table stuck on "Processing", so an expired session is reported as a normal
// but empty response that routes.js can react to.
if (!empty($session_check) && $session_check === 'expired-session') {
    echo json_encode([
        'draw'            => $draw,
        'recordsTotal'    => 0,
        'recordsFiltered' => 0,
        'data'            => [],
        'logout'          => true,
        'message'         => 'Your session has expired. Please log in again.',
    ]);
    exit;
}

// Columns the search box looks through.
$searchable_columns = [
    'route_id',
    'route_name',
    'route_acronym_name',
    'start_route',
    'end_route',
];

// Columns the browser may order by. Anything else falls back to `id` so the
// order clause can never be built out of user input.
$orderable_columns = [
    'route_id',
    'route_name',
    'start_route',
    'end_route',
    'journey_duration_value',
    'distance',
    'state',
];

$start = isset($_POST['start']) ? max((int) $_POST['start'], 0) : 0;
// -1 is what DataTables sends for the "All" page length.
$length = isset($_POST['length']) ? (int) $_POST['length'] : 25;
$search_value = isset($_POST['search']['value']) ? trim($_POST['search']['value']) : '';

$order_column = 'id';
$order_direction = 'DESC';

if (isset($_POST['order'][0]['column'])) {
    $ordered_index = (int) $_POST['order'][0]['column'];
    $ordered_name = $_POST['columns'][$ordered_index]['data'] ?? '';

    if (in_array($ordered_name, $orderable_columns, true)) {
        $order_column = $ordered_name;
    }

    $requested_direction = strtolower($_POST['order'][0]['dir'] ?? '');
    $order_direction = $requested_direction === 'asc' ? 'ASC' : 'DESC';
}

// Each searchable column gets its own placeholder so the query keeps working
// even when PDO prepare emulation is turned off.
$search_where = '';
$search_params = [];

if ($search_value !== '') {
    $search_conditions = [];

    foreach ($searchable_columns as $index => $column) {
        $search_conditions[] = "$column LIKE :search$index";
        $search_params["search$index"] = '%' . $search_value . '%';
    }

    $search_where = ' AND (' . implode(' OR ', $search_conditions) . ')';
}

$total = new Read([
    'select' => ['COUNT(*) AS total'],
    'pdo'    => $pdo,
    'where'  => [],
    'table'  => 'routes',
    'order'  => '',
    'limit'  => '',
]);
$total_records = (int) $total->rows[0]['total'];

$filtered = $pdo->prepare('SELECT COUNT(*) AS total FROM routes WHERE 1' . $search_where);
$filtered->execute($search_params);
$filtered_records = (int) $filtered->fetch(PDO::FETCH_ASSOC)['total'];

$sql = 'SELECT id, route_id, route_name, route_acronym_name, start_route, end_route,'
    . ' journey_duration_value, journey_duration_unit, distance, state'
    . ' FROM routes WHERE 1' . $search_where
    . ' ORDER BY ' . $order_column . ' ' . $order_direction;

if ($length >= 0) {
    $sql .= ' LIMIT :start, :length';
}

$statement = $pdo->prepare($sql);

foreach ($search_params as $placeholder => $value) {
    $statement->bindValue(':' . $placeholder, $value, PDO::PARAM_STR);
}

if ($length >= 0) {
    $statement->bindValue(':start', $start, PDO::PARAM_INT);
    $statement->bindValue(':length', $length, PDO::PARAM_INT);
}

$statement->execute();
$routes = $statement->fetchAll(PDO::FETCH_ASSOC);

$data = [];

foreach ($routes as $index => $route) {
    $data[] = [
        // Position within the current page, used for the leading "#" column.
        'row_number'             => $start + $index + 1,
        'id'                     => (int) $route['id'],
        'route_id'               => $route['route_id'],
        'route_name'             => $route['route_name'],
        'route_acronym_name'     => $route['route_acronym_name'],
        'start_route'            => $route['start_route'],
        'end_route'              => $route['end_route'],
        'journey_duration_value' => (float) $route['journey_duration_value'],
        'journey_duration_unit'  => $route['journey_duration_unit'],
        'distance'               => (float) $route['distance'],
        'state'                  => (int) $route['state'],
    ];
}

echo json_encode([
    'draw'            => $draw,
    'recordsTotal'    => $total_records,
    'recordsFiltered' => $filtered_records,
    'data'            => $data,
]);
