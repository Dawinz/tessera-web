<?php
require_once __DIR__ . '/includes/tessera_staff_boot.php';
include 'config2.php';
## Read value
$draw = isset($_POST['draw']) ? (int)$_POST['draw'] : 0;
$row = isset($_POST['start']) ? (int)$_POST['start'] : 0;
$rowperpage = isset($_POST['length']) ? (int)$_POST['length'] : 10;
if ($rowperpage < 1) { $rowperpage = 10; }
$columnIndex = isset($_POST['order'][0]['column']) ? (int)$_POST['order'][0]['column'] : 0;
$columnName = 'id';
if (isset($_POST['columns'][$columnIndex]['data']) && $_POST['columns'][$columnIndex]['data'] !== '') {
	$columnName = preg_replace('/[^a-zA-Z0-9_]/', '', (string)$_POST['columns'][$columnIndex]['data']);
	if ($columnName === '') { $columnName = 'id'; }
}
$columnSortOrder = (isset($_POST['order'][0]['dir']) && strtolower($_POST['order'][0]['dir']) === 'asc') ? 'ASC' : 'DESC';
$searchValue = isset($_POST['search']['value']) ? $_POST['search']['value'] : '';
$day = $_POST['day'];
$route_name = $_POST['route_name'];
$vehicle = $_POST['vehicle'];
$status = $_POST['status'];
$searchArray = array();

## Search 
$searchQuery = " ";
if($searchValue != ''){
	$searchQuery = " AND (bus LIKE :bus or 
        day  LIKE :day  OR 
        arrival LIKE :arrival OR departure Like :departure
		OR route_name Like :route_name  ) ";
    $searchArray = array( 
        'day'=>"%$searchValue%",  
		'bus'=>"%$searchValue%", 
        'arrival'=>"%$searchValue%",
		'departure'=>"%$searchValue%",
		'route_name'=>"%$searchValue%"
		
    );
}

$tenantSql = tessera_sql_tenant_eq();

## Total number of records without filtering
$stmt = $conn->prepare("SELECT COUNT(*) AS allcount FROM route_day WHERE ".$tenantSql);
$stmt->execute();
$records = $stmt->fetch();
$totalRecords = $records['allcount'];

## Total number of records with filtering
$stmt = $conn->prepare("SELECT COUNT(*) AS allcount FROM route_day WHERE ".$tenantSql." AND $day $route_name $vehicle $status  1  ".$searchQuery);
$stmt->execute($searchArray);
$records = $stmt->fetch();
$totalRecordwithFilter = $records['allcount'];

