<?php
include_once '../../connection/connect.php';
include_once '../../connection/general_db.php';
include_once '../../read/general/Read.php';
include_once '../../Create/general/Create.php';
include_once '../../auth/session/check_session.php';
require_once __DIR__ . '/includes/route_manager/route_creation.php';
require_once __DIR__ . '/includes/route_manager/departure_points.php';
require_once __DIR__ . '/includes/route_manager/arrival_points.php';
require_once __DIR__ . '/includes/route_manager/ticket_prices.php';
require_once __DIR__ . '/includes/route_manager/stops.php';
require_once __DIR__ . '/includes/route_manager/trip_requirements.php';

header('Content-Type: application/json');

ini_set('display_errors', '1');
error_reporting(E_ALL);
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['success' => false, 'message' => 'Method not allowed.']);
    exit;
}

if (!empty($session_check) && $session_check === 'expired-session') {
    http_response_code(401);
    echo json_encode([
        'success' => false,
        'logout' => true,
        'message' => 'Your session has expired. Please log in again.'
    ]);
    exit;
}

// Basic Info step
$input = collect_route_input($_POST);
// Departure Points step
$departure_points = collect_departure_points($_POST);
// Arrival Point step
$arrival_points = collect_arrival_points($_POST);
// Ticket Price step
$ticket_prices = collect_ticket_prices($_POST);
// Stops step
$stops = collect_stops($_POST);
// Requirements step
$trip_requirements = collect_trip_requirements($_POST);

$errors = array_merge(
    validate_route_input($input),
    validate_departure_points($departure_points),
    validate_arrival_points($arrival_points),
    validate_ticket_prices($ticket_prices),
    validate_stops($stops)
);

if (!empty($errors)) {
    http_response_code(422);
    echo json_encode(['success' => false, 'message' => implode(' ', $errors), 'errors' => $errors]);
    exit;
}

$result = create_route($pdo, $input, $_SESSION['username']);

if (empty($result['body']['success'])) {
    http_response_code($result['status']);
    echo json_encode($result['body']);
    exit;
}

$route_id = $result['body']['route_id'];

$departure_points_result = create_departure_points($pdo, $route_id, $departure_points, ROUTE_COMPANY_ID);

if (empty($departure_points_result['body']['success'])) {
    http_response_code($departure_points_result['status']);
    echo json_encode($departure_points_result['body']);
    exit;
}

$arrival_points_result = create_arrival_points($pdo, $route_id, $arrival_points, ROUTE_COMPANY_ID);

if (empty($arrival_points_result['body']['success'])) {
    http_response_code($arrival_points_result['status']);
    echo json_encode($arrival_points_result['body']);
    exit;
}

$ticket_prices_result = create_ticket_prices($pdo, $pdo_general, $route_id, $ticket_prices, ROUTE_COMPANY_ID);

if (empty($ticket_prices_result['body']['success'])) {
    http_response_code($ticket_prices_result['status']);
    echo json_encode($ticket_prices_result['body']);
    exit;
}

$stops_result = create_stops($pdo, $route_id, $stops, ROUTE_COMPANY_ID);

if (empty($stops_result['body']['success'])) {
    http_response_code($stops_result['status']);
    echo json_encode($stops_result['body']);
    exit;
}

http_response_code($result['status']);
echo json_encode($result['body']);
