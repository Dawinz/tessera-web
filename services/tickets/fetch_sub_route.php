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
	$searchQuery = " AND (start_route LIKE :start_route or 
        end_route  LIKE :end_route  OR 
        fare_price LIKE :fare_price OR 
        name LIKE :name   ) ";
    $searchArray = array( 
        'start_route'=>"%$searchValue%", 
        'end_route'=>"%$searchValue%",
        'fare_price'=>"%$searchValue%",
		'name'=>"%$searchValue%",
		
		
    );
}

## Total number of records without filtering
$stmt = $conn->prepare("SELECT COUNT(*) AS allcount FROM sub_route");
$stmt->execute();
$records = $stmt->fetch();
$totalRecords = $records['allcount'];

## Total number of records with filtering
$stmt = $conn->prepare("SELECT COUNT(*) AS allcount FROM sub_route WHERE   1  ".$searchQuery);
$stmt->execute($searchArray);
$records = $stmt->fetch();
$totalRecordwithFilter = $records['allcount'];

## Fetch records
$stmt = $conn->prepare("SELECT * FROM sub_route  WHERE   1  ".$searchQuery." ORDER BY ".$columnName." ".$columnSortOrder." LIMIT :limit,:offset");

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
          
			"start_route"=>$row['start_route'],
			"id"=>$row['id'],
			"end_route"=>$row['end_route'],
			"a_time"=>$row['a_time'],
			"name"=>$row['name'],
			"d_time"=>$row['d_time'],	
			"status"=>$row['status'],
			"currency"=>$row['currency'],
			"route_name"=>$row['route_name'],
			"active_day"=>$row['active_day'],	
			"detail"=>base64_decode($row['detail']),	 
 "fare_price"=>''.number_format($row['fare_price'],2).' '. $row['currency_symbol'].'', 			
	

			"delete"=>'
			<span style="display:none;"id="id'.$row['id'].'">'.$row['id'].'</span>
			<button type="button" class="btn btn-soft-danger"id="delete'.$row['id'].'">
                       <i class="uil uil-ban"></i> </button>
						<script>
						$(document).ready(function(){
						$("#delete'.$row['id'].'").click(function(){
							var id = $("#id'.$row['id'].'").text();
							$("#delete'.$row['id'].'").text("deleting...");
											$.ajax({
				url:"delete_sub_route.php",
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
$("#r_update").text("'.$row['route_name'].'");
$("#s_update").text("'.$row['start_route'].'");
$("#e_update").text("'.$row['end_route'].'");
$("#id_no").text("'.$row['id'].'");

$("#fair_update").val("'.$row['fare_price'].'");
$("#initial_update").val("'.$row['name'].'");
$("#a_aft_update").val("'.$row['active_day'].'");
$("#a_time_update").val("'.$row['a_time'].'");
$("#d_ptime_upadate").val("'.$row['d_time'].'");
$("#details_update").val("'.base64_decode($row['detail']).'");
	$("#update_currecny").text("'.$row['currency'].'");

	
			
			});	
		
			});
			</script>
						',
	
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
