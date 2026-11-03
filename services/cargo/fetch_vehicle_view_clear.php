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
$route = $_POST['route'];
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
$stmt = $conn->prepare("SELECT * FROM vehicle_name WHERE route='$route' and   1 ".$searchQuery." ORDER BY ".$columnName." ".$columnSortOrder." LIMIT :limit,:offset");

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
			"Name"=>$row['Name'],
			"Driver"=>$row['Driver'],
            "Route"=>$row['Route'],
			"Conductor"=>$row['Conductor'],
			"date"=>$row['date'],
			"status"=>$row['status'],
	 "luggage_no"=>$row['luggage_no'],
 
	 "to_pay"=> (function($p,$s){ $h='<div class="sr-amt"><span class="sr-amt-main">'.number_format((float)$p).'</span>'; if (abs((float)$s)>0.00001) { $h.='<span class="sr-amt-sub text-muted">'.number_format((float)$s).'</span>'; } return $h.'</div>'; })($row['to_pay'],$row['l_to_be']),
	 "paid"=> (function($p,$s){ $h='<div class="sr-amt"><span class="sr-amt-main">'.number_format((float)$p).'</span>'; if (abs((float)$s)>0.00001) { $h.='<span class="sr-amt-sub text-muted">'.number_format((float)$s).'</span>'; } return $h.'</div>'; })($row['paid'],$row['l_paid']),
	 "total"=> (function($p,$s){ $h='<div class="sr-amt"><span class="sr-amt-main">'.number_format((float)$p).'</span>'; if (abs((float)$s)>0.00001) { $h.='<span class="sr-amt-sub text-muted">'.number_format((float)$s).'</span>'; } return $h.'</div>'; })((float)$row['paid']+(float)$row['to_pay'],$row['l_amount']),
	"Delete"=>'<a target="_blank" href = delete_view.php?u='.$row['id'].'><button type="button" class="btn btn-outline-danger">
          <span class="glyphicon glyphicon-remove"style="color:#e60000;"></span> 
        </button></a>',	
	"View"=>'<a href = vehicle_report_new_edit.php?date='.$row['date'].'&date2='.$row['date'].'&date3='.$row['Name'].'&open='.$row['status'].'><button type="button" class="btn btn-soft-secondary"><i class="uil uil-eye"></i>
     
        </button></a>',
		
			"close"=>'<a  href = lock.php?u='.$row['id'].'><button type="button" class="btn btn-default" >
          <span class="glyphicon glyphicon-lock"></span> 
        </button></a>',
			"Insert"=>'<a  href = check_luggages.php?id='.$row['Name'].'&date='.$row['date'].'&open='.$row['status'].' ><button type="button" class="btn btn-default">
          <span class="	glyphicon glyphicon-share-alt"></span> 
        </button></a>',		
			"edit_manifest"=>'<a  href = edit_vehicle_list_man.php?date='.$row['date'].'&date2='.$row['date'].'&date3='.$row['Name'].'&open='.$row['status'].'><button type="button" class="btn btn-default">
           <span class="glyphicon glyphicon-edit"></span> 
        </button></a>',
		"expenses"=>'<a  href = man_exp_view.php?date='.$row['date'].'&date2='.$row['date'].'&date3='.$row['Name'].'&open='.$row['status'].'><button type="button" class="btn btn-default">
          <span class="glyphicon glyphicon-usd"></span> 
        </button></a>',
		"sms"=>'
		<div style="display:none;"id="bus'.$row['id'].'">'.$row['Name'].'</div>
		<div style="display:none;"id="date'.$row['id'].'">'.$row['date'].'</div>
		<div style="display:none;"id="posting'.$row['id'].'">Sending..</div>
		<button type="button" class="btn btn-soft-info"id="insert'.$row['id'].'" '.$row['disable'].'>
        '.base64_decode($row['img']).'
        </button>
		<script>
		$("#insert'.$row['id'].'").click(function(){
	var bus=$("#bus'.$row['id'].'").text();
	var date=$("#date'.$row['id'].'").text();
	$("#posting'.$row['id'].'").show();	
	   $.ajax({
				url:"send_bulk_reciver.php",
				method:"POST",
				data:{bus:bus,date:date},
				success:function(response){
												  swal({
													  
    title: "Succefull",
    text: "Whatsapp sms sent.",
    timer: 3000,
    showConfirmButton: false
  });
  			 if (navigator.onLine) {

			
			} else {

}
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
    "iTotalRecords" => $totalRecords,
    "iTotalDisplayRecords" => $totalRecordwithFilter,
    "aaData" => $data
);

echo json_encode($response);
