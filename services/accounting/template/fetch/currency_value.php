<?php
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
	$searchQuery = " AND (name LIKE :name or 
        value_no  LIKE :value_no  

		) ";
    $searchArray = array( 
        'value_no'=>"%$searchValue%",  
		'name'=>"%$searchValue%", 
   
		
    );
}

## Total number of records without filtering
$stmt = $conn->prepare("SELECT COUNT(*) AS allcount FROM  currency_value  ");
$stmt->execute();
$records = $stmt->fetch();
$totalRecords = $records['allcount'];

## Total number of records with filtering
$stmt = $conn->prepare("SELECT COUNT(*) AS allcount FROM  currency_value   WHERE   1  ".$searchQuery);
$stmt->execute($searchArray);
$records = $stmt->fetch();
$totalRecordwithFilter = $records['allcount'];

## Fetch records
$stmt = $conn->prepare("SELECT * FROM  currency_value    WHERE   1  ".$searchQuery." ORDER BY id  asc LIMIT :limit,:offset");

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
          
		
			"first_point"=>$row['first_point'],
			"second_point"=>$row['second_point'],
			"value"=>'1 '.$row['second_point'].' = '.$row['value'].' '.$row['second_point'].'',
		
				"edit"=>'<button type="button"  class="btn btn-warning btn-sm edit'.$row['id'].'" data-bs-toggle="offcanvas" data-bs-target="#offcanvasTop" aria-controls="offcanvasTop" ><i class="bx bx-edit"></i></button>
			<script>
			$(".edit'.$row['id'].'").click(function(){
				
$("#choose_first_update_text").text("'.$row['first_point'].'");
$("#choose_first_update").val("'.$row['first_point'].'");

$("#secondary_update_text").text("'.$row['first_point'].'");
$("#secondary_update").val("'.$row['second_point'].'");
$("#value_update").val("'.$row['value'].'");
$("#id_update").val("'.$row['id'].'");
			});
			</script>
			',
			"delete"=>'<button type="button" class="btn btn-danger btn-sm delete'.$row['id'].'"><i class="bx bx-trash"></i></button>
			<span class="dtext'.$row['id'].'"></span>
			<script>
			$(".delete'.$row['id'].'").click(function(){
				
				id = "'.$row['id'].'";
			
				$(".dtext'.$row['id'].'").text("Deleting....");
				 $.ajax({
				url:"delete/currency_value.php",
				method:"POST",
				data:{id:id},
				success:function(response){
		if($.trim(response)=="found"){
		Swal.fire({
  title: "Primary",
  text: "Cant delete primary currency",
  icon: "warning"
});
					$(".dtext'.$row['id'].'").text("");

		}else{
		$("#example1").DataTable().ajax.reload();

		}
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
