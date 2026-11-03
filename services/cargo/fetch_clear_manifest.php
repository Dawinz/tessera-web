<?php
require_once __DIR__ . '/includes/tessera_staff_boot.php';
include 'config2.php';
header('Content-Type: application/json; charset=utf-8');

## Read value (tolerant of partial DataTables payloads)
$draw = (int) ($_POST['draw'] ?? 0);
$row = (int) ($_POST['start'] ?? 0);
$rowperpage = (int) ($_POST['length'] ?? 100);
if ($rowperpage <= 0 || $rowperpage > 200) {
	$rowperpage = 100;
}
$columnIndex = (int) ($_POST['order'][0]['column'] ?? 12);
$columnName = (string) ($_POST['columns'][$columnIndex]['data'] ?? 'id');
$columnSortOrder = strtolower((string) ($_POST['order'][0]['dir'] ?? 'desc')) === 'asc' ? 'asc' : 'desc';
$searchValue = (string) ($_POST['search']['value'] ?? '');
$route = (string) ($_POST['route'] ?? '');

$orderAllow = [
	'id' => 'id',
	'code' => 'code',
	'username' => 'username',
	'bus' => 'bus',
	'date' => 'trip_date',
	'amount' => 'amount',
	'to_be' => 'to_be',
	'paid' => 'paid',
	'status' => 'status',
];
$orderCol = $orderAllow[$columnName] ?? 'id';

$searchArray = array();

## Search
$searchQuery = ' ';
if ($searchValue !== '') {
	$searchQuery = ' AND (code LIKE :code) ';
	$searchArray = array(
		'code' => '%' . $searchValue . '%',
	);
}

$tenantSql = tessera_sql_tenant_eq();
$routeSql = $route !== '' ? ' AND parcel_route = :parcel_route ' : '';

## Total number of records without filtering
$stmt = $conn->prepare("SELECT COUNT(*) AS allcount FROM manifest WHERE {$tenantSql}");
$stmt->execute();
$records = $stmt->fetch();
$totalRecords = (int) ($records['allcount'] ?? 0);

## Total number of records with filtering
$stmt = $conn->prepare("SELECT COUNT(*) AS allcount FROM manifest WHERE {$tenantSql} {$routeSql} AND 1 {$searchQuery}");
foreach ($searchArray as $key => $search) {
	$stmt->bindValue(':' . $key, $search, PDO::PARAM_STR);
}
if ($route !== '') {
	$stmt->bindValue(':parcel_route', $route, PDO::PARAM_STR);
}
$stmt->execute();
$records = $stmt->fetch();
$totalRecordwithFilter = (int) ($records['allcount'] ?? 0);

## Fetch records
$stmt = $conn->prepare(
	"SELECT * FROM manifest WHERE {$tenantSql} {$routeSql} AND 1 {$searchQuery} ORDER BY {$orderCol} {$columnSortOrder} LIMIT :offset, :limit"
);
foreach ($searchArray as $key => $search) {
	$stmt->bindValue(':' . $key, $search, PDO::PARAM_STR);
}
if ($route !== '') {
	$stmt->bindValue(':parcel_route', $route, PDO::PARAM_STR);
}
$stmt->bindValue(':offset', $row, PDO::PARAM_INT);
$stmt->bindValue(':limit', $rowperpage, PDO::PARAM_INT);
$stmt->execute();
$empRecords = $stmt->fetchAll();

$data = array();

foreach ($empRecords as $row) {
	$stored = (string) ($row['detail'] ?? '');
	$detailRaw = $stored;
	// Only treat as base64 when decode yields printable text (plain text can
	// "successfully" decode into garbage like ¬z%v).
	$decoded = base64_decode($stored, true);
	if ($decoded !== false && $decoded !== '' && preg_match('//u', $decoded)
		&& !preg_match('/[\x00-\x08\x0B\x0C\x0E-\x1F]/', $decoded)
		&& preg_match('/[\p{L}\p{N}\s]/u', $decoded)) {
		$detailRaw = $decoded;
	}
	$detail = function_exists('mb_convert_encoding')
		? mb_convert_encoding($detailRaw, 'UTF-8', 'UTF-8, ISO-8859-1, Windows-1252')
		: $detailRaw;
	$detail = preg_replace('/[\x00-\x08\x0B\x0C\x0E-\x1F]/', '', (string) $detail);
	$detail = trim((string) $detail);
	// Drop mojibake / binary leftovers so the cell stays readable.
	if ($detail === '' || !preg_match('/[\p{L}\p{N}]/u', $detail) || preg_match('/[\x80-\x9F]/', $detail)) {
		$detail = ' - ';
	}
	$detail = htmlspecialchars($detail, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');

	$rid = (int) $row['id'];
	$codeEsc = htmlspecialchars((string) $row['code'], ENT_QUOTES, 'UTF-8');
	$checkedEsc = htmlspecialchars((string) $row['checked'], ENT_QUOTES, 'UTF-8');

	$data[] = array(
		'id' => $rid,
		'status' => $row['status'],
		'bus' => $row['bus'],
		'code' => $row['code'],
		'username' => $row['username'],
		'Weight' => '' . $row['weight_value'] . '' . $row['weight_name'] . '',
		'detail' => $detail,
		'left' => '' . $row['q_left'] . '/ ' . $row['quantity'] . '',
		'date' => $row['trip_date'],
		'amount' => number_format((float) $row['amount'], 2),
		'to_be' => number_format((float) $row['to_be'], 2),
		'paid' => number_format((float) $row['paid'], 2),
		'action' =>
			'<span id="id' . $rid . '" style="display:none;">' . $rid . '</span>' .
			'<span id="checked' . $rid . '" style="display:none;">' . $checkedEsc . '</span>' .
			'<button type="button" class="btn btn-danger js-cargo-checkout" data-id="' . $rid . '" id="check' . $rid . '">' .
			'<span class="uil uil-check-circle"></span></button>' .
			'<div style="display:none;" id="loading' . $rid . '">clearing...</div>',
		'print' =>
			'<a href="qrcode/print_recieved.php?code=' . rawurlencode((string) $row['code']) . '&id=' . $rid . '">' .
			'<button type="button" class="btn btn-info"><span class="uil uil-print"></span></button></a>',
		'checked' => $row['checked'],
	);
}

$amount = array(
	'draw' => $draw,
	'iTotalRecords' => $totalRecords,
	'iTotalDisplayRecords' => $totalRecordwithFilter,
	'aaData' => $data,
);

$json = json_encode($amount, JSON_INVALID_UTF8_SUBSTITUTE | JSON_UNESCAPED_UNICODE);
if ($json === false) {
	$json = json_encode(array(
		'draw' => $draw,
		'iTotalRecords' => $totalRecords,
		'iTotalDisplayRecords' => 0,
		'aaData' => array(),
		'error' => 'json_encode_failed',
	));
}
echo $json !== false ? $json : '{"draw":0,"iTotalRecords":0,"iTotalDisplayRecords":0,"aaData":[],"error":"encode"}';
