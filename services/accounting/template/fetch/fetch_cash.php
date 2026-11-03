<?php
include 'config2.php';
## Read value
$draw = isset($_POST['draw']) ? (int) $_POST['draw'] : 0;
$row = isset($_POST['start']) ? (int) $_POST['start'] : 0;
$rowperpage = isset($_POST['length']) ? (int) $_POST['length'] : 20;
if ($rowperpage < 1) {
	$rowperpage = 20;
}
$searchValue = isset($_POST['search']['value']) ? $_POST['search']['value'] : '';
$my_id = isset($_POST['my_id']) ? (string) $_POST['my_id'] : '';
/* Only allow a tight route filter fragment from the UI buttons */
if ($my_id !== '' && !preg_match("/^route_name='[^']*' and\$/", $my_id)) {
	$my_id = '';
}
$searchArray = array();

## Search 
$searchQuery = " ";
if($searchValue != ''){
	$searchQuery = " AND (date LIKE :date or 
        issuer  LIKE :issuer  

		) ";
    $searchArray = array( 
        'issuer'=>"%$searchValue%",  
		'date'=>"%$searchValue%", 
   
		
    );
}

$tenant = tessera_sql_tenant_eq();

## Total number of records without filtering
$stmt = $conn->prepare("SELECT COUNT(*) AS allcount FROM  cash WHERE " . $tenant);
$stmt->execute();
$records = $stmt->fetch();
$totalRecords = $records['allcount'];

## Total number of records with filtering
$stmt = $conn->prepare("SELECT COUNT(*) AS allcount FROM  cash   WHERE " . $tenant . " AND ".$my_id." 1  ".$searchQuery);
$stmt->execute($searchArray);
$records = $stmt->fetch();
$totalRecordwithFilter = $records['allcount'];

## Fetch records
$stmt = $conn->prepare("SELECT * FROM  cash    WHERE " . $tenant . " AND ".$my_id." 1  ".$searchQuery." ORDER BY open_date  DESC LIMIT :limit,:offset");

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
          
		
			"open_date"=>date("l jS/m/Y", strtotime($row['open_date'])),
			"account"=>$row['account'],
			"authorizer"=>$row['authorizer'],
			"date"=>$row['date'],
			"issuer"=>$row['issuer'],
			"id"=>$row['id'],
			"opening_stock"=>number_format($row['opening_stock'],2),
			"amount_remain"=>number_format($row['amount_remain'],2),
			
		
	
   );
}

## account 
$response  = array(
    "draw" => intval($draw),
    "recordsTotal" => intval($totalRecords),
    "recordsFiltered" => intval($totalRecordwithFilter),
    "data" => $data,
    "iTotalRecords" => intval($totalRecords),
    "iTotalDisplayRecords" => intval($totalRecordwithFilter),
    "aaData" => $data
);
header('Content-Type: application/json; charset=utf-8');

echo json_encode($response);
