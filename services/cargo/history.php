<?php
include 'config2.php';
header('Content-Type: application/json; charset=utf-8');
## Read value
$draw = $_POST['draw'];
$row = $_POST['start'];
$rowperpage = $_POST['length']; // Rows display per page
$columnIndex = $_POST['order'][0]['column']; // Column index
$columnName = $_POST['columns'][$columnIndex]['data']; // Column name
$columnSortOrder = $_POST['order'][0]['dir']; // asc or desc
$searchValue = $_POST['search']['value']; // Search value
$username = $_POST['username'];
$searchArray = array();

## Search 
$searchQuery = " ";
if($searchValue != ''){
	$searchQuery = " AND (s_name LIKE :s_name or 
        code  LIKE :code  OR 
        date LIKE :date ) ";
    $searchArray = array( 
        's_name'=>"%$searchValue%", 
        'code'=>"%$searchValue%",
        'date'=>"%$searchValue%"
    );
}

## Total number of records without filtering
$stmt = $conn->prepare("SELECT COUNT(*) AS allcount FROM parcel_info");
$stmt->execute();
$records = $stmt->fetch();
$totalRecords = $records['allcount'];

## Total number of records with filtering
$stmt = $conn->prepare("SELECT COUNT(*) AS allcount FROM parcel_info WHERE 1 ".$searchQuery);
$stmt->execute($searchArray);
$records = $stmt->fetch();
$totalRecordwithFilter = $records['allcount'];

## Fetch records
$stmt = $conn->prepare("SELECT * FROM notification WHERE   1 ".$searchQuery." ORDER BY ".$columnName." ".$columnSortOrder." LIMIT :limit,:offset");

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
            "s_name"=>$row['s_name'],
			"id"=>$row['id'],
           "s_name"=>$row['s_name'],
		
            "r_name"=>$row['r_name'],
			"code"=>$row['code'],
			"date"=>$row['date'],
		
	 "status"=>$row['status'],
	 "uploader"=>$row['uploader'],			
			
		 "amount"=>number_format($row['amount'],2),
		    "paid"=>number_format($row['paid'],2),
	        "topay"=>number_format($row['topay'],2),
		"notification"=>$row['notification'],
		"deleted"=>$row['deleted'],
		"deleted_date"=>$row['deleted_date'],
		"l_status"=>$row['l_status'],
		"what_changed"=>$row['what_changed'],
        );
}

## account 
$response  = array(
    "draw" => intval($draw),
    "iTotalRecords" => $totalRecords,
    "iTotalDisplayRecords" => $totalRecordwithFilter,
    "aaData" => $data
);

echo json_encode($response);
