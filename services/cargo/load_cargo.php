<?php
session_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);

date_default_timezone_set('Africa/Dar_es_Salaam');
$date_insert = date('Y-m-d', time());
$date_time_insert = date('h:i A', time());

$date_add = "$date_insert at $date_time_insert";
include 'connect.php';
include 'modules/connection/connect.php';
include 'modules/read/general/Read.php';
include 'modules/update/general/Update.php';
include 'modules/create/general/Create.php';
$error = "";

// AJAX endpoint - require POST payload (avoids PHP 8 TypeError on empty GET hits)
$required = ['code', 'parcel_info_json', 'vehicle_name', 'trip_date', 'to_be', 'paid', 'qty', 'username_uploader'];
foreach ($required as $field) {
	if (!isset($_POST[$field]) || $_POST[$field] === '') {
		echo '{"error_no":"EE000","msg":"Missing required field: ' . $field . '"}';
		exit;
	}
}

$code = $_POST['code'];
$parcel_info_json = $_POST['parcel_info_json'];
$parcel_data = json_decode($parcel_info_json, true);
if (!is_array($parcel_data) || empty($parcel_data[0])) {
	echo '{"error_no":"EE000","msg":"Invalid parcel_info_json"}';
	exit;
}

$vehicle_name = $_POST['vehicle_name'];
$trip_date = $_POST['trip_date'];

$to_be = $_POST['to_be'];
$paid = $_POST['paid'];
$qty = $_POST['qty'];
$username_uploader = $_POST['username_uploader'];

$transit = (isset($_POST['transit'])) ? $_POST['transit'] : "";

$qty = preg_replace('/[^0-9.]+/', '', (string) $qty);
$to_be = preg_replace('/[^0-9.]+/', '', (string) $to_be);
$paid = preg_replace('/[^0-9.]+/', '', (string) $paid);
$amount = (float) $to_be + (float) $paid;
$primary_route = "";


$error .= varification($qty, $parcel_data[0]['scanned'], $paid, $to_be, $parcel_data[0]['paid_remain'], $parcel_data[0]['to_be_remain'], $parcel_data[0]['status'], $transit);


if ($error == "") {
    $exchange_rate = 1;
    $select = ['to_pay', 'paid', 'due', 'Route', 'currency'];
    $where = ['date' => $trip_date, 'Name' => $vehicle_name];
    $manifest = new Read(['select' => $select, 'pdo' => $pdo, 'where' => $where, 'table' => "vehicle_name", 'order' => "", '' => ""]);

    $parcel_currency = $parcel_data[0]['initial'];
    $manifest_currency = $manifest->currency;

    if ($manifest_currency != $parcel_currency) {
        $exchange_rate = exchange_rate($pdo, $parcel_currency, $manifest_currency);
    }
   
    $final_to_pay = $to_be * $exchange_rate;
    $final_paid = $paid * $exchange_rate;
    $final_total = $final_paid + $final_to_pay;

    $manfiest_to_paid = $manifest->to_pay + $final_to_pay;
    $manfiest_paid = $manifest->paid + $final_paid;
    $manfiest_total = $manifest->due + $final_total;

    $data = ['to_pay' => $manfiest_to_paid, 'paid' => $manfiest_paid, 'due' => $manfiest_total,];
    $where = ["date" => $trip_date, "Name" => $vehicle_name];
    $update_manifest = new Update($pdo, $data, 'vehicle_name', $where);

    $qty_remain = $parcel_data[0]['scanned'] - $qty;
    $luggage_status = ($qty_remain == 0) ? $luggage_status = "out" : $luggage_status = "half_out";
    $to_be_remain = $parcel_data[0]['to_be_remain'] - $to_be;
    $paid_remain = $parcel_data[0]['paid_remain'] - $paid;

    $data = ['store' => $luggage_status, 'scanned' => $qty_remain, 'quantity_remain' => $qty_remain, 'paid_remain' => $paid_remain, 'to_be_remain' => $to_be_remain, 'bus' => $vehicle_name, 'trip_date' => $trip_date];
    $where = ["code" => $code];
    $update_manifest = new Update($pdo, $data, 'parcel_info', $where);

    $data = ['code' => $code, 'detail' => $parcel_data[0]['detail'], 'username' => $parcel_data[0]['r_name'], 'parcel_route' => $parcel_data[0]['account'], 'amount' => $amount, 'trip_date' => $trip_date, 'bus' => $vehicle_name, 'paid' => $paid, 'to_be' => $to_be, 'status' => $parcel_data[0]['status'], 'currency' => $parcel_data[0]['initial'], 'uploader' => $username_uploader, 'q_left' => $qty_remain, 'quantity' => $parcel_data[0]['question'], 'parcel_date' => $parcel_data[0]['date_insert'], 'quantity_insert' => $qty, 'weight_name' => $parcel_data[0]['weight_name'], 'weight_value' => $parcel_data[0]['weight_value'], 'lugg_value' => $parcel_data[0]['lugg_value'], 'sender_no' => $parcel_data[0]['s_no'], 'reciever_no' => $parcel_data[0]['r_no'], 'date_insert' => $date_add, 'parcel_uploader' => $parcel_data[0]['username'],];
    $create_manifest = new Create($pdo, $data, "manifest");

} else {

}

echo $error;





function varification($qty, $scanned, $paid, $to_be, $paid_remain, $to_be_remain, $status, $transit)
{
    $error = '';
    if ($qty > $scanned) {
        $error .= '{"error_no": "EE001","msg":"Manifest Quantity is Greater than Actual Quantity"}';

    } else if (($qty <= 0) AND ($transit == "")) {
        $error .= '{"error_no": "EE002","msg":"Manifest Quantity Can not be Zero or less than zero"}';
    } else if ($paid > $paid_remain) {
        $error .= '{"error_no": "EE007","msg":"Manifest paid is much higher than Actual  paid"}';
    } else if (($qty < $scanned) && ($paid == $paid_remain) && ($status == "paid")) {
        $error .= '{"error_no": "EE009","msg":"Manifest Quantity Does not match Actual Amount Remain"}';
    } else if (($qty == $scanned) && ($paid < $paid_remain)) {
                $error .= '{"error_no": "EE010","msg":"Manifest Quantity Does not match Actual Amount Remain"}';

    } else if ($to_be > $to_be_remain) {
        $error .= '{"error_no": "EE005","msg":"Manifest to be paid is much higher than Actual  to be paid"}';

    } else if (($qty == $scanned) && ($to_be < $to_be_remain)) {
        $error .= '{"error_no": "EE006","msg":"Manifest to be paid is much higher than Actual  to be paid"}';

    } else if (($qty < $scanned) && ($to_be == $to_be_remain) && ($status == "To be Payed")) {
        $error .= '{"error_no": "EE008","msg":"Manifest Quantity is Greater than Actual Quantity"}';
    } else if (($qty < $scanned) && ($to_be == $to_be_remain) && ($paid == $paid_remain) && ($status == "Due payment")) {
        
                $error .= '{"error_no": "EE011","msg":"Manifest Quantity is less than paid and to be paid value}';

    }

    return $error;
}
function exchange_rate($pdo, $parcel_currency, $manifest_currency)
{
    
    $value = 1;
    $select = ['value'];
    $where = ['first_point' => $parcel_currency, 'second_point' => $manifest_currency];
    $exchange_rate = new Read(['select' => $select, 'pdo' => $pdo, 'where' => $where, 'table' => "currency_value", 'order' => "", '' => ""]);
    if ($exchange_rate->available_row != 0) {
        $value = $exchange_rate->value;
    } else {
        $value = 1;
    }
    return $value;
}





