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

$searchArray = array();

## Search 
$searchQuery = " ";
if($searchValue != ''){
	$searchQuery = " AND (trip_date LIKE :trip_date or 
        id  LIKE :id  or 
        seat_no  LIKE :seat_no  or 
        firstname  LIKE :firstname  or 
        lastname  LIKE :lastname    ) ";
    $searchArray = array( 
        'trip_date'=>"%$searchValue%", 
        'id'=>"%$searchValue%", 
		'seat_no'=>"%$searchValue%",
		'firstname'=>"%$searchValue%",'lastname'=>"%$searchValue%",
       
		
    );
}

## Total number of records without filtering
$stmt = $conn->prepare("SELECT COUNT(*) AS allcount FROM booking WHERE " . tessera_sql_tenant_eq() . "");
$stmt->execute();
$records = $stmt->fetch();
$totalRecords = $records['allcount'];

## Total number of records with filtering
$stmt = $conn->prepare("SELECT COUNT(*) AS allcount FROM booking WHERE " . tessera_sql_tenant_eq() . " AND 1  ".$searchQuery);
$stmt->execute($searchArray);
$records = $stmt->fetch();
$totalRecordwithFilter = $records['allcount'];

## Fetch records
$stmt = $conn->prepare("SELECT * FROM booking WHERE " . tessera_sql_tenant_eq() . " AND 1  ".$searchQuery." ORDER BY ".$columnName." ".$columnSortOrder." LIMIT :limit,:offset");

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
          
			"trip_date"=>$row['trip_date'],
			"bus"=>$row['bus'],	 
            "seat_no"=>$row['seat_no'],
			"id"=>$row['id'], 
			"fullname"=>''.$row['firstname'].' '.$row['lastname'].'',
			"trip_date"=>$row['trip_date'],
			"issuer"=>$row['issuer'],
			"cost"=>number_format($row['cost'],2),
		
			"ending_location"=>$row['ending_location'],
			"start_location"=>$row['start_location'],
			"book_date_time"=>''.$row['book_date'].' '.$row['book_time'].'',
		
	
	
   );
}

## account 
$response  = array(
    "draw" => intval($draw),
    "recordsTotal" => intval($totalRecords),
    "recordsFiltered" => intval($totalRecordwithFilter),
    "data" => $data,
    // legacy DataTables keys
    "iTotalRecords" => intval($totalRecords),
    "iTotalDisplayRecords" => intval($totalRecordwithFilter),
    "aaData" => $data
);

header('Content-Type: application/json; charset=utf-8');
echo json_encode($response);
