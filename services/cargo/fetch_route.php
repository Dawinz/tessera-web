<?php
require_once __DIR__ . '/includes/tessera_staff_boot.php';
include 'config2.php';
## Read value
$draw = $_POST['draw'] ?? 0;
$row = (int) ($_POST['start'] ?? 0);
$rowperpage = (int) ($_POST['length'] ?? 10); // Rows display per page
$columnIndex = (int) ($_POST['order'][0]['column'] ?? 0);
$allowedCols = ['start_route', 'end_route', 'currency', 'active_date', 'contact_1', 'contact_2', 'address_1', 'address_2', 'fare_price', 'route_name', 'route_full_name', 'id', 'initial'];
$columnName = $_POST['columns'][$columnIndex]['data'] ?? 'id';
if (!in_array($columnName, $allowedCols, true)) {
	$columnName = 'id';
}
$columnSortOrder = strtolower((string) ($_POST['order'][0]['dir'] ?? 'desc')) === 'asc' ? 'ASC' : 'DESC';
$searchValue = (string) ($_POST['search']['value'] ?? '');

$searchArray = array();

## Search 
$searchQuery = " ";
if($searchValue != ''){
	$searchQuery = " AND (start_route LIKE :start_route OR end_route LIKE :end_route OR route_full_name LIKE :route_full_name OR initial LIKE :initial) ";
	$searchArray = array(
		'end_route' => "%$searchValue%",
		'start_route' => "%$searchValue%",
		'route_full_name' => "%$searchValue%",
		'initial' => "%$searchValue%",
	);
}

## Total number of records without filtering
$stmt = $conn->prepare("SELECT COUNT(*) AS allcount FROM routes");
$stmt->execute();
$records = $stmt->fetch();
$totalRecords = $records['allcount'];

## Total number of records with filtering
$stmt = $conn->prepare("SELECT COUNT(*) AS allcount FROM routes WHERE   1  ".$searchQuery);
$stmt->execute($searchArray);
$records = $stmt->fetch();
$totalRecordwithFilter = $records['allcount'];

## Fetch records
$stmt = $conn->prepare("SELECT * FROM routes  WHERE   1  ".$searchQuery." ORDER BY ".$columnName." ".$columnSortOrder." LIMIT :limit,:offset");

// Bind values
foreach($searchArray as $key=>$search){
    $stmt->bindValue(':'.$key, $search,PDO::PARAM_STR);
}

$stmt->bindValue(':limit', (int)$row, PDO::PARAM_INT);
$stmt->bindValue(':offset', (int)$rowperpage, PDO::PARAM_INT);
$stmt->execute();
$empRecords = $stmt->fetchAll();

$data = array();

$jsStr = static function ($value) {
	return json_encode((string) $value, JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_AMP | JSON_HEX_QUOT | JSON_UNESCAPED_UNICODE);
};

foreach($empRecords as $row){
	$term1 = base64_decode((string) ($row['term_1_new1'] ?? ''));
	$term2 = base64_decode((string) ($row['term_2_new'] ?? ''));
	$detail = base64_decode((string) ($row['detail'] ?? ''));
	$routeName = base64_decode((string) ($row['route_name'] ?? ''));
    $data[] = array(
          
			"start_route"=>$row['start_route'],
			"end_route"=>$row['end_route'],	
			"currency"=>$row['currency'],
			"active_date"=>$row['active_date'],	
			 "contact_1"=>$row['contact_1'],
			 "contact_2"=>$row['contact_2'],	
			  "address_1"=>$row['address_1'],
			 "address_2"=>$row['address_2'],	
			 "term_1_new1"=>base64_decode($row['term_1_new1']),
			 "term_2_new"=>base64_decode($row['term_2_new']),	 
            "fare_price"=>''.number_format($row['fare_price'],2).' '. $row['currency_symbol'].'', 
			"route_name"=>base64_decode($row['route_name']),
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
			<button data-bs-toggle="offcanvas" data-bs-target="#offcanvasRight" aria-controls="offcanvasRight"type="button" class="btn btn-soft-light"id="update'.$row['id'].'">
                       <i class="uil uil-pen"></i> </button>


			<script>
			$(document).ready(function(){
			$("#update'.$row['id'].'").click(function(){
$("#id_no").text("'.$row['id'].'");
$("#s_update").text('.$jsStr($row['start_route']).');
$("#e_update").text('.$jsStr($row['end_route']).');
$("#fair_update").val('.$jsStr($row['fare_price']).');
	$("#details_update").val('.$jsStr($detail).');
	$("#initial_update").val('.$jsStr($routeName).');
	$("#r_initial_update").val('.$jsStr($row['initial']).');
	$("#update_currecny").text('.$jsStr($row['currency']).');
	$("#contact_1_update").val('.$jsStr($row['contact_1']).');
	$("#contact_2_update").val('.$jsStr($row['contact_2']).');
	$("#address_1_update").val('.$jsStr($row['address_1']).');
	$("#address_2_update").val('.$jsStr($row['address_2']).');
	$("#terms_1_update").val('.$jsStr($term1).');
	$("#terms_2_update").val('.$jsStr($term2).');

			});	
		
			});
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
