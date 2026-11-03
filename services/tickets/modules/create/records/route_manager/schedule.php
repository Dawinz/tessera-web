<?php
include_once '../../../connection/connect.php';
include_once '../../../connection/general_db.php';
include_once '../../../read/general/Read.php';
include_once '../../../Create/general/Create.php';
include_once '../../../auth/session/check_session.php';
require_once __DIR__ . '/../includes/route_manager/schedule_creation.php';

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

$input = collect_schedule_input($_POST);
$errors = validate_schedule_input($input);

if (!empty($errors)) {
    http_response_code(422);
    echo json_encode(['success' => false, 'message' => implode(' ', $errors), 'errors' => $errors]);
    exit;
}

$result = create_schedule($pdo, $pdo_general, $input, $_SESSION['username']);

http_response_code($result['status']);
echo json_encode($result['body']);
