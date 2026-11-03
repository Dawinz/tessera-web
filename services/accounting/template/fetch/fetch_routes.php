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
	$searchQuery = " AND (fullname LIKE :fullname or 
        currency  LIKE :currency  

		) ";
    $searchArray = array( 
        'currency'=>"%$searchValue%",  
		'fullname'=>"%$searchValue%", 
   
		
    );
}

## Total number of records without filtering
$stmt = $conn->prepare("SELECT COUNT(*) AS allcount FROM route WHERE " . tessera_sql_tenant_eq() . " ");
$stmt->execute();
$records = $stmt->fetch();
$totalRecords = $records['allcount'];

## Total number of records with filtering
$stmt = $conn->prepare("SELECT COUNT(*) AS allcount FROM route WHERE " . tessera_sql_tenant_eq() . " AND 1  ".$searchQuery);
$stmt->execute($searchArray);
$records = $stmt->fetch();
$totalRecordwithFilter = $records['allcount'];

## Fetch records
$stmt = $conn->prepare("SELECT * FROM route WHERE " . tessera_sql_tenant_eq() . " AND ".$my_id."  1  ".$searchQuery." ORDER BY id  asc LIMIT :limit,:offset");

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
          
		
			"fullname"=>$row['fullname'],
			"name"=>preg_replace("/'/"," ", base64_decode($row['name'])),
			"detail"=>$row['detail'],
			"start_point"=>$row['start_point'],
			"end_point"=>$row['end_point'],
			"badge"=>$row['badge'],
			"currency_name"=>$row['currency'],
			"edit"=>'<button type="button" id="Lock_modal" class="btn btn-warning btn-sm edit'.$row['id'].'" data-bs-toggle="offcanvas" data-bs-target="#offcanvasTop" aria-controls="offcanvasTop" ><i class="bx bx-edit"></i></button>
			<script>
			$(".edit'.$row['id'].'").click(function(){
		$("#load_currency").load("load/edit_currency.php?r_name='.base64_encode($row['fullname']).'&status='.base64_encode($row['status']).'");	
			});
			</script>
			',
			"currency"=>'<button class="btn btn-indigo btn-sm" id="currency'.$row['id'].'"><i class="bx bx-plus"></i></button>
			<script>
			$("#currency'.$row['id'].'").click(function(){
			$("#modaldemo2").modal("show");
			$(".route_name_holder").text("'.$row['fullname'].'");
			$("#currency_r").val("'.$row['fullname'].'");
			$("#r_status").val("'.$row['status'].'");
			
			});
			</script>
			',	
			
			"active"=>'<button style="width:100px;padding:2px;"class="btn btn-'.$row['button_view'].' btn-sm" id="acv'.$row['id'].'">'.$row['button'].'</button>
			<script>
			$("#acv'.$row['id'].'").click(function(){
		id = "'.$row['id'].'";
		availability = "'.$row['availability'].'";
				 $.ajax({
				url:"update/acv_update.php",
				method:"POST",
				data:{id:id,availability:availability},
				success:function(response){
					$("#example1").DataTable().ajax.reload();

		alert(response);
			}

			
			});
			
			
			});
			</script>
			',
			"delete"=>'<button class="btn btn-danger btn-sm" id="delete'.$row['id'].'"><i class="bx bx-trash"></i></button><span id="delete_text'.$row['id'].'"></span>
			<script>
			$("#delete'.$row['id'].'").click(function(){
		id = "'.$row['id'].'";
				route_name = "'.$row['fullname'].'";
$("#delete_text'.$row['id'].'").text("deleting....");
				 $.ajax({
				url:"delete/route.php",
				method:"POST",
				data:{id:id,route_name:route_name},
				success:function(response){
					$("#delete_text'.$row['id'].'").text("");

				response = $.trim(response);
				if(response == "found"){
				Swal.fire({
  title: "Entires Found?",
  text: "You cant delete entries found ",
  icon: "warning"
});
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
