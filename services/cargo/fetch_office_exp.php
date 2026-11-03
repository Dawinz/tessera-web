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
	$searchQuery = " AND ( stock_no Like :stock_no  or 
        insert_date_time  LIKE :insert_date_time  OR 
        username LIKE :username OR approved LIKE :approved ) ";
    $searchArray = array( 
	 'stock_no'=>"%$searchValue%",
        'approved'=>"%$searchValue%", 
        'insert_date_time'=>"%$searchValue%",
       
		'username'=>"%$searchValue%"
    );
}

## Total number of records without filtering
$stmt = $conn->prepare("SELECT COUNT(*) AS allcount FROM  expenses");
$stmt->execute();
$records = $stmt->fetch();
$totalRecords = $records['allcount'];

## Total number of records with filtering
$stmt = $conn->prepare("SELECT COUNT(*) AS allcount FROM  expenses WHERE 1 ".$searchQuery);
$stmt->execute($searchArray);
$records = $stmt->fetch();
$totalRecordwithFilter = $records['allcount'];

## Fetch records
$stmt = $conn->prepare("SELECT * FROM  expenses WHERE   1 ".$searchQuery." ORDER BY ".$columnName." ".$columnSortOrder." LIMIT :limit,:offset");

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

			"approved"=>$row['approved'],
			"amount"=>number_format($row['amount'],2),
            "insert_date_time"=>$row['insert_date_time'],
			"username"=>$row['username'],
			 "detail"=>base64_decode($row['detail']),	
	        "stock_no"=>$row['stock_no'],
			"time"=>$row['time'],
			"edit"=>'
			<button type="button" class="btn btn-default"id="delete'.$row['id'].'">
           <span class="glyphicon  glyphicon-trash"></span> 
        </button>
		<div id="id'.$row['id'].'"style="display:none;">'.$row['id'].'</div>
		<div id="x'.$row['id'].'"style="display:none;">Deleted</div>
			        <input style="display:none;"type="type" class="form-control" id="password'.$row['id'].'" placeholder="****" value="" data-type="currency"> <br>
<script>
	$(document).ready(function(){
		$("#delete'.$row['id'].'").click(function(){
			var id=$("#id'.$row['id'].'").text();
			var password=$("#password'.$row['id'].'").val();
			$("#delete'.$row['id'].'").hide();
			$("#password'.$row['id'].'").show();
		$("#button_delete'.$row['id'].'").show();
		
		$("#password'.$row['id'].'").keypress(function (e) {
  if (e.which == 13) {
	  var id=$("#id'.$row['id'].'").text();
	  var code=$("#code'.$row['id'].'").text();
   		var password=$("#password'.$row['id'].'").val();
		 $.ajax({
				url:"delete_office.php",
				method:"POST",
				data:{password:password,id:id},
				success:function(response){

if($.trim(response) == "Deleted"){
		$("#x'.$row['id'].'").show();
	swal("Deleted!", "Entry has been successfull deleted!", "success");
}else{
						swal({
  title: "you may not be an Admin,issuer or Wrong password!",
  text: "Please make sure your are an admin or the person who have issue the Entry!",
  icon: "warning",
});	
}
		
	}
			
			});
    return false;    //<---- Add this line
  }
});
		
		});});
</script>
			',
			
			  

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
