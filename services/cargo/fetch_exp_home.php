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
$stock_no = $_POST['stock_no'];

$searchArray = array();

## Search 
$searchQuery = " ";
if($searchValue != ''){
	$searchQuery = " AND (stock_no LIKE :stock_no or 
        stock_amount  LIKE :stock_amount  OR 
        date LIKE :date or username LIKE :username ) ";
    $searchArray = array( 
        'stock_no'=>"%$searchValue%", 
        'stock_amount'=>"%$searchValue%",
        'date'=>"%$searchValue%",
		'username'=>"%$searchValue%"
    );
}

## Total number of records without filtering
$stmt = $conn->prepare("SELECT COUNT(*) AS allcount FROM expenses");
$stmt->execute();
$records = $stmt->fetch();
$totalRecords = $records['allcount'];

## Total number of records with filtering
$stmt = $conn->prepare("SELECT COUNT(*) AS allcount FROM expenses WHERE 1 ".$searchQuery);
$stmt->execute($searchArray);
$records = $stmt->fetch();
$totalRecordwithFilter = $records['allcount'];

## Fetch records
$stmt = $conn->prepare("SELECT * FROM expenses WHERE and 1 ".$searchQuery." ORDER BY ".$columnName." ".$columnSortOrder." LIMIT :limit,:offset");

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
           	
			"date"=>$row['date'],
			"stock_no"=>$row['stock_no'],
			"detail"=>base64_decode($row['detail']),         
		"approved"=>$row['approved'],
			 "amount"=>number_format($row['amount'],2),
			 "stock_amount"=>$row['stock_amount'],
			 "amount_remain"=>$row['amount_remain'],
		"insert_date_time"=>$row['insert_date_time'],
		"username"=>$row['username'],
		"time"=>$row['time'],
	
		
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
