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
$route_name = isset($_POST['route_name']) ? (string) $_POST['route_name'] : '';
$status = isset($_POST['status']) ? (string) $_POST['status'] : '';
$searchArray = array();

## Search 
$searchQuery = " ";
if($searchValue != ''){
	$searchQuery = " AND (start_route LIKE :start_route OR end_route LIKE :end_route OR route_name LIKE :route_name OR fullname LIKE :fullname) ";
	$searchArray = array(
		'end_route' => "%$searchValue%",
		'start_route' => "%$searchValue%",
		'route_name' => "%$searchValue%",
		'fullname' => "%$searchValue%",
	);
}

$tenantSql = tessera_sql_tenant_eq();

## Total number of records without filtering
$stmt = $conn->prepare("SELECT COUNT(*) AS allcount FROM routes WHERE ".$tenantSql);
$stmt->execute();
$records = $stmt->fetch();
$totalRecords = $records['allcount'];

## Total number of records with filtering
$stmt = $conn->prepare("SELECT COUNT(*) AS allcount FROM routes WHERE ".$tenantSql." AND 1  ".$searchQuery);
$stmt->execute($searchArray);
$records = $stmt->fetch();
$totalRecordwithFilter = $records['allcount'];

## Fetch records
$stmt = $conn->prepare("SELECT * FROM routes  WHERE ".$tenantSql." AND $route_name $status 1  ".$searchQuery." ORDER BY id asc LIMIT :limit,:offset");

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
			"start_route"=>$row['start_route'],
			"end_route"=>$row['end_route'],	
			"currency"=>$row['currency'],
			"active_date"=>$row['active_date'],	 
            "fare_price"=>''.number_format($row['fare_price'],2).' '. $row['currency_symbol'].'', 
			"route_name"=>$row['route_name'],
			"transit"=>$row['transit'],
			"route_id_fullname"=>$row['route_id_fullname'],
			"fullname"=>$row['fullname'],
			"badge"=>$row['badge'],
			"detail"=>base64_decode($row['detail']),
	

			"delete"=>'
			<span style="display:none;"id="id'.$row['id'].'">'.$row['id'].'</span>
			<button type="button" class="btn btn-soft-danger"id="delete'.$row['id'].'">
                       <i class="uil uil-ban"></i> </button>
						<script>
						$(document).ready(function(){
						$("#delete'.$row['id'].'").click(function(){
							var id = $("#id'.$row['id'].'").text();
							var route_id = "'.$row['route_id'].'";
							$("#delete'.$row['id'].'").text("deleting...");
											$.ajax({
				url:"delete_route.php",
				method:"POST",
				data:{id:id,route_id:route_id},
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
			<button data-bs-toggle="offcanvas" data-bs-target="#offcanvasWithBothOptions" aria-controls="offcanvasWithBothOptions"type="button" class="btn btn-soft-light"id="update'.$row['id'].'">
                       <i class="uil uil-pen"></i> </button>


			<script>
			$(document).ready(function(){
			$("#update'.$row['id'].'").click(function(){
$("#id_no").text("'.$row['id'].'");
$("#s_update").text("'.$row['start_route'].'");	
$("#e_update").text("'.$row['end_route'].'");
$("#fair_update").val("'.$row['fare_price'].'");
	$("#details_update").val("'.base64_decode($row['detail']).'");
	$("#initial_update").val("'.$row['route_name'].'");
	$("#active_day_update").val("'.$row['active_date'].'");
	$("#update_currecny").text("'.$row['currency'].'");
	$("#contact1_update").val("'.$row['contact_no1'].'");
	$("#contact2_update").val("'.$row['contact_no2'].'");
			});	
		
			});
			</script>
						',
	"contact"=>'
			<span style="display:none;"id="id'.$row['id'].'">'.$row['id'].'</span>
			<button data-bs-toggle="modal"data-bs-target="#route_contact"  type="button" class="btn btn-soft-warning"id="add_contact'.$row['id'].'">
                       <i class="uil uil-plus"></i> </button>


			<script>
			$(document).ready(function(){
			$("#add_contact'.$row['id'].'").click(function(){

	$("#route_name_cont").val("'.$row['start_route'].' to '.$row['end_route'].'");
	$("#route_name_id").val("'.$row['route_id'].'");
	$("#route_cnt").text("'.$row['start_route'].' to '.$row['end_route'].'");

route_cnt_this = "'.$row['start_route'].' to '.$row['end_route'].'";
	$.ajax({
				url:"search/contact_table.php",
				method:"POST",
				data:{route_cnt_this:route_cnt_this},
				success:function(response){
$("#contact_table").html(response);
}
			
			});

			});	
		
			});
			</script>
						',
						"transit"=>'
			<span style="display:none;"id="id'.$row['id'].'">'.$row['id'].'</span>
			<button data-bs-toggle="modal"data-bs-target="#route_transit"  type="button" class="btn btn-soft-success"id="add_transit'.$row['id'].'">
                       <i class="uil uil-rotate-360"></i> </button>
				<script>
			$(document).ready(function(){	
$("#add_transit'.$row['id'].'").click(function(){			
	$("#route_name_cont_this").val("'.$row['start_route'].' to '.$row['end_route'].'")
	$("#route_name_id_this").val("'.$row['route_id'].'");
	$("#route_cnt_this").text("'.$row['start_route'].' to '.$row['end_route'].'");
route_cnt_this = "'.$row['start_route'].' to '.$row['end_route'].'";
							$.ajax({
				url:"search/transit_table.php",
				method:"POST",
				data:{route_cnt_this:route_cnt_this},
				success:function(response){
$("#transit_single_table").html(response);
}
			
			});				

		});
		});
			</script>
						',
						"terms"=>'
			<span style="display:none;"id="id'.$row['id'].'">'.$row['id'].'</span>
			<button data-bs-toggle="modal"data-bs-target="#route_term"  type="button" class="btn btn-soft-info"id="add_terms'.$row['id'].'">
                       <i class="uil uil-clipboard-notes"></i> </button>
				<script>
			$(document).ready(function(){	
$("#add_terms'.$row['id'].'").click(function(){			
$("#this_route_new").val("'.$row['start_route'].' to '.$row['end_route'].'");

var route_name ="'.$row['start_route'].' to '.$row['end_route'].'";
var route_name = route_name.replace(/ /g,"_");
editor2 = "'.$row['terms'].'";

$("#route_term_load").load("modal/textarea.php?route_name="+route_name);
		});
		});
			</script>
						',	
						"code"=>'
			<span style="display:none;"id="id'.$row['id'].'">'.$row['id'].'</span>
			<button data-bs-toggle="modal"data-bs-target="#route_country_code"  type="button" style="color:white;" class="btn btn-soft-secondary "id="code'.$row['id'].'">
                       <i class="uil uil-phone"></i> </button>
				<script>
			$(document).ready(function(){	
$("#code'.$row['id'].'").click(function(){			
$("#code_route_name_id").val("'.$row['route_id'].'");
$("#route_cnt_code").text(" Country Code - '.$row['start_route'].' to '.$row['end_route'].' ");
route_id = "'.$row['route_id'].'";
	$.ajax({
				url:"search/country_code.php",
				method:"POST",
				data:{route_id:route_id},
				success:function(response){
$("#coutry_code_table").html(response);

}
			
			});

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
id="'.$row['id'].'";
route_id="'.$row['route_id'].'";
route_type="'.$row['route_type'].'";
main_id="'.$row['main_id'].'";
			$.ajax({
				url:"search/route_time_route.php",
				method:"POST",
				
				beforeSend:function()
	    		{
 $("#load_manually_time").html("<div class=`placeholder-glow` ><span class=`placeholder col-12`style=`height:300px`></span></div>");		
	    		},
				data:{id:id,route_id:route_id,route_type:route_type,main_id:main_id},
				success:function(response){
		$("#load_route_time").html(response);
}
			
			});
	
});	
</script>
',
"arrival"=>'
			<span  style="display:none;"id="id'.$row['id'].'">'.$row['id'].'</span>
			<button  type="button" data-bs-toggle="modal"data-bs-target="#trip_time_manullay" class="btn btn-soft-warning"id="clock_arrv'.$row['id'].'">
                       <i class="uil uil-clock"></i> </button>
<script>
$("#clock_arrv'.$row['id'].'").click(function(){
id="'.$row['id'].'";
route_id="'.$row['route_id'].'";
route_type="'.$row['route_type'].'";
main_id="'.$row['main_id'].'";
			$.ajax({
				url:"search/route_time_arrival_route.php",
				method:"POST",
				
				beforeSend:function()
	    		{
 $("#load_manually_time").html("<div class=`placeholder-glow` ><span class=`placeholder col-12`style=`height:300px`></span></div>");		
	    		},
				data:{id:id,route_id:route_id,route_type:route_type,main_id:main_id},
				success:function(response){
		$("#load_route_time").html(response);
}
			
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
