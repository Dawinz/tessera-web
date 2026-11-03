<?php
include '../config2.php';
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
		$searchQuery = " AND (name LIKE :name or 
        driver  LIKE :driver   ) ";
    $searchArray = array( 
        'driver'=>"%$searchValue%",  
		'name'=>"%$searchValue%", 
      
		
    );
}

## Total number of records without filtering
$stmt = $conn->prepare("SELECT COUNT(*) AS allcount FROM vehicles");
$stmt->execute();
$records = $stmt->fetch();
$totalRecords = $records['allcount'];

## Total number of records with filtering
$stmt = $conn->prepare("SELECT COUNT(*) AS allcount FROM vehicles WHERE   1  ".$searchQuery);
$stmt->execute($searchArray);
$records = $stmt->fetch();
$totalRecordwithFilter = $records['allcount'];

## Fetch records
$stmt = $conn->prepare("SELECT * FROM vehicles  WHERE   1  ".$searchQuery." ORDER BY id desc  LIMIT :limit,:offset");

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
			"driver"=>$row['driver'],	
			"conductor"=>$row['conductor'],
			"update"=>'
            <button data-bs-toggle="offcanvas" data-bs-target="#offcanvasRight" type="button"id="edit'.$row['id'].'" class="btn btn-soft-dark"><i class="uil uil-pen "></i></button>
		<script>
		$("#edit'.$row['id'].'").click(function(){
			
			$("#id_no").val("'.$row['id'].'");
			$("#vehicle_name_update").val("'.$row['name'].'");
			$("#driver_name_update").val("'.$row['driver'].'");
			$("#conductor_name_update").val("'.$row['conductor'].'");
		});
		</script>
            
            <button id="delete'.$row['id'].'"type="button" class="btn btn-soft-danger">
         <i class="uil uil-times "></i>
        </button><span id="response_d'.$row['id'].'"></span>
		
		<script>
		$("#delete'.$row['id'].'").click(function(){
			$("#response_d'.$row['id'].'").text("deleting...");
			var delete_id  = "'.$row['id'].'";
				  $.ajax({
				url:"delete/vehicle.php",
				method:"POST",
				data:{delete_id:delete_id},
				success:function(response){
						
				//alert(response);
                $("#example").DataTable().ajax.reload();

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
    "iTotalRecords" => $totalRecords,
    "iTotalDisplayRecords" => $totalRecordwithFilter,
    "aaData" => $data
);

echo json_encode($response);
