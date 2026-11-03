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
$date3exp = $_POST['date3exp'];
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
$stmt = $conn->prepare("SELECT COUNT(*) AS allcount FROM manifest");
$stmt->execute();
$records = $stmt->fetch();
$totalRecords = $records['allcount'];

## Total number of records with filtering
$stmt = $conn->prepare("SELECT COUNT(*) AS allcount FROM manifest WHERE 1 ".$searchQuery);
$stmt->execute($searchArray);
$records = $stmt->fetch();
$totalRecordwithFilter = $records['allcount'];

## Fetch records
$stmt = $conn->prepare("SELECT * FROM manifest WHERE trip_date >='$date1_exp' and trip_date <='$date2_exp' and bus = '$date3exp'and 1 ".$searchQuery." ORDER BY ".$columnName." ".$columnSortOrder." LIMIT :limit,:offset");

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
			"bus"=>$row['bus'],
			"code"=>$row['code'],
			"username"=>$row['username'],
			"detail"=>base64_decode($row['detail']),
				"loan"=>number_format($row['loan'],2),
			"tamount"=>number_format($row['loan']+$row['amount'],2),
"left"=>''.$row['q_left'].'/ '.$row['quantity'].'',            
	        
		"date"=>$row['trip_date'],
			"amount"=>number_format($row['amount'],2),
		
             "to_be"=>number_format($row['to_be'],2),

			"paid"=>number_format($row['paid'],2),
				"action"=>'
				<span id="id'.$row['id'].'"style="display:none;">'.$row['id'].'</span>
				<button type="button" class="btn btn-soft-primary"id="check'.$row['id'].'">
          <i class="uil  uil-clipboard-notes "></i>
        </button>
		<div style="display:none;"id="loading'.$row['id'].'">...</div>
		<script>
		$(document).ready(function(){
			
		$("#check'.$row['id'].'").click(function(){
			var id=$("#id'.$row['id'].'").text();
	$("#loading'.$row['id'].'").show();
		  $.ajax({
				url:"check_recieved.php",
				method:"POST",
				data:{id:id},
				success:function(response){
							

		$("#loading'.$row['id'].'").hide();
$("#empTable").DataTable().ajax.reload();
	}
			
			});
	
		});
		});
		</script>
				',	"print"=>'
				<a href="qrcode/print_recieved.php?code='.$row['code'].'&id='.$row['id'].'"><button type="button" class="btn btn-default">
          <span class="glyphicon glyphicon-print"></span>
        </button></a>
				',
				"checked"=>$row['checked'],
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
