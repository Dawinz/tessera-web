<?php
require_once __DIR__ . '/mobile_cargo_lib.php';
$cargo = tessera_customer_cargo_boot();

$code = strtoupper(trim((string) ($_POST['code_no'] ?? '')));
$phone = trim((string) ($_POST['phone_no'] ?? ''));
if ($code === '' || $phone === '') {
    http_response_code(400);
    echo json_encode(['success' => false, 'error' => 'phone_code_required', 'luggageData' => null]);
    exit;
}

$row = tessera_customer_cargo_by_code($cargo, $code);
if (!$row || !tessera_customer_cargo_phone_matches($row, $phone)) {
    echo json_encode(['success' => false, 'found' => false, 'luggageData' => null]);
    exit;
}

echo json_encode([
    'success' => true,
    'found' => true,
    'luggageData' => tessera_customer_cargo_luggage($row),
]);
