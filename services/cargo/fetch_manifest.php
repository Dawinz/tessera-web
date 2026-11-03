<?php
require_once __DIR__ . '/includes/tessera_staff_boot.php';
include 'config2.php';
## Read value
$draw = $_POST['draw'];
$row = $_POST['start'];
$rowperpage = $_POST['length']; // Rows display per page
$columnIndex = $_POST['order'][0]['column']; // Column index
$columnName = $_POST['columns'][$columnIndex]['data']; // Column name
$columnSortOrder = $_POST['order'][0]['dir']; // asc or desc
$searchValue = $_POST['search']['value']; // Search value
$currency_value = $_POST['currency_value'];
$trip_date = $_POST['trip_date'];
$vehicle = $_POST['vehicle'];
$default_currency = base64_decode($_POST['currency']);
$searchArray = array();

## Search 
$searchQuery = " ";
if ($searchValue != '') {
    $searchQuery = " AND (code LIKE :code or 
        username  LIKE :username  OR 
        trip_date LIKE :trip_date or status LIKE :status ) ";
    $searchArray = array(
        'code' => "%$searchValue%",
        'username' => "%$searchValue%",
        'trip_date' => "%$searchValue%",
        'status' => "%$searchValue%"
    );
}

## Total number of records without filtering
$stmt = $conn->prepare("SELECT COUNT(*) AS allcount FROM manifest WHERE " . tessera_sql_tenant_eq() . "");
$stmt->execute();
$records = $stmt->fetch();
$totalRecords = $records['allcount'];

## Total number of records with filtering
$stmt = $conn->prepare("SELECT COUNT(*) AS allcount FROM manifest WHERE " . tessera_sql_tenant_eq() . " AND trip_date ='$trip_date' and bus = '$vehicle' and 1 " . $searchQuery);
$stmt->execute($searchArray);
$records = $stmt->fetch();
$totalRecordwithFilter = $records['allcount'];

## Fetch records
$stmt = $conn->prepare("SELECT * FROM manifest WHERE " . tessera_sql_tenant_eq() . " AND trip_date ='$trip_date' and bus = '$vehicle' and 1 " . $searchQuery . " ORDER BY ID DESC LIMIT :limit,:offset");

// Bind values
foreach ($searchArray as $key => $search) {
    $stmt->bindValue(':' . $key, $search, PDO::PARAM_STR);
}

$stmt->bindValue(':limit', (int) $row, PDO::PARAM_INT);
$stmt->bindValue(':offset', (int) $rowperpage, PDO::PARAM_INT);
$stmt->execute();
$empRecords = $stmt->fetchAll();

$data = array();
$routes = [];
$values = [];
$no =0;
foreach ($empRecords as $row) {
$no++;
    $data[] = array(

        "status" => $row['status'],
        "code" => $row['code'],
        "r_name" => $row['username'],
        "qty" => $row['quantity_insert'],

        "amount" => number_format($row['amount'], 2),
        "to_be" => number_format($row['to_be'], 2),
        "paid" => number_format($row['paid'], 2),
        "sno" => $no,
        "remove" => '<span class="remove_luggage" data-id="'. $row['id'] .'"><button type="button" class="btn btn-danger btn-sm">
          <span class="glyphicon glyphicon-remove"></span> <span id="dtext'.$row['id'].'">Remove</span>
        </button></span>',
    );
    $values[] = [
        "code" => $row['code'],
        "paid" => $row['paid'],
        "to_be" => $row['to_be'],
        "currency" => $row['currency']
    ];

    if (in_array($row['parcel_route'], $routes)) {
    } else {
        $routes[] = $row['parcel_route'];
    }
}
$holder = "";

$total_to_be =0;
$total_paid =0;
foreach ($values as $row) {
$exchange_rate = currency_rate($currency_value, $row["currency"], $default_currency);
 $total_to_be += $row['to_be']*$exchange_rate;
 $total_paid += $row['paid']*$exchange_rate;
}
$total_amount = $total_to_be + $total_paid;
$total_to_be_AV = shortNumber($total_to_be);
$total_paid_AV = shortNumber($total_paid);
$total_amount_AV  = shortNumber($total_amount);
function currency_rate($currency_value, $currency, $default_currency){
    $currency_value = json_decode($currency_value, true);
    $exchange_rate = 1;
    if($currency !=""){
    if (($currency != $default_currency) ) {
        foreach ($currency_value as $row) {
            $first_point = $row["first_point"];
            $second_point = $row["second_point"];

            if (($second_point == $default_currency) AND ($first_point == $currency)) {
                $exchange_rate = $row["value"];

            }


        }
    }

    }


    return $exchange_rate;


}
function shortNumber($number) {
    if ($number >= 1000000000) {
        return round($number / 1000000000, 2) . 'B';
    } elseif ($number >= 1000000) {
        return round($number / 1000000, 2) . 'M';
    } elseif ($number >= 1000) {
        return round($number / 1000, 2) . 'K';
    }
    return $number;
}


## amount 
$response = array(
    "draw" => intval($draw),
    "iTotalRecords" => $totalRecords,
    "iTotalDisplayRecords" => $totalRecordwithFilter,
    "aaData" => $data,
    "total_to_be_paid" => $total_to_be_AV,
    "total_paid" => $total_paid_AV,
    "total_amount" => $total_amount_AV,
);

echo json_encode($response);
