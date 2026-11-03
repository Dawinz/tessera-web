<?php
require_once __DIR__ . '/includes/tessera_staff_boot.php';
include 'config2.php';
## Read value
$draw = isset($_POST['draw']) ? (int) $_POST['draw'] : 0;
$row = isset($_POST['start']) ? (int) $_POST['start'] : 0;
$rowperpage = isset($_POST['length']) ? (int) $_POST['length'] : 50;
if ($rowperpage < 1) {
	$rowperpage = 50;
}
$allowedColumns = ['id', 'name', 'driver', 'conductor', 'seats', 'detail', 'status'];
$columnIndex = isset($_POST['order'][0]['column']) ? (int) $_POST['order'][0]['column'] : 0;
$columnName = 'id';
if (isset($_POST['columns'][$columnIndex]['data']) && $_POST['columns'][$columnIndex]['data'] !== '') {
	$candidate = preg_replace('/[^a-zA-Z0-9_]/', '', (string) $_POST['columns'][$columnIndex]['data']);
	if (in_array($candidate, $allowedColumns, true)) {
		$columnName = $candidate;
	}
}
$columnSortOrder = (isset($_POST['order'][0]['dir']) && strtolower((string) $_POST['order'][0]['dir']) === 'asc') ? 'ASC' : 'DESC';
$searchValue = isset($_POST['search']['value']) ? $_POST['search']['value'] : '';

$searchArray = array();

## Search 
$searchQuery = " ";
if($searchValue != ''){
	$searchQuery = " AND (name LIKE :name or 
        driver  LIKE :driver  OR 
        conductor LIKE :conductor OR status Like :status  ) ";
    $searchArray = array( 
        'name'=>"%$searchValue%", 
        'driver'=>"%$searchValue%",
        'conductor'=>"%$searchValue%",
		'status'=>"%$searchValue%"
		
    );
}

$tenantSql = tessera_sql_tenant_eq();

## Total number of records without filtering
$stmt = $conn->prepare("SELECT COUNT(*) AS allcount FROM vehicle WHERE ".$tenantSql);
$stmt->execute();
$records = $stmt->fetch();
$totalRecords = $records['allcount'];

## Total number of records with filtering
$stmt = $conn->prepare("SELECT COUNT(*) AS allcount FROM vehicle WHERE ".$tenantSql." AND 1  ".$searchQuery);
$stmt->execute($searchArray);
$records = $stmt->fetch();
$totalRecordwithFilter = $records['allcount'];

## Fetch records
$stmt = $conn->prepare("SELECT * FROM vehicle WHERE ".$tenantSql." AND 1  ".$searchQuery." ORDER BY ".$columnName." ".$columnSortOrder." LIMIT :limit,:offset");

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
			"name"=>$row['name'],
			"driver"=>$row['driver'],	 
            "conductor"=>$row['conductor'], 
			"seats"=>$row['seats'],
			"detail"=>base64_decode($row['detail']),
			"status"=>$row['status'],

			"delete"=>'
			<span style="display:none;"id="id'.$row['id'].'">'.$row['id'].'</span>
			<button type="button" class="btn btn-soft-danger"id="delete'.$row['id'].'">
                       <i class="uil uil-ban"></i> </button>
						<script>
						$(document).ready(function(){
						$("#delete'.$row['id'].'").click(function(){
							var id = $("#id'.$row['id'].'").text();
							var name = "'.$row['name'].'";
							
							$("#delete'.$row['id'].'").text("deleting...");
											$.ajax({
				url:"delete_vehilce.php",
				method:"POST",
				data:{id:id,name:name},
				success:function(response){
									

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
			<a href="vehicle_manager.php?vehicle='.$row['id'].'" class="btn btn-soft-light" title="Open in Vehicle manager">
                       <i class="uil uil-pen"></i> </a>
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
