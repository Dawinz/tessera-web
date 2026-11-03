<?php
require_once __DIR__ . '/includes/tessera_staff_boot.php';
include 'config2.php';
## Read value
$draw = isset($_POST['draw']) ? (int)$_POST['draw'] : 0;
$row = isset($_POST['start']) ? (int)$_POST['start'] : 0;
$rowperpage = isset($_POST['length']) ? (int)$_POST['length'] : 10;
if ($rowperpage < 1) { $rowperpage = 10; }
$columnIndex = isset($_POST['order'][0]['column']) ? (int)$_POST['order'][0]['column'] : 0;
$columnName = 'id';
if (isset($_POST['columns'][$columnIndex]['data']) && $_POST['columns'][$columnIndex]['data'] !== '') {
	$columnName = preg_replace('/[^a-zA-Z0-9_]/', '', (string)$_POST['columns'][$columnIndex]['data']);
	if ($columnName === '') { $columnName = 'id'; }
}
$columnSortOrder = (isset($_POST['order'][0]['dir']) && strtolower($_POST['order'][0]['dir']) === 'asc') ? 'ASC' : 'DESC';
$searchValue = isset($_POST['search']['value']) ? $_POST['search']['value'] : '';
$start_location = $_POST['start_location_select']; 

$start_date_select = $_POST['start_date_select']; 
$end_date_select = $_POST['end_date_select'];
$start_year_select = $_POST['start_year_select'];
$end_year_select = $_POST['end_year_select'];
$searchArray = array();

## Search 
$searchQuery = " ";
if($searchValue != ''){
	$searchQuery = " AND (trip_date LIKE :trip_date or 
        bus  LIKE :bus  OR 
        start_location LIKE :start_location OR end_location Like :end_location  ) ";
    $searchArray = array( 
        'trip_date'=>"%$searchValue%", 
        'bus'=>"%$searchValue%",
        'start_location'=>"%$searchValue%",
		'end_location'=>"%$searchValue%"
		
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
$stmt = $conn->prepare("SELECT * FROM booking WHERE " . tessera_sql_tenant_eq() . " AND route_id ='$start_location'  and book_date >='$start_date_select' and year='$start_year_select' and book_date <='$end_date_select' and year='$end_year_select' and cost !='0' and delete_book ='' and 1  ".$searchQuery." ORDER BY ".$columnName." ".$columnSortOrder." LIMIT :limit,:offset");

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
