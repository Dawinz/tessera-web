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
	$searchQuery = " AND (firstname LIKE :firstname or 
        number_e  LIKE :number_e  OR 
        surname LIKE :surname ) ";
    $searchArray = array( 
        'firstname'=>"%$searchValue%", 
        'number_e'=>"%$searchValue%",
        'surname'=>"%$searchValue%"
    );
}

## Total number of records without filtering
$stmt = $conn->prepare("SELECT COUNT(*) AS allcount FROM employee");
$stmt->execute();
$records = $stmt->fetch();
$totalRecords = $records['allcount'];

## Total number of records with filtering
$stmt = $conn->prepare("SELECT COUNT(*) AS allcount FROM employee WHERE 1 ".$searchQuery);
$stmt->execute($searchArray);
$records = $stmt->fetch();
$totalRecordwithFilter = $records['allcount'];

## Fetch records
$stmt = $conn->prepare("SELECT * FROM employee WHERE   1 ".$searchQuery." ORDER BY ".$columnName." ".$columnSortOrder." LIMIT :limit,:offset");

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
            "firstname"=>''.$row['firstname'].' '.$row['surname'].'',
			 "grade"=>$row['grade'],
            "phone"=>$row['phone'],
            "number_e"=>$row['number_e'],
			"account"=>$row['account'],
             "id"=>'<a href=profile.php?id='.$row['id'].'><button type="button" class="btn btn-primary">view</button></a>',
			 "update"=>'<a href=employe_update.php?id='.$row['id'].'><button type="button" class="btn btn-danger">Update</button></a>',
"warning"=>'<a href=warning.php?id='.$row['id'].'><button type="button" class="btn btn-danger">Warning</button></a>',
        );
}

## number_e 
$number_e  = array(
    "draw" => intval($draw),
    "iTotalRecords" => $totalRecords,
    "iTotalDisplayRecords" => $totalRecordwithFilter,
    "aaData" => $data
);

echo json_encode($number_e );
