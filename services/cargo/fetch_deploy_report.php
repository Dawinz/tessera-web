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
	$searchQuery = " AND (name LIKE :name or 
        em_id  LIKE :em_id  OR 
        date LIKE :date ) ";
    $searchArray = array( 
        'name'=>"%$searchValue%", 
        'em_id'=>"%$searchValue%",
        'date'=>"%$searchValue%"
    );
}

## Total number of records without filtering
$stmt = $conn->prepare("SELECT COUNT(*) AS allcount FROM m_roll");
$stmt->execute();
$records = $stmt->fetch();
$totalRecords = $records['allcount'];

## Total number of records with filtering
$stmt = $conn->prepare("SELECT COUNT(*) AS allcount FROM m_roll WHERE 1 ".$searchQuery);
$stmt->execute($searchArray);
$records = $stmt->fetch();
$totalRecordwithFilter = $records['allcount'];

## Fetch records
$stmt = $conn->prepare("SELECT * FROM m_roll WHERE   1 ".$searchQuery." ORDER BY ".$columnName." ".$columnSortOrder." LIMIT :limit,:offset");

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
            "name"=>$row['name'],
			"em_id"=>$row['em_id'],
            "date"=>$row['date'],
			//////////////////////////
			//////////////////////////
			 "1"=>$row['1'],
			"2"=>$row['2'],
            "3"=>$row['3'],
            "4"=>$row['4'],
			"5"=>$row['5'],
			//////////////////////////
			//////////////////////////
						//////////////////////////
			//////////////////////////
			 "6"=>$row['6'],
			"7"=>$row['7'],
            "8"=>$row['8'],
            "9"=>$row['9'],
			"10"=>$row['10'],
			//////////////////////////
			//////////////////////////
			"11"=>$row['11'],
			"12"=>$row['12'],
            "13"=>$row['13'],
            "14"=>$row['14'],
			"15"=>$row['15'],
			//////////////////////////
			//////////////////////////
			"16"=>$row['16'],
			"17"=>$row['17'],
            "18"=>$row['18'],
            "19"=>$row['19'],
			"20"=>$row['20'],
			//////////////////////////
			//////////////////////////
			"21"=>$row['21'],
			"22"=>$row['22'],
            "23"=>$row['23'],
            "24"=>$row['24'],
			"25"=>$row['25'],
			//////////////////////////
			//////////////////////////
			"26"=>$row['26'],
			"27"=>$row['27'],
            "28"=>$row['28'],
            "29"=>$row['29'],
			"30"=>$row['30'],
			//////////////////////////
			//////////////////////////
          	"31"=>$row['31']	
			//////////////////////////
			//////////////////////////
        );
}

## em_id 
$em_id  = array(
    "draw" => intval($draw),
    "iTotalRecords" => $totalRecords,
    "iTotalDisplayRecords" => $totalRecordwithFilter,
    "aaData" => $data
);

echo json_encode($em_id );
