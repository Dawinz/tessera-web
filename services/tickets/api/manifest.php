<?php
require_once __DIR__ . '/mobile_cargo_lib.php';
$cargo = tessera_customer_cargo_boot();

$code = strtoupper(trim((string) ($_POST['code_no'] ?? '')));
if ($code === '') {
    http_response_code(400);
    echo json_encode(['success' => false, 'error' => 'code_required', 'arraival_response' => []]);
    exit;
}

$row = tessera_customer_cargo_by_code($cargo, $code);
if (!$row) {
    echo json_encode(['success' => false, 'found' => false, 'arraival_response' => []]);
    exit;
}

$paid = (float) ($row['paid'] ?? 0);
$toBe = (float) ($row['to_be'] ?? 0);
echo json_encode([
    'success' => true,
    'arraival_response' => [[
        'date' => (string) (($row['trip_date'] ?? '') !== '' ? $row['trip_date'] : ($row['date'] ?? '')),
        'arraival' => (string) ($row['account'] ?? ''),
        'status' => (string) ($row['status'] ?? ''),
        'q_left' => (string) (($row['question'] ?? '') !== '' ? $row['question'] : '1'),
        'amount' => (string) ($paid + $toBe),
        'vehicle' => (string) ($row['bus'] ?? ''),
    ]],
]);
