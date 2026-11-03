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
$date1_exp = $_POST['date1_exp'];
$date2_exp = $_POST['date2_exp']; 
$date3exp = $_POST['date3exp'];
$searchArray = array();

## Search 
$searchQuery = " ";
if($searchValue != ''){
	$searchQuery = " AND (v_no LIKE :v_no or 
        amount  LIKE :amount  OR 
        insert_amount LIKE :insert_amount or time LIKE :time ) ";
    $searchArray = array( 
        'v_no'=>"%$searchValue%", 
        'amount'=>"%$searchValue%",
        'insert_amount'=>"%$searchValue%",
		'time'=>"%$searchValue%"
    );
}

## Total number of records without filtering
$stmt = $conn->prepare("SELECT COUNT(*) AS allcount FROM manifest_exp");
$stmt->execute();
$records = $stmt->fetch();
$totalRecords = $records['allcount'];

## Total number of records with filtering
$stmt = $conn->prepare("SELECT COUNT(*) AS allcount FROM manifest_exp WHERE 1 ".$searchQuery);
$stmt->execute($searchArray);
$records = $stmt->fetch();
$totalRecordwithFilter = $records['allcount'];

## Fetch records
$stmt = $conn->prepare("SELECT * FROM manifest_exp WHERE trip_date >='$date1_exp'and bus = '$date3exp'and 1 ".$searchQuery." ORDER BY ".$columnName." ".$columnSortOrder." LIMIT :limit,:offset");

// Bind values
foreach($searchArray as $key=>$search){
    $stmt->bindValue(':'.$key, $search,PDO::PARAM_STR);
}

$stmt->bindValue(':limit', (int)$row, PDO::PARAM_INT);
$stmt->bindValue(':offset', (int)$rowperpage, PDO::PARAM_INT);
$stmt->execute();
$empRecords = $stmt->fetchAll();

$data = array();


foreach($empRecords as $row){
    $data[] = array(
           	
			"time"=>$row['time'],
			"v_no"=>$row['v_no'],
			"detail"=>base64_decode($row['detail']),         
	        
		"to_be"=>$row['to_be'],
			"paid"=>$row['paid'],
		
             "amount"=>$row['amount'],
			 "status_name"=>$row['status_name'],
			 "insert_amount"=>number_format($row['insert_amount'],2),
	
		"date"=>$row['insert_date'],
		"username"=>$row['username'],
		"income_amt"=>$row['income_amt'],
		"status"=>$row['status'],
		
        );

}

## amount 
$amount  = array(
    "draw" => intval($draw),
    "iTotalRecords" => $totalRecords,
    "iTotalDisplayRecords" => $totalRecordwithFilter,
    "aaData" => $data
);

echo json_encode($amount );
