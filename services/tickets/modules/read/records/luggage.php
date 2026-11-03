<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
require_once '../../../modules/connection/connect.php';
require_once '../general/Read.php';
require_once '../../update/general/Update.php';
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['success' => false, 'message' => 'Method not allowed.']);
    exit;
}
$id = $_POST['id'] ?? '';
$parcel_info = [];
$select = ['code', 'lugg_value', 'amount', 's_name,s_surname,s_no', 'r_name', 'r_no', 'account', 'detail', 'status', 'question', 'payed', 'to_be', 'weight_value', 'weight_name', 'initial', 'username', 'date_insert', 'time', 'printed_time','uploader'];
$where = ['id' => $id];
$parcel_value = new Read(['select' => $select, 'pdo' => $pdo, 'where' => $where, 'table' => "parcel_info", 'order' => "", 'limit' => ""]);
$parcel_info = $parcel_value->rows;

$select = ['currency_symbol', 'to_be_paid_currency', 'contact_1', 'contact_2', 'adress_1', 'adress_2'];
$where = ['route_full_name' => $parcel_info[0]['account']];
$route_value = new Read(['select' => $select, 'pdo' => $pdo, 'where' => $where, 'table' => "routes", 'order' => "", 'limit' => ""]);
$route_info = $route_value->rows;


$select = ['first_point', 'second_point', 'value'];
$where = [];
$currency_values = new Read(['select' => $select, 'pdo' => $pdo, 'where' => $where, 'table' => "currency_value", 'order' => "", 'limit' => ""]);
$currency_value_info = $currency_values->rows;


$select = ['url'];
$where = ['url_for' => 'luggage verification'];
$url = new Read(['select' => $select, 'pdo' => $pdo, 'where' => $where, 'table' => "constant_urls", 'order' => "", 'limit' => ""]);
$url_value = $url->rows;



$date_insert = $parcel_info[0]['date_insert'];
$unixTime = strtotime($date_insert);
$formated_date = date("d-m-Y", $unixTime);
$to_be_paid_value = to_be_paid_exchange($parcel_info[0]['status'], $parcel_info[0]['to_be'], $parcel_info[0]['initial'], $route_info[0]['to_be_paid_currency'], $currency_value_info);
$display_to_be = visiblity($parcel_info[0]['status'], $route_info[0]['to_be_paid_currency']);
$printed = $parcel_info[0]['printed_time'];
$printed_times = $printed + 1;

$parcel_info[0]['converted_date'] = $formated_date;
$parcel_info[0]['visibility'] = $display_to_be;
$parcel_info[0]['to_be_paid_value'] = $to_be_paid_value;
$parcel_info[0]['to_be_paid_currency'] = $route_info[0]['to_be_paid_currency'];
$parcel_info[0]['url'] = $url_value[0]['url'];
$parcel_info[0]['contact_1'] = $route_info[0]['contact_1'];
$parcel_info[0]['contact_2'] = $route_info[0]['contact_2'];
$parcel_info[0]['adress_1'] = $route_info[0]['adress_1'];
$parcel_info[0]['adress_2'] = $route_info[0]['adress_2'];
$parcel_info[0]['printed_times'] = $printed_times;

$data = ['printed_time' => $printed_times];
$where = ["code" => $parcel_info[0]['code']];
$update_manifest = new Update($pdo, $data, 'parcel_info', $where);


function to_be_paid_exchange($status_em, $value, $currency_symbol, $to_be_paid_currency, $currency_values)
{

    $exchange_rate = 1;
    foreach ($currency_values as $row) {
        $first_point = $row["first_point"];
        $second_point = $row["second_point"];
        if (($second_point == $to_be_paid_currency) AND ($first_point == $currency_symbol)) {
            $exchange_rate = $row["value"];
        }
    }
    $total_value = $value * $exchange_rate;
    return $total_value;

}
function visiblity($status_em, $to_be_paid_currency)
{
    $display = "";
    if (($status_em != "payed") AND ($to_be_paid_currency != "")) {

    } else {
        $display = "display:none";
    }
    return $display;
}


$error = "";
$boolean_response = ($error == "") ? true : false;
echo json_encode([
    'success' => $boolean_response,
    'message' => $error,
    'data' => $parcel_info
]);


