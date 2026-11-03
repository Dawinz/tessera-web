<?php
require_once __DIR__ . '/includes/tessera_staff_boot.php';
session_start();
include 'config2.php';
include 'connect.php';
$username=$_SESSION['username'];
$route_array=array();
$select2=mysqli_query($db,"SELECT route_full_name,status  FROM  assigned_routes where username='$username' order by preference desc");
$select_num2=mysqli_num_rows($select2);
if($select_num2 !=0){
   	WHILE($get12=mysqli_fetch_array($select2)){
    	    $route_full_name =$get12['route_full_name'];  
    	    $status =$get12['status'];  
			

   $fullname_man="$route_full_name.$status";

array_push($route_array,$fullname_man);
			
	}
}
$new_array =  implode("$", $route_array);
function options_change($route_name_check,$new_array) {
	$my_array1 = explode("$", $new_array);
	$route_name_check = "$route_name_check.sender";
if (strpos($new_array, $route_name_check) !== false) {
  return "";
} else {
  
	return "disabled";
}
return $new_array;
}
function options_change_vs($route_name_check,$new_array) {
	$my_array1 = explode("$", $new_array);
	$route_name_check = "$route_name_check.sender";
if (strpos($new_array, $route_name_check) !== false) {
return "none";
} else {
  
	return "";
}
return $new_array;
}
function options_change_none($route_name_check,$new_array) {
	$my_array1 = explode("$", $new_array);
	$route_name_check = "$route_name_check.sender";
if (strpos($new_array, $route_name_check) !== false) {
  return "";
} else {
  
	return "none";
}
return $new_array;
}
## Read value
$draw = $_POST['draw'];
$row = $_POST['start'];
$rowperpage = $_POST['length']; // Rows display per page
$columnIndex = $_POST['order'][0]['column']; // Column index
$columnName = $_POST['columns'][$columnIndex]['data']; // Column name
$columnSortOrder = $_POST['order'][0]['dir']; // asc or desc
$searchValue = $_POST['search']['value']; // Search value
$route = $_POST['route'];
$route_name_select = $_POST['route_name_select'];

$start_date = $_POST['start_date'];
$end_date = $_POST['end_date'];
$vehicle = $_POST['vehicle'];
$order_by = "ORDER BY date desc,id desc";
if($start_date!=""){
	$start_date = "date >= '$start_date' and";
	$order_by = "ORDER BY date asc ,id asc";
}
if($end_date!=""){
	$end_date = "date <= '$end_date' and";
	$order_by = "ORDER BY date asc,id asc";
}
if($vehicle!=""){
	$vehicle = "Name = '$vehicle' and";
}else{
	$vehicle ="";
}

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
$stmt = $conn->prepare("SELECT COUNT(*) AS allcount FROM vehicle_name WHERE   $vehicle $start_date $end_date $route_name_select   1 ".$searchQuery);
$stmt->execute($searchArray);
$records = $stmt->fetch();
$totalRecordwithFilter = $records['allcount'];

