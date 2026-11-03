<?php
include 'config2.php';
## Read value
$draw = $_POST['draw'];
$row = $_POST['start'];
$rowperpage = $_POST['length']; // Rows display per page
$columnIndex = $_POST['order'][0]['column']; // Column index
$columnfullname = $_POST['columns'][$columnIndex]['data']; // Column fullname
$columnSortOrder = $_POST['order'][0]['dir']; // asc or desc
$searchValue = $_POST['search']['value']; // Search value
$my_id = $_POST['my_id'];
$searchArray = array();

## Search 
$searchQuery = " ";
if($searchValue != ''){
	$searchQuery = " AND (name LIKE :name  


		) ";
    $searchArray = array( 
        'name'=>"%$searchValue%",  
        
      
		
   
		
    );
}

## Total number of records without filtering
$stmt = $conn->prepare("SELECT COUNT(*) AS allcount FROM  vehicle_category ");
$stmt->execute();
$records = $stmt->fetch();
$totalRecords = $records['allcount'];

## Total number of records with filtering
$stmt = $conn->prepare("SELECT COUNT(*) AS allcount FROM  vehicle_category  WHERE   1  ".$searchQuery);
$stmt->execute($searchArray);
$records = $stmt->fetch();
$totalRecordwithFilter = $records['allcount'];

## Fetch records
$stmt = $conn->prepare("SELECT * FROM  vehicle_category   WHERE   1 ".$searchQuery." ORDER BY id  asc LIMIT :limit,:offset");

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
			"detail"=>$row['detail'],
			"partiuclar"=>$row['partiuclar'],	
            "particular_insert"=>'<button class="btn btn-indigo btn-sm" id="particular'.$row['id'].'"><i class="bx bx-plus"></i></button>
			<script>
			$("#particular'.$row['id'].'").click(function(){
			$("#modaldemo2").modal("show");
			$(".particular_name_holder").text("'.$row['name'].'");
			$("#category_item").val("'.$row['id'].'");
			$("#category_name").val("'.$row['name'].'");
			
			});
			</script>
			',
			"edit"=>'<button type="button"  class="btn btn-warning btn-sm edit'.$row['id'].'" data-bs-toggle="offcanvas" data-bs-target="#offcanvasTop" aria-controls="offcanvasTop" ><i class="bx bx-edit"></i></button>
			<script>
			$(".edit'.$row['id'].'").click(function(){
			$("#id_no").val("'.$row['id'].'");
			$("#choose_account").text("'.$row['category'].'");
			$("#choose_group").text("'.$row['cat_group'].'");
			$("#group_cat").val("'.$row['cat_group'].'");
			$("#category2").val("'.$row['category'].'");
			$("#id_no_update").val("'.$row['id'].'");
			$("#particular_amount2").val("'.$row['name'].'");

			});
			</script>
			',
			"delete"=>'<button type="button"  class="btn btn-danger btn-sm delete'.$row['id'].'" ><i class="bx bx-trash"></i></button>
			<span class="text'.$row['id'].'"></span>
			<script>
			$(".delete'.$row['id'].'").click(function(){
				id = "'.$row['id'].'";
				$(".text'.$row['id'].'").text("deleting....");
						 $.ajax({
				url:"delete/delete_vehicle_particular.php",
				method:"POST",
				data:{id:id},
				success:function(response){

		$("#example1").DataTable().ajax.reload();
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
