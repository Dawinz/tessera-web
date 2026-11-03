<?php
require_once __DIR__ . '/mobile_cargo_lib.php';
$cargo = tessera_customer_cargo_boot();

$phone = trim((string) ($_POST['phone_no'] ?? ''));
$tail = tessera_customer_cargo_phone_tail($phone);
if (strlen($tail) < 9) {
    http_response_code(400);
    echo json_encode(['success' => false, 'error' => 'phone_required', 'cargo_list' => []]);
    exit;
}

$tailE = $cargo->real_escape_string($tail);
$tenant = tessera_customer_cargo_tenant_sql($cargo);
$q = $cargo->query(
    "SELECT code, s_name, r_name, s_no, r_no, account, status, date, paid, to_be, bus, trip_date, question
     FROM parcel_info
     WHERE (RIGHT(REPLACE(REPLACE(REPLACE(s_no,'+',''),' ',''),'-',''), 9)='{$tailE}'
         OR RIGHT(REPLACE(REPLACE(REPLACE(r_no,'+',''),' ',''),'-',''), 9)='{$tailE}')
       {$tenant}
     ORDER BY date DESC
     LIMIT 50"
);
$list = [];
if ($q) {
    while ($row = $q->fetch_assoc()) {
        $list[] = tessera_customer_cargo_luggage($row);
    }
}

echo json_encode(['success' => true, 'cargo_list' => $list]);