## Fetch records
$stmt = $conn->prepare("SELECT * FROM route_day  WHERE ".$tenantSql." AND $day $route_name $vehicle $status  1  ".$searchQuery." ORDER BY ".$columnName." ".$columnSortOrder." LIMIT :limit,:offset");

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
          
			"day"=>$row['day'],
			"id"=>$row['id'],
			"bus"=>$row['bus'],
			"arrival"=>$row['arrival'],
			"route_name"=>$row['route_name'],
			"departure"=>$row['departure'],
			"status"=>$row['status'],

			"reporting_time"=>$row['reporting_time'],
			"detail"=>base64_decode($row['detail']),	 
         
				"status"=>'
		<button id="change'.$row['id'].'" type="button" class="'.$row['button_class'].'"><i class="'.$row['button_icon'].'"></i>'.$row['button_name'].'</button>
	<script>
						$(document).ready(function(){
						$("#change'.$row['id'].'").click(function(){
							var id = $("#id'.$row['id'].'").text();
						

							$("#change'.$row['id'].'").text("changing...");
											$.ajax({
				url:"change_trip_status.php",
				method:"POST",
				data:{id:id},
				success:function(response){
		
	$("#example").DataTable().ajax.reload();



}
			
			});
						});
						});
						</script>
			',
	

			"delete"=>'
			<span style="display:none;"id="id'.$row['id'].'">'.$row['id'].'</span>
			<button type="button" class="btn btn-soft-danger"id="delete'.$row['id'].'">
                       <i class="uil uil-ban"></i> </button>
						<script>
						$(document).ready(function(){
						$("#delete'.$row['id'].'").click(function(){
							var id = $("#id'.$row['id'].'").text();
							$("#delete'.$row['id'].'").text("deleting...");

Swal.fire({
  title: "Are you sure?",
  text: "You wont be able to revert this!",
  icon: "warning",
  showCancelButton: true,
  confirmButtonColor: "#3085d6",
  cancelButtonColor: "#d33",
  confirmButtonText: "Yes, delete it!"
}).then((result) => {
  if (result.isConfirmed) {
  
											$.ajax({
				url:"delete_trip.php",
				method:"POST",
				data:{id:id},
				success:function(response){
										$("#example").DataTable().ajax.reload();

					if($.trim(response) == "cant"){
													  swal({
    title: "Entries found",
    text: "you cannot delete.",
    timer: 1000,
    showConfirmButton: false
  });
					}else{
						
					}
					

	$("#example").DataTable().ajax.reload();

}
			
			});
  }
});

						});
						});
						</script>
						',
										"update"=>'
			<span style="display:none;"id="id'.$row['id'].'">'.$row['id'].'</span>
			<button data-bs-toggle="offcanvas" data-bs-target="#offcanvasRight" aria-controls="offcanvasRight"type="button" class="btn btn-soft-light"id="update'.$row['id'].'">
                       <i class="uil uil-pen"></i> </button>
		   <div style="display:none;"id="check'.$row['id'].'">'.$row['status'].'</div>
		 <div style="display:none;"id="check_update'.$row['id'].'">'.$row['status'].'</div>

			<script>
			$(document).ready(function(){
			$("#update'.$row['id'].'").click(function(){
			$("#rname").text("'.$row['route_name'].'");
			$("#tday").text("'.$row['day'].'");
			$("#id_no").text("'.$row['id'].'");
			$("#v_name").text("'.$row['bus'].'");
			$("#bus_prev").text("'.$row['bus'].'");
			$("#a_time_update").val("'.$row['arrival'].'");
			$("#d_ptime_update").val("'.$row['departure'].'");
			$("#detail_update").val("'.base64_decode($row['detail']).'");
			var checked = $("#check_update'.$row['id'].'").text();	
	if(checked=="active"){
						$("#check_update").prop("checked", true);
					}else{
						$("#check_update").prop("checked", false);
					}
			
			});	
		
			});
			</script>
						',
										"time"=>'
			<span  style="display:none;"id="id'.$row['id'].'">'.$row['id'].'</span>
			<button  type="button" data-bs-toggle="modal"data-bs-target="#trip_time_manullay" class="btn btn-soft-warning"id="clock'.$row['id'].'">
                       <i class="uil uil-clock"></i> </button>
<script>
$("#clock'.$row['id'].'").click(function(){
$(".title_header").text("'.$row['bus'].' ('.$row['day'].') - departure time");
id="'.$row['id'].'";
route_id="'.$row['route_id'].'";
			$.ajax({
				url:"search/route_time.php",
				method:"POST",
				
				beforeSend:function()
	    		{
				
 $("#load_manually_time").html("<div class=`placeholder-glow` ><span class=`placeholder col-12`style=`height:300px`></span></div>");		
	    		},
				data:{id:id,route_id:route_id},
				success:function(response){
				
		$("#load_manually_time").html(response);
}
			
			});

	
	
});	
</script>
',
										"arrival"=>'
			<span  style="display:none;"id="id'.$row['id'].'">'.$row['id'].'</span>
			<button  type="button" data-bs-toggle="modal"data-bs-target="#trip_time_manullay" class="btn btn-soft-warning"id="clock_arrival'.$row['id'].'">
                       <i class="uil uil-clock"></i> </button>
<script>
$("#clock_arrival'.$row['id'].'").click(function(){
id="'.$row['id'].'";
route_id="'.$row['route_id'].'";
$(".title_header").text("'.$row['bus'].' ('.$row['day'].') - departure time");
			$.ajax({
				url:"search/route_time_arrival.php",
				method:"POST",
				
				beforeSend:function()
	    		{
				
 $("#load_manually_time").html("<div class=`placeholder-glow` ><span class=`placeholder col-12`style=`height:300px`></span></div>");		
	    		},
				data:{id:id,route_id:route_id},
				success:function(response){
				
		$("#load_manually_time").html(response);
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