## Fetch records
$stmt = $conn->prepare("SELECT * FROM vehicle_name WHERE $vehicle $start_date $end_date $route_name_select   1 ".$searchQuery." $order_by LIMIT :limit,:offset");

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
            "code"=>$row['code'],
			"Name"=>$row['Name'],
			"Driver"=>$row['Driver'],
            "Route"=>$row['Route'],
			"Conductor"=>$row['Conductor'],
			"date"=>date("d/m/Y", strtotime($row['date']))  ,
			"status"=>$row['status'],
			"user"=>'<a  href = print_manifest.php?date='.$row['date'].'&date2='.$row['date'].'&name='.$row['Name'].'&open='.$row['status'].'><button type="button" class="btn btn-soft-secondary">
          <i class="uil uil-print"></i>
        </button></a>',
	 "luggage_no"=>$row['luggage_no'],
 
	 "to_pay"=>''.number_format($row['to_pay'],2).'<img src="img/transfer.png"/><br> 
	 '.number_format($row['l_to_be'],2).'',	
	 "paid"=>''.number_format($row['paid'],2).'<img src="img/transfer.png"/> '.number_format($row['l_paid'],2).'',	
	 "total"=>''.number_format($row['paid'] + $row['to_pay'],2).'<img src="img/transfer.png"/><br> '.number_format($row['l_amount'],2).'',	
	"Delete"=>'
	 
	<button id="delete'.$row['id'].'"type="button" class="btn btn-soft-danger" '.options_change($row['Route'],$new_array).' >
      
	<i class="uil uil-times "></i>
        </button><span id="response_d'.$row['id'].'"></span>
		
		<script>
		$("#delete'.$row['id'].'").click(function(){
			$("#response_d'.$row['id'].'").text("deleting...");
			var delete_id  = "'.$row['id'].'";
				  $.ajax({
				url:"delete_vehicle_name.php",
				method:"POST",
				data:{delete_id:delete_id},
				success:function(response){
						
					if($.trim(response) == "found"){
							$("#savebtn2").text("Delete");
						 swal("Found!", "Data Found inside!", "warning");
						 $("#response_d'.$row['id'].'").text("");
						 
                         control.disabled="disabled";
						}else{
$("#empTable").DataTable().ajax.reload();


						}

}
			
			});
		});
		</script>
		
		',	
	"View"=>'<a  href = vehicle_report_new.php?date='.$row['date'].'&date2='.$row['date'].'&date3='.$row['Name'].'&open='.$row['status'].'&route='.base64_encode($row['Route']).'&driver='.base64_encode($row['Driver']).'&Conductor='.base64_encode($row['Conductor']).'&code='.base64_encode($row['code']).'><button type="button" class="btn btn-soft-secondary"><i class="uil uil-eye"></i></button></a>',	
		
		"pdf"=>'<a  href = pdf/index.php?date='.$row['date'].'&date2='.$row['date'].'&date3='.$row['Name'].'&open='.$row['status'].'&driver='.base64_encode($row['Driver']).'&Conductor='.base64_encode($row['Conductor']).'&code='.base64_encode($row['code']).'><button type="button" class="btn btn-soft-secondary"><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-filetype-pdf" viewBox="0 0 16 16">
  <path fill-rule="evenodd" d="M14 4.5V14a2 2 0 0 1-2 2h-1v-1h1a1 1 0 0 0 1-1V4.5h-2A1.5 1.5 0 0 1 9.5 3V1H4a1 1 0 0 0-1 1v9H2V2a2 2 0 0 1 2-2h5.5zM1.6 11.85H0v3.999h.791v-1.342h.803q.43 0 .732-.173.305-.175.463-.474a1.4 1.4 0 0 0 .161-.677q0-.375-.158-.677a1.2 1.2 0 0 0-.46-.477q-.3-.18-.732-.179m.545 1.333a.8.8 0 0 1-.085.38.57.57 0 0 1-.238.241.8.8 0 0 1-.375.082H.788V12.48h.66q.327 0 .512.181.185.183.185.522m1.217-1.333v3.999h1.46q.602 0 .998-.237a1.45 1.45 0 0 0 .595-.689q.196-.45.196-1.084 0-.63-.196-1.075a1.43 1.43 0 0 0-.589-.68q-.396-.234-1.005-.234zm.791.645h.563q.371 0 .609.152a.9.9 0 0 1 .354.454q.118.302.118.753a2.3 2.3 0 0 1-.068.592 1.1 1.1 0 0 1-.196.422.8.8 0 0 1-.334.252 1.3 1.3 0 0 1-.483.082h-.563zm3.743 1.763v1.591h-.79V11.85h2.548v.653H7.896v1.117h1.606v.638z"/>
</svg>
        </button></a>',
		
			"close"=>'<a  href = lock.php?u='.$row['id'].'><button type="button" class="btn btn-soft-danger"><i class="uil uil-lock"></i>
        </button></a>',
			"Insert"=>'<a  aria-current="page" style="pointer-events: '.options_change_none($row['Route'],$new_array).';" href = check_luggages.php?id='.$row['Name'].'&date='.$row['date'].'&open='.$row['status'].' ><button type="button" class="btn btn-soft-secondary"><i class="uil uil-download-alt"></i>
        </button></a>',		
			"edit_manifest"=>'
