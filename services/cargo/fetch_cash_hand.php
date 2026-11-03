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
	$searchQuery = " AND (stock_no LIKE :stock_no or 
        date  LIKE :date  OR 
        opening_stock LIKE :opening_stock OR authorizer Like :authorizer ) ";
    $searchArray = array( 
        'stock_no'=>"%$searchValue%", 
        'date'=>"%$searchValue%",
        'opening_stock'=>"%$searchValue%",
		'authorizer'=>"%$searchValue%"
    );
}

## Total number of records without filtering
$stmt = $conn->prepare("SELECT COUNT(*) AS allcount FROM office_expenses");
$stmt->execute();
$records = $stmt->fetch();
$totalRecords = $records['allcount'];

## Total number of records with filtering
$stmt = $conn->prepare("SELECT COUNT(*) AS allcount FROM office_expenses WHERE 1 ".$searchQuery);
$stmt->execute($searchArray);
$records = $stmt->fetch();
$totalRecordwithFilter = $records['allcount'];

## Fetch records
$stmt = $conn->prepare("SELECT * FROM office_expenses WHERE   1 ".$searchQuery." ORDER BY ".$columnName." ".$columnSortOrder." LIMIT :limit,:offset");

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
			"authorizer"=>$row['authorizer'],
			"open_date"=>$row['open_date'],
            "date"=>$row['date'],
			 "opening_stock"=>number_format($row['opening_stock'],2),	
			 "amount_remain"=>number_format($row['amount_remain'],2),	
			"status"=>$row['status'],
	       "issuer"=>$row['issuer'],
	         "stock_no"=>$row['stock_no'],
			 "time"=>$row['time'],
			


	"View"=>'<a target="_blank" href = expenses_view.php?id='.$row['stock_no'].'><button type="button" class="btn btn-default">
          <span class="glyphicon glyphicon-eye-open"></span> 
        </button></a>',
	
		
			"Insert"=>'<a target="_blank" href = expenses.php?id='.$row['id'].'><button type="button" class="btn btn-default">
          <span class="	glyphicon glyphicon-share-alt"></span> 
        </button></a>',
		"end"=>'<a target="_blank" href = edit_open.php?id='.$row['id'].'><button type="button" class="btn btn-default">
          <span class="glyphicon glyphicon-pencil"></span> 
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
