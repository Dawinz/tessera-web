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
$username = $_POST['username'];
$searchArray = array();

## Search 
$searchQuery = " ";
if($searchValue != ''){
	$searchQuery = " AND (code LIKE :code or 
        username  LIKE :username  OR 
        trip_date LIKE :trip_date or status LIKE :status ) ";
    $searchArray = array( 
        'code'=>"%$searchValue%", 
        'username'=>"%$searchValue%",
        'trip_date'=>"%$searchValue%",
		'status'=>"%$searchValue%"
    );
}

## Total number of records without filtering
$stmt = $conn->prepare("SELECT COUNT(*) AS allcount FROM manifest WHERE " . tessera_sql_tenant_eq() . "");
$stmt->execute();
$records = $stmt->fetch();
$totalRecords = $records['allcount'];

## Total number of records with filtering
$stmt = $conn->prepare("SELECT COUNT(*) AS allcount FROM manifest WHERE " . tessera_sql_tenant_eq() . " AND 1 ".$searchQuery);
$stmt->execute($searchArray);
$records = $stmt->fetch();
$totalRecordwithFilter = $records['allcount'];

## Fetch records
$stmt = $conn->prepare("SELECT * FROM manifest WHERE " . tessera_sql_tenant_eq() . " AND uploader='$username' and 1 ".$searchQuery." ORDER BY ".$columnName." ".$columnSortOrder." LIMIT :limit,:offset");

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
           	
			"status"=>$row['status'],
			"code"=>$row['code'],
			"username"=>$row['username'],
			"detail"=>base64_decode($row['detail']),
			
"left"=>''.$row['q_left'].'/ '.$row['quantity'].'',            
	        
		
			"amount"=>number_format($row['amount'],2),
		
             "to_be"=>number_format($row['to_be'],2),

			"paid"=>number_format($row['paid']  ,2),
			"date"=>$row['trip_date'],
				"Delete"=>'<a target="_blank" href = delet_luggage.php?u='.$row['id'].'><button type="button" class="btn btn-danger btn-sm">
          <span class="glyphicon glyphicon-remove"></span> Delete
        </button></a>',
		
		
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
