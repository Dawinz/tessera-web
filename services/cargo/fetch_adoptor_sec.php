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
$date1_exp = $_POST['firstday'];



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
$stmt = $conn->prepare("SELECT * FROM parcel_info WHERE  username='salym' and date_insert ='$date1_exp'  and 1 ".$searchQuery." ORDER BY ".$columnName." ".$columnSortOrder." LIMIT :limit,:offset");

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
			"username"=>$row['username'],
			"date_insert"=>$row['date_insert'],
			"detail"=>base64_decode($row['detail']),
            "code"=>$row['code'],
	        "amount"=>number_format($row['amount'],2),
		    "paid"=>number_format($row['paid'],2),
	        "to_be"=>number_format($row['to_be'],2),
			"loan_amount"=>number_format($row['loan']+$row['amount'],2),
			"loan"=>number_format($row['loan'],2),
			"open"=>$row['open'],

					"delete"=>'<button type="button" class="btn btn-soft-danger"id="delete'.$row['id'].'">
       <i class="uil uil-plus"></i>
        </button>
		<div id="id'.$row['id'].'"style="display:none;">'.$row['id'].'</div>


	<script>
	$(document).ready(function(){
		$("#delete'.$row['id'].'").click(function(){
			var id=$("#id'.$row['id'].'").text();
			$("#delete'.$row['id'].'").text("delete...");
			 $.ajax({
				url:"adaptor_change.php",
				method:"POST",
				data:{id:id},
				success:function(response){
	$("#empTable").DataTable().ajax.reload();
		
	}
			
			});
		})
		
	});
	</script>
		',		
		"min"=>'<button type="button" class="btn btn-soft-success"id="delete'.$row['id'].'">
       <i class="uil uil-minus"></i>
        </button>
		<div id="id'.$row['id'].'"style="display:none;">'.$row['id'].'</div>


	
		',

		
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
