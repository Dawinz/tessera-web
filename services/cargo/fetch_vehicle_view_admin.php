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
	$searchQuery = " AND (Name LIKE :Name or 
        Driver  LIKE :Driver  OR 
        date LIKE :date OR Conductor Like :Conductor ) ";
    $searchArray = array( 
        'Name'=>"%$searchValue%", 
        'Driver'=>"%$searchValue%",
        'date'=>"%$searchValue%",
		'Conductor'=>"%$searchValue%"
    );
}

## Total number of records without filtering
$stmt = $conn->prepare("SELECT COUNT(*) AS allcount FROM vehicle_name");
$stmt->execute();
$records = $stmt->fetch();
$totalRecords = $records['allcount'];

## Total number of records with filtering
$stmt = $conn->prepare("SELECT COUNT(*) AS allcount FROM vehicle_name WHERE 1 ".$searchQuery);
$stmt->execute($searchArray);
$records = $stmt->fetch();
$totalRecordwithFilter = $records['allcount'];

## Fetch records
$stmt = $conn->prepare("SELECT * FROM vehicle_name WHERE   1 ".$searchQuery." ORDER BY ".$columnName." ".$columnSortOrder." LIMIT :limit,:offset");

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
                    "id"=>$row['id'],

			"Name"=>$row['Name'],
			"Driver"=>$row['Driver'],
            "Route"=>$row['Route'],
			"Conductor"=>$row['Conductor'],
			"date"=>$row['date'],
			"status"=>$row['status'],
	 "luggage_no"=>$row['luggage_no'],
 
	 "to_pay"=>number_format($row['to_pay'],2),	
	 "paid"=>number_format($row['paid'],2),	
	 "total"=>number_format($row['paid'] + $row['to_pay'],2),	
	"Delete"=>'<a  href = delete_view.php?u='.$row['id'].'><button type="button" class="btn btn-outline-danger">
          <span class="glyphicon glyphicon-remove"style="color:#e60000;"></span> 
        </button></a>',	
	"View"=>'<a  href = vehicle_report_new.php?date='.$row['date'].'&date2='.$row['date'].'&date3='.$row['Name'].'&open='.$row['status'].'><button type="button" class="btn btn-default">
          <span class="glyphicon glyphicon-eye-open"></span> 
        </button></a>',
		"update"=>'<a  href = edit_manifest.php?u='.$row['id'].'><button type="button" class="btn btn-default">
          <span class="glyphicon glyphicon-edit"></span> 
        </button></a>',	
			"close"=>'<a  href = lock.php?u='.$row['id'].'><button type="button" class="btn btn-default">
          <span class="glyphicon glyphicon-lock"></span> 
        </button></a>',
			"Insert"=>'<a  href = check_luggages.php?id='.$row['Name'].'&date='.$row['date'].'&open='.$row['status'].' ><button type="button" class="btn btn-default">
          <span class="	glyphicon glyphicon-share-alt"></span> 
        </button></a>',		
			"user"=>'<a  href = print_manifest.php?date='.$row['date'].'&date2='.$row['date'].'&name='.$row['Name'].'&open='.$row['status'].'><button type="button" class="btn btn-default">
           <span class="glyphicon glyphicon-print"></span> 
        </button></a>',
		"charges"=>'<a  href = charges.php?date='.$row['date'].'&date2='.$row['date'].'&date3='.$row['Name'].'&open='.$row['status'].'><button type="button" class="btn btn-default">
           <span class="glyphicon glyphicon-edit"></span> 
        </button></a>',
		
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
