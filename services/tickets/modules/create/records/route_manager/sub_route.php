<?php
include_once '../../../connection/connect.php';
include_once '../../../connection/general_db.php';
include_once '../../../read/general/Read.php';
include_once '../../../Create/general/Create.php';
include_once '../../../auth/session/check_session.php';
require_once __DIR__ . '/../includes/route_manager/sub-route/sub_route_creation.php';
require_once __DIR__ . '/../includes/route_manager/sub-route/departure_points.php';
require_once __DIR__ . '/../includes/route_manager/sub-route/arrival_points.php';
require_once __DIR__ . '/../includes/route_manager/sub-route/ticket_prices.php';
require_once __DIR__ . '/../includes/route_manager/sub-route/stops.php';
require_once __DIR__ . '/../includes/route_manager/sub-route/trip_requirements.php';

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
        'logout'  => true,
        'message' => 'Your session has expired. Please log in again.',
    ]);
    exit;
}

// Basic Info step
$input = collect_sub_route_input($_POST);
// Departure Points step
$departure_points = collect_sub_departure_points($_POST);
// Arrival Point step
$arrival_points = collect_sub_arrival_points($_POST);
// Ticket Price step
$ticket_prices = collect_sub_ticket_prices($_POST);
// Stops step
$stops = collect_sub_stops($_POST);
// Requirements step
$trip_requirements = collect_sub_trip_requirements($_POST);

$errors = array_merge(
    validate_sub_route_input($input),
    validate_sub_departure_points($departure_points),
    validate_sub_arrival_points($arrival_points),
    validate_sub_ticket_prices($ticket_prices),
    validate_sub_stops($stops)
);

if (!empty($errors)) {
    http_response_code(422);
    echo json_encode(['success' => false, 'message' => implode(' ', $errors), 'errors' => $errors]);
    exit;
}

$result = create_sub_route($pdo, $input, $_SESSION['username']);

if (empty($result['body']['success'])) {
    http_response_code($result['status']);
    echo json_encode($result['body']);
    exit;
}

$route_id = $result['body']['route_id'];

$departure_points_result = create_sub_departure_points($pdo, $route_id, $departure_points, ROUTE_COMPANY_ID);

if (empty($departure_points_result['body']['success'])) {
    http_response_code($departure_points_result['status']);
    echo json_encode($departure_points_result['body']);
    exit;
}

$arrival_points_result = create_sub_arrival_points($pdo, $route_id, $arrival_points, ROUTE_COMPANY_ID);

if (empty($arrival_points_result['body']['success'])) {
    http_response_code($arrival_points_result['status']);
    echo json_encode($arrival_points_result['body']);
    exit;
}

$ticket_prices_result = create_sub_ticket_prices($pdo, $pdo_general, $route_id, $ticket_prices, ROUTE_COMPANY_ID);

if (empty($ticket_prices_result['body']['success'])) {
    http_response_code($ticket_prices_result['status']);
    echo json_encode($ticket_prices_result['body']);
    exit;
}

$stops_result = create_sub_stops($pdo, $route_id, $stops, ROUTE_COMPANY_ID);

if (empty($stops_result['body']['success'])) {
    http_response_code($stops_result['status']);
    echo json_encode($stops_result['body']);
    exit;
}

$trip_requirements_result = create_sub_trip_requirements($pdo, $route_id, $trip_requirements);

if (empty($trip_requirements_result['body']['success'])) {
    http_response_code($trip_requirements_result['status']);
    echo json_encode($trip_requirements_result['body']);
    exit;
}

http_response_code($result['status']);
echo json_encode($result['body']);
