<?php
include 'config2.php';
## Read value
$draw = $_POST['draw'];
$row = $_POST['start'];
$rowperpage = $_POST['length']; // Rows display per page
$columnIndex = $_POST['order'][0]['column']; // Column index
$columnname = $_POST['columns'][$columnIndex]['data']; // Column name
$columnSortOrder = $_POST['order'][0]['dir']; // asc or desc
$searchValue = $_POST['search']['value']; // Search value
$my_id = $_POST['my_id'];
$searchArray = array();

## Search 
$searchQuery = " ";
if($searchValue != ''){
	$searchQuery = " AND (name LIKE :name or 
        route_name  LIKE :route_name  

		) ";
    $searchArray = array( 
        'route_name'=>"%$searchValue%",  
		'name'=>"%$searchValue%", 
   
		
    );
}

## Total number of records without filtering
$stmt = $conn->prepare("SELECT COUNT(*) AS allcount FROM  constants ");
$stmt->execute();
$records = $stmt->fetch();
$totalRecords = $records['allcount'];

## Total number of records with filtering
$stmt = $conn->prepare("SELECT COUNT(*) AS allcount FROM  constants  WHERE   1  ".$searchQuery);
$stmt->execute($searchArray);
$records = $stmt->fetch();
$totalRecordwithFilter = $records['allcount'];

## Fetch records
$stmt = $conn->prepare("SELECT * FROM  constants   WHERE ".$my_id."  1  ".$searchQuery." ORDER BY id  asc LIMIT :limit,:offset");

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
			"route_name"=>$row['route_name'],
			"price_value"=>number_format($row['price_value'],2),
			"currency"=>$row['currency'],
			"route_category"=>$row['route_category'],
			"name_category"=>$row['name_category'],
			"affect"=>$row['affect'],
			"vehicle"=>$row['vehicle'],
			"delete"=>'<button type="button"  class="btn btn-danger btn-sm delete'.$row['id'].'" ><i class="bx bx-trash"></i></button>
			<span class="text'.$row['id'].'"></span>
			<script>
			$(".delete'.$row['id'].'").click(function(){
				id = "'.$row['id'].'";
				$(".text'.$row['id'].'").text("deleting....");
						 $.ajax({
				url:"delete/delete_constant.php",
				method:"POST",
				data:{id:id},
				success:function(response){
	alert(response);
		$("#example1").DataTable().ajax.reload();
			}

			
			});
				});
			</script>
			',
			"edit"=>'<button type="button" id="Lock_modal" class="btn btn-warning btn-sm edit'.$row['id'].'" data-bs-toggle="offcanvas" data-bs-target="#offcanvasTop" aria-controls="offcanvasTop" ><i class="bx bx-edit"></i></button>
			<script>
			$(".edit'.$row['id'].'").click(function(){
				$("#part_Category_update").text("'.$row['name_category'].'");
				$("#part_particular_update").text("'.$row['name'].'");
				$("#choose_route_update2").text("'.$row['route_name'].'");
				$("#route_category_update23").text("'.$row['route_category'].'");
				$("#currency_v_update23").text("'.$row['currency'].'");
				$("#affect_update2").text("'.$row['affect'].'");
				$("#vehicle_update2").text("'.$row['vehicle'].'");
				$("#amount_update").val("'.$row['price_value'].'");
				$("#id_no").text("'.$row['id'].'");
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