<button data-bs-toggle="offcanvas" data-bs-target="#offcanvasRight" type="button"id="edit'.$row['id'].'" class="btn btn-soft-dark" '.options_change($row['Route'],$new_array).'><i class="uil uil-pen "
        </button>
		<script>
		$("#edit'.$row['id'].'").click(function(){
			$("#vehicle_option").text("'.$row['Name'].'");
			$("#driver_update").val("'.$row['Driver'].'");
			$("#conductor_update").val("'.$row['Conductor'].'");
			$("#time2_update").val("'.$row['date'].'");
			$("#id_update").text("'.$row['id'].'");
	
			$("#route_update option:selected").text("'.$row['Route'].'");
			$("#route_update option:selected").value("'.$row['Route'].'");

		});
		</script>
		',
		"receipt"=>'<a  href = print_manifest.php?date='.$row['date'].'&date2='.$row['date'].'&name='.$row['Name'].'&open='.$row['status'].'><button type="button" class="btn btn-default">
          <span class="glyphicon glyphicon-print"></span> 
        </button></a>',
		"sms"=>'
		<div style="display:none;"id="bus'.$row['id'].'">'.$row['Name'].'</div>
		<div style="display:none;"id="date'.$row['id'].'">'.$row['date'].'</div>
		<div style="display:none;"id="posting'.$row['id'].'">Sending..</div>
		
		<div style="display:none;"id="id'.$row['id'].'">'.$row['id'].'</div>
		<div style="display:none;"id="Driver'.$row['id'].'">'.$row['Driver'].'</div>
		<div style="display:none;"id="Conductor'.$row['id'].'">'.$row['Conductor'].'</div>
		<div style="display:none;"id="Route'.$row['id'].'">'.$row['Route'].'</div>
		<div style="display:none;"id="luggage_no'.$row['id'].'">'.$row['luggage_no'].'</div>
		<div style="display:none;"id="to_pay'.$row['id'].'">'.$row['to_pay'].'</div>
		<div style="display:none;"id="paid'.$row['id'].'">'.$row['paid'].'</div>
		<div style="display:none;"id="due'.$row['id'].'">'.$row['due'].'</div>
		<div style="display:none;"id="status'.$row['id'].'">'.$row['status'].'</div>
		<div style="display:none;"id="passcode'.$row['id'].'">'.$row['passcode'].'</div>
		<div style="display:none;"id="creaded_by'.$row['id'].'">'.$row['creaded_by'].'</div>
		<div style="display:none;"id="locked_by'.$row['id'].'">'.$row['locked_by'].'</div>
		
		<div style="display:none;"id="l_paid'.$row['id'].'">'.$row['l_paid'].'</div>
		<div style="display:none;"id="l_to_be'.$row['id'].'">'.$row['l_to_be'].'</div>
		<div style="display:none;"id="l_amount'.$row['id'].'">'.$row['l_amount'].'</div>
		<div style="display:none;"id="img'.$row['id'].'">'.$row['img'].'</div>
		
		<div style="display:none;"id="disable'.$row['id'].'">'.$row['disable'].'</div>
		<div style="display:none;"id="disable2'.$row['id'].'">'.$row['disable2'].'</div>
		<div style="display:none;"id="updated'.$row['id'].'">'.$row['updated'].'</div>
		
		
		<button type="button" class="btn btn-soft-secondary"id="insert'.$row['id'].'" '.$row['disable2'].'>
          '.base64_decode($row['img']).'
        </button>
		<script>
		$("#insert'.$row['id'].'").click(function(){
	var bus=$("#bus'.$row['id'].'").text();
	var date=$("#date'.$row['id'].'").text();
	
	$("#posting'.$row['id'].'").show();	
	   $.ajax({
				url:"send_bulk.php",
				method:"POST",
				data:{bus:bus,date:date},
				success:function(response){
					//alert(response);
												  swal({
    title: "Sms Sent",
    text: "Sms Sent.",
    timer: 3000,
    showConfirmButton: false
  });
  

			
$("#posting'.$row['id'].'").hide();	
$("#empTable").DataTable().ajax.reload();

	}
			
			});
			
		});
		</script>
		',
	"check_out"=>'<a style="pointer-events: '.options_change_vs($row['Route'],$new_array).';" href = vehicle_report_new_edit.php?date='.$row['date'].'&date2='.$row['date'].'&date3='.$row['Name'].'&open='.$row['status'].'><button type="button" class="btn btn-soft-secondary"><i class="uil uil-check"></i>
     
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
