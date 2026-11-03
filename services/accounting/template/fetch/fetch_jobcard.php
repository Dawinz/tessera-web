<?php
include 'config2.php';
## Read value
$draw = $_POST['draw'];
$row = $_POST['start'];
$rowperpage = $_POST['length']; // Rows display per page
$columnIndex = $_POST['order'][0]['column']; // Column index
$columntrip_date = $_POST['columns'][$columnIndex]['data']; // Column trip_date
$columnSortOrder = $_POST['order'][0]['dir']; // asc or desc
$searchValue = $_POST['search']['value']; // Search value
$my_id = $_POST['my_id'];
$searchArray = array();

## Search 
$searchQuery = " ";
if($searchValue != ''){
	$searchQuery = " AND (date LIKE :date or 
        username  LIKE :username  

		) ";
    $searchArray = array( 
        'username'=>"%$searchValue%",  
		'date'=>"%$searchValue%", 
   
		
    );
}

## Total number of records without filtering
$stmt = $conn->prepare("SELECT COUNT(*) AS allcount FROM job_card WHERE " . tessera_sql_tenant_eq() . "  ");
$stmt->execute();
$records = $stmt->fetch();
$totalRecords = $records['allcount'];

## Total number of records with filtering
$stmt = $conn->prepare("SELECT COUNT(*) AS allcount FROM job_card WHERE " . tessera_sql_tenant_eq() . " AND 1  ".$searchQuery);
$stmt->execute($searchArray);
$records = $stmt->fetch();
$totalRecordwithFilter = $records['allcount'];

## Fetch records
$stmt = $conn->prepare("SELECT * FROM job_card WHERE " . tessera_sql_tenant_eq() . " AND ".$my_id."  1  ".$searchQuery." ORDER BY id  DESC LIMIT :limit,:offset");

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
          
		
			"date"=>date("l jS m Y", strtotime($row['date'])),
			"name"=>$row['name'],
			"amount"=>number_format($row['amount']),
			"insert"=>'<a href="jobcard_entries.php"><button type="button" class="btn btn-success btn-sm"><i class="bx bx-plus"style="color:white;"></i></button></a>',
	"edit"=>'<button type="button" id="Lock_modal" class="btn btn-warning btn-sm edit'.$row['id'].'" data-bs-toggle="offcanvas" data-bs-target="#offcanvasTop" aria-controls="offcanvasTop" ><i class="bx bx-edit"></i></button>
			<script>
			$(".edit'.$row['id'].'").click(function(){
			$(".choose_route").text("'.$row['route_name'].'");
			$("#choose_route").val("'.$row['route_name'].'");
			$("#insert_date").val("'.date("m/d/Y", strtotime($row['date'])).'");
			$("#first_date").val("'.$row['date'].'");
			$("#route_name").val("'.$row['route_name'].'");
			});
			</script>
			',
		
			"delete"=>'<button type="button" class="btn btn-danger btn-sm delete'.$row['id'].'"><i class="bx bx-trash"></i></button>
			<span class="dtext'.$row['id'].'"></span>
			<script>
			$(".delete'.$row['id'].'").click(function(){
				
				id = "'.$row['id'].'";
				date = "'.$row['date'].'";
				route_name = "'.$row['route_name'].'";
				$(".dtext'.$row['id'].'").text("Deleting....");
				 $.ajax({
				url:"delete/delete_job_card.php",
				method:"POST",
				data:{id:id,date:date,route_name:route_name},
				success:function(response){
					$("#example1").DataTable().ajax.reload();
					alert(response);
									$(".dtext'.$row['id'].'").text("");

             response = $.trim(response);
			 if(response == "found"){
				 Swal.fire({
  title: "Error",
  text: "Cannot delete Data found,please empty Jobcard then delete",
  icon: "warning"
});
			 }else{
				 
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
