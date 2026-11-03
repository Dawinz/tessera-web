<?php

use Dom\Implementation;
//header('Content-Type: application/json; charset=utf-8');

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
require_once '../../../modules/connection/connect.php';
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['success' => false, 'message' => 'Method not allowed.']);
    exit;
}
function post($key, $default = null)
{
    return isset($_POST[$key]) && $_POST[$key] !== '' ? trim($_POST[$key]) : $default;
}

$filters = [
    'code' => post('code'),
    'account' => post('route_name'),
    'status' => post('status'),
    's_name' => post('sender_name'),
    's_no' => post('sender_no'),
    'r_name' => post('receiver_name'),
    'r_no' => post('receiver_no'),
    'store' => post('dispatch_info'),
    'username' => post('username'),
    'weight_name' => post('weight_name'),
    'weight_value' => post('weight_value'),
    'lugg_value' => post('luggage_value'),
    'amount' => post('amount'),
    'date_from' => post('date_from'),
    'date_to' => post('date_to'),

];
$where = [];
$params = [];

if ($filters['code'] !== null) {
    $where[] = 'code LIKE :code';
    $params[':code'] = '%' . $filters['code'] . '%';
}
if ($filters['account'] !== null) {
    $where[] = 'account LIKE :account';
    $params[':account'] = '%' . $filters['account'] . '%';
}
if ($filters['status'] !== null) {
    $where[] = 'status = :status';
    $params[':status'] = $filters['status'];
}
if ($filters['s_name'] !== null) {
    $where[] = 's_name LIKE :s_name';
    $params[':s_name'] = '%' . $filters['s_name'] . '%';
}
if ($filters['s_no'] !== null) {
    $where[] = 's_no LIKE :s_no';
    $params[':s_no'] = '%' . $filters['s_no'] . '%';
}
if ($filters['r_name'] !== null) {
    $where[] = 'r_name LIKE :r_name';
    $params[':r_name'] = '%' . $filters['r_name'] . '%';
}
if ($filters['r_no'] !== null) {
    $where[] = 'r_no LIKE :r_no';
    $params[':r_no'] = '%' . $filters['r_no'] . '%';
}
if ($filters['store'] !== null) {
    $where[] = 'store = :store';
    $params[':store'] = $filters['store'];
}
if ($filters['username'] !== null) {
    $where[] = 'username = :username';
    $params[':username'] = $filters['username'];
}
if ($filters['weight_name'] !== null) {
    $where[] = 'weight_name = :weight_name';
    $params[':weight_name'] = $filters['weight_name'];
}
if ($filters['weight_value'] !== null) {
    $where[] = 'weight_value = :weight_value';
    $params[':weight_value'] = $filters['weight_value'];
}
if ($filters['lugg_value'] !== null) {
    $where[] = 'lugg_value = :lugg_value';
    $params[':lugg_value'] = $filters['lugg_value'];
}
if ($filters['amount'] !== null) {
    $where[] = 'amount = :amount';
    $params[':amount'] = $filters['amount'];
}
if ($filters['date_from'] !== null) {
    $datetime = $filters['date_from'];
    list($date, $time) = explode(" ", $datetime, 2);

    $where[] = 'date_insert >= :date_insert';
    $params[':date_insert'] = $date;
    $where[] = 'time >= :time';
    $params[':time'] = $time;

}
if ($filters['date_to'] !== null) {
    $datetime = $filters['date_to'];
    list($date, $time) = explode(" ", $datetime, 2);

    $where[] = 'date_insert <= :date_insert';
    $params[':date_insert'] = $date;
 $where[] = 'time <= :time';
    $params[':time'] = $time;
}

$whereSql = $where ? ('WHERE ' . implode(' AND ', $where)) : '';

$limit = (int) post('limit', 20);
if ($limit < 1 || $limit > 100) {
    $limit = 20;
}
$page = (int) post('page', 1);
if ($page < 1) {
    $page = 1;
}
$offset = ($page - 1) * $limit;

$sql = "
    SELECT
        id,
        code,
        account,
        status,
        s_name,
        s_no,
        r_name,
        r_no,
        store,
        username,
        weight_name,
        weight_value,
        lugg_value,
        question,
        amount,
        to_be,
        payed,
        initial,
        date_insert,
        time

    FROM  parcel_info
    {$whereSql}
    ORDER BY id DESC
    LIMIT :limit OFFSET :offset
";
try {
    $countStmt = $pdo->prepare("SELECT COUNT(*) FROM parcel_info {$whereSql}");
    $countStmt->execute($params);
    $total = (int) $countStmt->fetchColumn();

    $stmt = $pdo->prepare($sql);
    foreach ($params as $key => $value) {
        $stmt->bindValue($key, $value);
    }
    $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
    $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
    $stmt->execute();
    $rows = $stmt->fetchAll();
    echo json_encode([
        'success' => true,
        'data' => $rows,
        'total' => $total,
        'page' => $page,
        'limit' => $limit,
        'total_pages' => $limit > 0 ? (int) ceil($total / $limit) : 0,
    ]);

} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode([
        'success' => false,
        'message' => 'Query failed. Check the "luggage" table/column names match your schema.',
    ]);

}
