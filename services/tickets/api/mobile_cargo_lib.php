<?php
/**
 * Customer-app cargo reads the same parcel rows the staff cargo desk uses.
 */

function tessera_customer_cargo_db(): ?mysqli
{
    require_once dirname(__DIR__) . '/includes/sr_env.php';
    $name = getenv('SR_CARGO_DB') ?: 'cargo';
    $cargo = function_exists('sr_mysqli') ? sr_mysqli($name) : null;
    if ($cargo && !$cargo->connect_error) {
        return $cargo;
    }
    return null;
}

function tessera_customer_cargo_boot(): mysqli
{
    header('Content-Type: application/json; charset=utf-8');
    header('Access-Control-Allow-Origin: *');
    header('Access-Control-Allow-Headers: Content-Type');
    header('Access-Control-Allow-Methods: POST, OPTIONS');
    if (($_SERVER['REQUEST_METHOD'] ?? '') === 'OPTIONS') {
        http_response_code(204);
        exit;
    }
    $cargo = tessera_customer_cargo_db();
    if (!$cargo) {
        http_response_code(503);
        echo json_encode(['success' => false, 'error' => 'cargo_db']);
        exit;
    }
    return $cargo;
}

function tessera_customer_cargo_phone_tail(string $phone): string
{
    $digits = preg_replace('/\D+/', '', $phone);
    return substr($digits, -9);
}

function tessera_customer_cargo_tenant_sql(mysqli $cargo): string
{
    $col = @$cargo->query("SHOW COLUMNS FROM parcel_info LIKE 'tenant_id'");
    if (!$col || $col->num_rows < 1) {
        return '';
    }
    require_once dirname(__DIR__) . '/includes/tessera_tenant.php';
    $tid = function_exists('tessera_current_tenant_id') ? (int) tessera_current_tenant_id() : 0;
    return $tid > 0 ? ' AND tenant_id=' . $tid : '';
}

function tessera_customer_cargo_luggage(array $row): array
{
    $paid = (float) ($row['paid'] ?? 0);
    $toBe = (float) ($row['to_be'] ?? 0);
    return [
        'code' => (string) ($row['code'] ?? ''),
        'date_insert' => (string) (($row['date'] ?? '') !== '' ? $row['date'] : ($row['trip_date'] ?? '')),
        'account' => (string) ($row['account'] ?? ''),
        's_name' => (string) ($row['s_name'] ?? ''),
        'r_name' => (string) ($row['r_name'] ?? ''),
        's_no' => (string) ($row['s_no'] ?? ''),
        'r_no' => (string) ($row['r_no'] ?? ''),
        'status' => (string) ($row['status'] ?? ''),
        'payed' => (string) ($row['paid'] ?? ''),
        'to_be' => (string) ($row['to_be'] ?? ''),
        'amount' => (string) ($paid + $toBe),
        'symbol' => '/=',
        'question' => (string) (($row['question'] ?? '') !== '' ? $row['question'] : '1'),
        'store' => (string) ($row['status'] ?? ''),
    ];
}

function tessera_customer_cargo_by_code(mysqli $cargo, string $code): ?array
{
    $code = strtoupper(trim($code));
    if ($code === '') {
        return null;
    }
    $codeE = $cargo->real_escape_string($code);
    $tenant = tessera_customer_cargo_tenant_sql($cargo);
    $q = $cargo->query(
        "SELECT code, s_name, r_name, s_no, r_no, account, status, date, paid, to_be, bus, trip_date, question
         FROM parcel_info WHERE code='{$codeE}'{$tenant} LIMIT 1"
    );
    if (!$q) {
        return null;
    }
    $row = $q->fetch_assoc();
    return $row ?: null;
}

function tessera_customer_cargo_phone_matches(array $row, string $phone): bool
{
    $tail = tessera_customer_cargo_phone_tail($phone);
    if (strlen($tail) < 9) {
        return false;
    }
    return tessera_customer_cargo_phone_tail((string) ($row['s_no'] ?? '')) === $tail
        || tessera_customer_cargo_phone_tail((string) ($row['r_no'] ?? '')) === $tail;
}
