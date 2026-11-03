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
	$searchQuery = " AND (name LIKE :name or category LIKE :category 

		) ";
    $searchArray = array( 
        'name'=>"%$searchValue%",  
        'category'=>"%$searchValue%",  
		
   
		
    );
}

## Total number of records without filtering
$stmt = $conn->prepare("SELECT COUNT(*) AS allcount FROM  particulars ");
$stmt->execute();
$records = $stmt->fetch();
$totalRecords = $records['allcount'];

## Total number of records with filtering
$stmt = $conn->prepare("SELECT COUNT(*) AS allcount FROM  particulars  WHERE   1  ".$searchQuery);
$stmt->execute($searchArray);
$records = $stmt->fetch();
$totalRecordwithFilter = $records['allcount'];

## Fetch records
$stmt = $conn->prepare("SELECT * FROM  particulars   WHERE   1 ".$searchQuery." ORDER BY id  asc LIMIT :limit,:offset");

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
			"category"=>$row['category'],
			"account"=>$row['account'],
			"sub_particulars"=>$row['sub_particulars'],
			"edit"=>'<button type="button"  class="btn btn-warning btn-sm edit'.$row['id'].'" data-bs-toggle="offcanvas" data-bs-target="#offcanvasTop" aria-controls="offcanvasTop" ><i class="bx bx-edit"></i></button>
			<script>
			$(".edit'.$row['id'].'").click(function(){
			$("#category_update_value").text("'.$row['cat_group'].'");
			$("#category_update").val("'.$row['cat_group'].'");
			$("#account_text").text("'.$row['account'].'");
			$("#account").val("'.$row['account'].'");
			$("#particular_update").val("'.$row['name'].'");
			$("#id_no_update").val("'.$row['id'].'");



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
				url:"delete/delete_particular_detail.php",
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
