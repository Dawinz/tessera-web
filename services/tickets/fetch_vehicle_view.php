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
$stmt = $conn->prepare("SELECT COUNT(*) AS allcount FROM vehicle_name WHERE 1 ".$searchQuery);
$stmt->execute($searchArray);
$records = $stmt->fetch();
$totalRecordwithFilter = $records['allcount'];

## Fetch records
$stmt = $conn->prepare("SELECT * FROM vehicle_name WHERE   1 ".$searchQuery." ORDER BY ".$columnName." ".$columnSortOrder." LIMIT :limit,:offset");

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
			"Name"=>$row['Name'],
			"Driver"=>$row['Driver'],
            "Route"=>$row['Route'],
			"Conductor"=>$row['Conductor'],
			"date"=>$row['date'],
			"status"=>$row['status'],
	 "luggage_no"=>$row['luggage_no'],
 
	 "to_pay"=>''.number_format($row['to_pay'],2).'<img src="img/transfer.png"/><br> 
	 '.number_format($row['l_to_be'],2).'',	
	 "paid"=>''.number_format($row['paid'],2).'<img src="img/transfer.png"/> '.number_format($row['l_paid'],2).'',	
	 "total"=>''.number_format($row['paid'] + $row['to_pay'],2).'<img src="img/transfer.png"/><br> '.number_format($row['l_amount'],2).'',	
	"Delete"=>'<a  href = delete_view.php?u='.$row['id'].'><button type="button" class="btn btn-outline-danger">
          <span class="glyphicon glyphicon-remove"style="color:#e60000;"></span> 
        </button></a>',	
	"View"=>'<a  href = vehicle_report_new.php?date='.$row['date'].'&date2='.$row['date'].'&date3='.$row['Name'].'&open='.$row['status'].'><button type="button" class="btn btn-default">
          <span class="glyphicon glyphicon-eye-open"></span> 
        </button></a>',
		
			"close"=>'<a  href = lock.php?u='.$row['id'].'><button type="button" class="btn btn-default">
          <span class="glyphicon glyphicon-lock"></span> 
        </button></a>',
			"Insert"=>'<a  href = check_luggages.php?id='.$row['Name'].'&date='.$row['date'].'&open='.$row['status'].' ><button type="button" class="btn btn-default">
          <span class="	glyphicon glyphicon-share-alt"></span> 
        </button></a>',		
			"edit_manifest"=>'
<button type="button" class="btn btn-default"id="edit'.$row['id'].'">
           <span class="glyphicon glyphicon-edit"></span> 
        </button>
		<script>
		$("#edit'.$row['id'].'").ciick(function(){
			alert();
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
		
		
		<button type="button" class="btn btn-default"id="insert'.$row['id'].'" '.$row['disable2'].'>
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
												  swal({
    title: "Sms Sent",
    text: "Data Succesfull Insert.",
    timer: 3000,
    showConfirmButton: false
  });
  
     var id=$("#id'.$row['id'].'").text();
	var Name=$("#bus'.$row['id'].'").text();
    var date=$("#date'.$row['id'].'").text();
	var Driver=$("#Driver'.$row['id'].'").text();
	var Conductor=$("#Conductor'.$row['id'].'").text();
	var Route=$("#Route'.$row['id'].'").text();
	var luggage_no=$("#luggage_no'.$row['id'].'").text();
	var to_pay=$("#to_pay'.$row['id'].'").text();
	var paid=$("#paid'.$row['id'].'").text();
	var due=$("#due'.$row['id'].'").text();
	var status=$("#status'.$row['id'].'").text();
	var passcode=$("#passcode'.$row['id'].'").text();
	var creaded_by=$("#creaded_by'.$row['id'].'").text();
	var locked_by=$("#locked_by'.$row['id'].'").text();
	var l_paid=$("#l_paid'.$row['id'].'").text();
	var l_to_be=$("#l_to_be'.$row['id'].'").text();
	var l_amount=$("#l_amount'.$row['id'].'").text();
	var disable=$("#disable'.$row['id'].'").text();
	var disable2=$("#disable2'.$row['id'].'").text();
	var updated=$("#updated'.$row['id'].'").text();
	var img=$("#img'.$row['id'].'").text();
  	  $.ajax({
				url:"https://www.srclassiccoach.co.tz/insert_vehicle_back_up.php",
				method:"POST",
				contentType: "application/x-www-form-urlencoded",
				data:{id:id,Name:Name,Driver:Driver,Conductor:Conductor,Route:Route,
				luggage_no:luggage_no,to_pay:to_pay,paid:paid,due:due,status:status,
				date:date,passcode:passcode,creaded_by:creaded_by,locked_by:locked_by,l_paid:l_paid,l_to_be:l_to_be,l_amount:l_amount,
				img:img,disable:disable,disable2:disable2,updated:updated},
				success:function(response){

}
			
			});
				  $.ajax({
				url:"update_vehicle_code_backup.php",
				method:"POST",
				contentType: "application/x-www-form-urlencoded",
				data:{Name:Name,date:date},
				success:function(response){


}
			
			});
			
$("#posting'.$row['id'].'").hide();	
$("#empTable").DataTable().ajax.reload();

	}
			
			});
			
		});
		</script>
		'
        );
}

## account 
$response  = array(
    "draw" => intval($draw),
    "recordsTotal" => intval($totalRecords),
    "recordsFiltered" => intval($totalRecordwithFilter),
    "data" => $data,
    // legacy DataTables keys
    "iTotalRecords" => intval($totalRecords),
    "iTotalDisplayRecords" => intval($totalRecordwithFilter),
    "aaData" => $data
);

header('Content-Type: application/json; charset=utf-8');
echo json_encode($response);
