<?php
include_once '../../../connection/connect.php';
include_once '../../../auth/session/check_session.php';
include_once '../../../read/general/Read.php';
include_once '../../../Create/general/Create.php';
include_once '../../general/Update.php';
require_once __DIR__ . '/../../../create/records/includes/route_manager/point_times.php';

header('Content-Type: application/json');

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

$point_id = (int) ($_POST['point_id'] ?? 0);
$kind = trim((string) ($_POST['kind'] ?? ''));
$report_time = trim((string) ($_POST['report_time'] ?? ''));
$point_time = trim((string) ($_POST['point_time'] ?? ''));

if (preg_match('/^(\d{2}:\d{2})/', $report_time, $match)) {
    $report_time = $match[1];
}

if (preg_match('/^(\d{2}:\d{2})/', $point_time, $match)) {
    $point_time = $match[1];
}

$point_tables = [
    'departure' => 'route_depature_points',
    'arrival'   => 'route_arrival_points',
];

if ($point_id < 1 || !isset($point_tables[$kind])) {
    http_response_code(422);
    echo json_encode(['success' => false, 'message' => 'The selected point could not be found.']);
    exit;
}

if ($point_time === '' || !preg_match('/^\d{2}:\d{2}$/', $point_time)) {
    http_response_code(422);
    echo json_encode(['success' => false, 'message' => 'Please enter a valid time.']);
    exit;
}

if ($kind === 'departure' && ($report_time === '' || !preg_match('/^\d{2}:\d{2}$/', $report_time))) {
    http_response_code(422);
    echo json_encode(['success' => false, 'message' => 'Please enter a valid report time.']);
    exit;
}

$point = new Read([
    'select' => ['id', 'route_id', 'name', 'company_id'],
    'pdo'    => $pdo,
    'where'  => ['id' => $point_id],
    'table'  => $point_tables[$kind],
    'order'  => '',
    'limit'  => 'LIMIT 1',
]);

if ($point->available_row == 0) {
    http_response_code(404);
    echo json_encode(['success' => false, 'message' => 'The selected point could not be found.']);
    exit;
}

$result = upsert_route_point_time($pdo, $kind, [
    'route_id'    => $point->rows[0]['route_id'],
    'point_id'    => (int) $point->rows[0]['id'],
    'point_name'  => $point->rows[0]['name'],
    'report_time' => $kind === 'departure' ? $report_time : null,
    'point_time'  => $point_time,
    'company_id'  => $point->rows[0]['company_id'],
], $_SESSION['username'] ?? '');

http_response_code($result['status']);
echo json_encode($result['body']);
