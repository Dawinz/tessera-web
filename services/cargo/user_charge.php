<?php
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

$start_month = $_POST['start_month'];
$end_month = $_POST['end_month'];

$searchArray = array();

## Search 
$searchQuery = " ";
if($searchValue != ''){
	$searchQuery = " AND (s_name LIKE :s_name or 
        r_name  LIKE :r_name  OR 
        code LIKE :code OR 
        status LIKE :status ) ";
    $searchArray = array( 
        's_name'=>"%$searchValue%", 
        'r_name'=>"%$searchValue%",
        'code'=>"%$searchValue%",
		'status'=>"%$searchValue%"
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
$stmt = $conn->prepare("SELECT * FROM parcel_info WHERE username='$date3exp'and date_insert >=' $date1_exp' and month='$start_month' and date_insert <=' $date2_exp' and month='$end_month' and 1 ".$searchQuery." ORDER BY ".$columnName." ".$columnSortOrder." LIMIT :limit,:offset");

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
            "r_name"=>$row['r_name'],
			"status"=>$row['status'],
			"account"=>$row['account'],
			"date_insert"=>$row['date_insert'],
			"detail"=>base64_decode($row['detail']),
            "code"=>$row['code'],
	        "amount"=>number_format($row['amount'],2),
		    "paid"=>number_format($row['paid'],2),
	        "to_be"=>number_format($row['to_be'],2),

		
			"question"=>''.$row['scanned'].'/'.$row['question'].''
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
