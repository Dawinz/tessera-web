<?php
require_once __DIR__ . '/includes/tessera_staff_boot.php';
include 'config2.php';
## Read value
$draw = isset($_POST['draw']) ? (int)$_POST['draw'] : 0;
$row = isset($_POST['start']) ? (int)$_POST['start'] : 0;
$rowperpage = isset($_POST['length']) ? (int)$_POST['length'] : 10;
if ($rowperpage < 1) { $rowperpage = 10; }
$columnIndex = isset($_POST['order'][0]['column']) ? (int)$_POST['order'][0]['column'] : 0;
$columnName = 'id';
if (isset($_POST['columns'][$columnIndex]['data']) && $_POST['columns'][$columnIndex]['data'] !== '') {
	$columnName = preg_replace('/[^a-zA-Z0-9_]/', '', (string)$_POST['columns'][$columnIndex]['data']);
	if ($columnName === '') { $columnName = 'id'; }
}
$columnSortOrder = (isset($_POST['order'][0]['dir']) && strtolower($_POST['order'][0]['dir']) === 'asc') ? 'ASC' : 'DESC';
$searchValue = isset($_POST['search']['value']) ? $_POST['search']['value'] : '';

$searchArray = array();

## Search 
$searchQuery = " ";
if($searchValue != ''){
	$searchQuery = " AND (seat_id LIKE :seat_id ) ";
    $searchArray = array( 
        'seat_id'=>"%$searchValue%", 
      
		
    );
}

## Total number of records without filtering
$stmt = $conn->prepare("SELECT COUNT(*) AS allcount FROM deleted ");
$stmt->execute();
$records = $stmt->fetch();
$totalRecords = $records['allcount'];

## Total number of records with filtering
$stmt = $conn->prepare("SELECT COUNT(*) AS allcount FROM deleted  WHERE   1  ".$searchQuery);
$stmt->execute($searchArray);
$records = $stmt->fetch();
$totalRecordwithFilter = $records['allcount'];

## Fetch records
$stmt = $conn->prepare("SELECT * FROM deleted   WHERE   1  ".$searchQuery." ORDER BY id desc LIMIT :limit,:offset");

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
			"seat_no"=>$row['seat_no'],
			"seat_id"=>$row['seat_id'],
			"username"=>$row['username'],
			"delete_date"=>$row['delete_date'],
			"ticket_user"=>$row['ticket_user'],
			"ticket_deleter"=>$row['ticket_deleter'],

	        "phone_no"=>$row['phone_no'],
			"location"=>$row['location'], 
			
			"seat_insert_time"=>$row['seat_insert_time'],
			"trip_date"=>$row['trip_date'],
				"restore"=>'
			<span style="display:none;"id="id'.$row['id'].'">'.$row['id'].'</span>
			<button type="button" class="btn btn-soft-warning"id="restore'.$row['id'].'" '.$row['button'].'>
                       <i class="uil uil-arrow-circle-left"></i> </button>


			<script>
			$(document).ready(function(){

			$("#restore'.$row['id'].'").click(function(){
							var seat_id = '.$row['seat_id'].';
							var id = $("#id'.$row['id'].'").text();

								$.ajax({
				url:"restore_ticket.php",
				method:"POST",
				data:{id:id,seat_id:seat_id},
				success:function(response){
						if($.trim(response) == "not"){
													  swal({
    title: "authorization",
    text: "Only super admin can recover.",
    timer: 2000,
    showConfirmButton: false
  });
					}else{
							$("#example").DataTable().ajax.reload();

					}

}
			
			});
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
    // legacy DataTables keys
    "iTotalRecords" => intval($totalRecords),
    "iTotalDisplayRecords" => intval($totalRecordwithFilter),
    "aaData" => $data
);

header('Content-Type: application/json; charset=utf-8');
echo json_encode($response);
