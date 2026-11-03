<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
include 'modules/connection/connect.php';
include 'modules/read/general/Read.php';
include 'modules/read/general/ReadJson.php';

$code = $_POST['code'] ?? '';
$name = $_POST['vehicle'] ?? '';
$date = $_POST['date'] ?? '';
$error = "";
$parcel_json = [];
header('Content-Type: application/json');
$select = ['Route'];
$where = ['Name' => $name, 'date' => $date, 'disable2' => ''];
$vehicle_name = new Read(['select' => $select, 'pdo' => $pdo, 'where' => $where, 'table' => "vehicle_name", 'order' => "", 'limit' => ""]);
$vehicle_name->available_row;

if ($vehicle_name->available_row == 1) {
	$select = ['store','id','s_name','code','username','lugg_value','weight_name','weight_value','date_insert','trip_date','s_no','r_name','r_no','amount','initial','bus', 's_surname', 's_no', 'r_no', 'r_name', 'account', 'detail', 'due', 'scanned', 'question', 'amount_remain', 'status', 'paid', 'to_be', 'answer', 'payment_status', 'paid_remain', 'to_be_remain'];
	$where = ['code' => $code];
	$parcel_info = new ReadJson(['select' => $select, 'pdo' => $pdo, 'where' => $where, 'table' => "parcel_info", 'order' => '', 'limit' => '']);
	$parcel_info->available_row;
	if ($parcel_info->available_row == 1) {
		$parcel_json = $parcel_info->rows;
		if($parcel_info->scanned!=0){

		}else{
			$error .= 'Loaded on <strong>'.date("dS-m-Y", strtotime($parcel_info->trip_date)).'</strong> Bus no <strong>'.$parcel_info->bus.'</strong>';
		}

	} else {
		$error .= "Luggage not found ";
	}

} else {
	$error .= "Locked";
}
$boolean_response = ($error == "") ? true : false;

echo json_encode([
	'success' => $boolean_response,
	'message' => $error,
	'data' => $parcel_json
]);
exit;
