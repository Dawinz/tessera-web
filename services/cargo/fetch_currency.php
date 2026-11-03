<?php
require_once __DIR__ . '/includes/tessera_staff_boot.php';
include 'config2.php';
## Read value
$draw = $_POST['draw'] ?? 0;
$row = (int) ($_POST['start'] ?? 0);
$rowperpage = (int) ($_POST['length'] ?? 10); // Rows display per page
$columnIndex = (int) ($_POST['order'][0]['column'] ?? 0);
$allowedCols = ['name', 'status', 'country', 'initial', 'rate', 'id'];
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
	$searchQuery = " AND (name LIKE :name OR country LIKE :country OR initial LIKE :initial) ";
	$searchArray = array(
		'name' => "%$searchValue%",
		'country' => "%$searchValue%",
		'initial' => "%$searchValue%",
	);
}

## Total number of records without filtering
$stmt = $conn->prepare("SELECT COUNT(*) AS allcount FROM currency");
$stmt->execute();
$records = $stmt->fetch();
$totalRecords = $records['allcount'];

## Total number of records with filtering
$stmt = $conn->prepare("SELECT COUNT(*) AS allcount FROM currency WHERE   1  ".$searchQuery);
$stmt->execute($searchArray);
$records = $stmt->fetch();
$totalRecordwithFilter = $records['allcount'];

## Fetch records
$stmt = $conn->prepare("SELECT * FROM currency  WHERE   1  ".$searchQuery." ORDER BY ".$columnName." ".$columnSortOrder." LIMIT :limit,:offset");

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
          
			"name"=>$row['name'],
			"status"=>$row['status'],	 
			"country"=>$row['country'],
			"initial"=>$row['initial'],
			
            "rate"=>number_format($row['rate'],2), 
			
	

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
				url:"delete_currecny.php",
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


			<script>
			$(document).ready(function(){
			$("#update'.$row['id'].'").click(function(){
 $("#currecy_name_update").val("'.$row['name'].'");
 $("#initial_update").val("'.$row['initial'].'");
 $("#update_country").text("'.$row['country'].'");
 $("#update_status").text("'.$row['status'].'"); 
 $("#id_no").text("'.$row['id'].'"); 
 $("#fair_update").val("'.$row['rate'].'");
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
