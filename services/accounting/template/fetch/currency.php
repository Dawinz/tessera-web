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
$stmt = $conn->prepare("SELECT COUNT(*) AS allcount FROM  currency ");
$stmt->execute();
$records = $stmt->fetch();
$totalRecords = $records['allcount'];

## Total number of records with filtering
$stmt = $conn->prepare("SELECT COUNT(*) AS allcount FROM  currency  WHERE   1  ".$searchQuery);
$stmt->execute($searchArray);
$records = $stmt->fetch();
$totalRecordwithFilter = $records['allcount'];

## Fetch records
$stmt = $conn->prepare("SELECT * FROM  currency   WHERE   1  ".$searchQuery." ORDER BY id  asc LIMIT :limit,:offset");

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
			"value_no"=>number_format($row['value_no'],2),
			"status"=>$row['status'],
			"symbol"=>$row['symbol'],
			"detail"=>$row['detail'],
			"id"=>$row['id'],
			"Evaluation"=>'1'.$row['name'].' = '.number_format($row['value_no'],2).' Tsh',
				"edit"=>'<button type="button"  class="btn btn-warning btn-sm edit'.$row['id'].'" data-bs-toggle="offcanvas" data-bs-target="#offcanvasTop" aria-controls="offcanvasTop" ><i class="bx bx-edit"></i></button>
			<script>
			$(".edit'.$row['id'].'").click(function(){
			$("#id_no").val("'.$row['id'].'");
			$("#name_update").val("'.$row['name'].'");
			$("#value_update").val("'.$row['value_no'].'");
			$("#symbol_update").val("'.$row['symbol'].'");
			$("#detail_update").val("'.$row['detail'].'");

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
				url:"delete/currency.php",
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
    "recordsTotal" => intval($totalRecords),
    "recordsFiltered" => intval($totalRecordwithFilter),
    "data" => $data,
    "iTotalRecords" => intval($totalRecords),
    "iTotalDisplayRecords" => intval($totalRecordwithFilter),
    "aaData" => $data
);

header('Content-Type: application/json; charset=utf-8');
echo json_encode($response);
