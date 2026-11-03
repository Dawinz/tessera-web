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
$user = $_POST['user'];
$route = $_POST['route'];
$searchArray = array();

## Search 
$searchQuery = " ";
if($searchValue != ''){
	$searchQuery = " AND (s_name LIKE :s_name or 
        store  LIKE :store  OR 
        date_insert LIKE :date_insert OR code Like :code OR s_name  Like :s_name ) ";
    $searchArray = array( 
        's_name'=>"%$searchValue%", 
        'store'=>"%$searchValue%",
        'date_insert'=>"%$searchValue%",
		'code'=>"%$searchValue%",
		's_name'=>"%$searchValue%"
    );
}

## Total number of records without filtering
$stmt = $conn->prepare("SELECT COUNT(*) AS allcount FROM parcel_info WHERE " . tessera_sql_tenant_eq() . "");
$stmt->execute();
$records = $stmt->fetch();
$totalRecords = $records['allcount'];

## Total number of records with filtering
$stmt = $conn->prepare("SELECT COUNT(*) AS allcount FROM parcel_info WHERE " . tessera_sql_tenant_eq() . " AND 1 ".$searchQuery);
$stmt->execute($searchArray);
$records = $stmt->fetch();
$totalRecordwithFilter = $records['allcount'];

## Fetch records
$stmt = $conn->prepare("SELECT * FROM parcel_info WHERE " . tessera_sql_tenant_eq() . " AND store !='out' and username='$user'and account='$route' and 1 ".$searchQuery." ORDER BY ".$columnName." ".$columnSortOrder." LIMIT :limit,:offset");

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
            "id"=>'<a target="_blank" href = tempo.php?u='.$row['id'].'>'.$row['id'].'</a>',
			"s_name"=>$row['s_name'],
			"account"=>$row['account'],
			 
            "r_name"=>$row['r_name'],
			"code"=>$row['code'],
			"date"=>$row['date_insert'],
	    "amount"=>''.number_format($row['amount'],2).'('.number_format($row['amount_remain'],2).')',
	 "detail"=>base64_decode($row['detail']),	
	 "status"=>$row['status'],			
			"store"=>'<a target="_blank" href = view.php?u='.$row['id'].'>'.$row['store'].'</a>',
			   "paid"=>number_format($row['paid'],2),
   "to_be"=>number_format($row['to_be'],2), 
   "left"=>''.$row['scanned'].'/ '.$row['question'].'',  
   	"check"=>'',
   			"quantity"=>'<button type="button" class="btn btn-default"id="delete'.$row['id'].'">
           <span class="glyphicon  glyphicon-trash"></span> 
        </button>
		<div id="id'.$row['id'].'"style="display:none;">'.$row['id'].'</div>
		<div id="code'.$row['id'].'"style="display:none;">'.$row['code'].'</div>
		<div id="x'.$row['id'].'"style="display:none;">Deleted</div>
	        <input style="display:none;"type="input" class="form-control" id="password'.$row['id'].'" placeholder="****" value="" data-type="currency"> <br>
			<button style="display:none;"type="button" class="btn btn-default"id="button_delete'.$row['id'].'">
           <span class="glyphicon glyphicon-remove" style="color:#e31507;"></span> 
        </button>


	<script>
	$(document).ready(function(){
		$("#delete'.$row['id'].'").click(function(){
			var id=$("#id'.$row['id'].'").text();
			var password=$("#password'.$row['id'].'").val();
			$("#delete'.$row['id'].'").hide();
			$("#password'.$row['id'].'").show();
			$("#button_delete'.$row['id'].'").show();
			

		});
		$("#button_delete'.$row['id'].'").click(function(){
		  var id=$("#id'.$row['id'].'").text();
	  var code=$("#code'.$row['id'].'").text();
   		var password=$("#password'.$row['id'].'").val();
			 $.ajax({
				url:"delete_store.php",
				method:"POST",
				data:{password:password,id:id,code:code},
				success:function(response){

			if($.trim(response) == "found"){
			$("#password'.$row['id'].'").hide();
			$("#x'.$row['id'].'").show();
			}else if ($.trim(response) == "Operators"){
			swal({
  title: "Not An Admin!",
  text: "Only Admin Can Edit!",
  icon: "success",
});	
			}else if ($.trim(response) == "not found"){
			swal("Password is Incorrect!");		
			}else if ($.trim(response) == "manifest"){
					swal({
  title: "Inside Manifest!",
  text: "Found In the Manifest , you cant Delete it!",
  icon: "warning",
});	
			}
		
	}
			
			});
    return false;    //<---- Add this line

		});
		
$("#password'.$row['id'].'").keypress(function (e) {
  if (e.which == 13) {
	  var id=$("#id'.$row['id'].'").text();
	  var code=$("#code'.$row['id'].'").text();
   		var password=$("#password'.$row['id'].'").val();
			 $.ajax({
				url:"delete_store.php",
				method:"POST",
				data:{password:password,id:id,code:code},
				success:function(response){

			if($.trim(response) == "found"){
			$("#password'.$row['id'].'").hide();
			$("#x'.$row['id'].'").show();
			}else if ($.trim(response) == "Operators"){
			swal({
  title: "Not An Admin!",
  text: "Only Admin Can Edit!",
  icon: "success",
});	
			}else if ($.trim(response) == "not found"){
			swal("Password is Incorrect!");		
			}else if ($.trim(response) == "manifest"){
					swal({
  title: "Inside Manifest!",
  text: "Found In the Manifest , you cant Delete it!",
  icon: "warning",
});	
			}
		
	}
			
			});
    return false;    //<---- Add this line
  }
});
	});
	</script>
		',	
	"check"=>'',
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
