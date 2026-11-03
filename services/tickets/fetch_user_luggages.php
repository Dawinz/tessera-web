<?php
require_once __DIR__ . '/includes/tessera_staff_boot.php';
include 'config3.php';

try {
## Read value
$draw = (int) ($_POST['draw'] ?? 0);
$row = (int) ($_POST['start'] ?? 0);
$rowperpage = (int) ($_POST['length'] ?? 10);
$columnIndex = (int) ($_POST['order'][0]['column'] ?? 0);
$columnName = (string) ($_POST['columns'][$columnIndex]['data'] ?? 'id');
$columnSortOrder = strtolower((string) ($_POST['order'][0]['dir'] ?? 'desc')) === 'asc' ? 'ASC' : 'DESC';
$searchValue = (string) ($_POST['search']['value'] ?? '');
$username = (string) ($_POST['username'] ?? ($_SESSION['username'] ?? ''));
$date_now = (string) ($_POST['date_now'] ?? '');

$allowedSort = ['id', 'code', 's_name', 'r_name', 'status', 'date_insert'];
if (!in_array($columnName, $allowedSort, true)) {
	$columnName = 'id';
}


$searchArray = array();

## Search 
$searchQuery = " ";
if($searchValue != ''){
	$searchQuery = " AND (s_name LIKE :s_name or 
        r_name  LIKE :r_name  OR 
        code LIKE :code OR 
        status LIKE :status ) ";
    $searchArray = array( 
        's_name'=>"%$searchValue%", 
        'r_name'=>"%$searchValue%",
        'code'=>"%$searchValue%",
		'status'=>"%$searchValue%"
    );
}

## Total number of records without filtering
$stmt = $conn->prepare("SELECT COUNT(*) AS allcount FROM parcel_info");
$stmt->execute();
$records = $stmt->fetch();
$totalRecords = $records['allcount'];

## Total number of records with filtering
$stmt = $conn->prepare("SELECT COUNT(*) AS allcount FROM parcel_info WHERE 1 ".$searchQuery);
$stmt->execute($searchArray);
$records = $stmt->fetch();
$totalRecordwithFilter = $records['allcount'];

$userEsc = preg_replace('/[^a-zA-Z0-9_@.\-]/', '', $username);

## Fetch records
$stmt = $conn->prepare("SELECT * FROM parcel_info WHERE username=:username AND 1=1 ".$searchQuery." ORDER BY ".$columnName." ".$columnSortOrder." LIMIT :limit,:offset");
$stmt->bindValue(':username', $userEsc, PDO::PARAM_STR);

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
			"s_name"=>$row['s_name'],
            "r_name"=>$row['r_name'],
			"status"=>$row['status'],
			"account"=>$row['account'],
			"date_insert"=>$row['date_insert'],
			"detail"=>base64_decode($row['detail']),
            "code"=>''.$row['code'].' <br>

                                    <button id="print'.$row['id'].'"type="button" class="btn btn-primary"><i class="uil uil-print"></i></button>

  <div class="modal fade exampleModalFullscreen'.$row['id'].'" tabindex="-1"
                                            aria-labelledby="exampleModalFullscreenLabel" aria-hidden="true">
                                            <div class="modal-dialog modal-fullscreen">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title" id="exampleModalFullscreenLabel">Print reciepts</h5>
                                                        <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                            aria-label="Close"></button>
                                                    </div>
                                                    <div class="modal-body">
                                                        
<div id="container_print'.$row['id'].'"style="background-color:white;"></div>                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-secondary"
                                                            data-bs-dismiss="modal">Close</button>
                                                   
                                                    </div>
                                                </div><!-- /.modal-content -->
                                            </div><!-- /.modal-dialog -->
                                        </div>
										
										


<script>

$("#print'.$row['id'].'").click(function(){
	
$(".exampleModalFullscreen'.$row['id'].'").modal("show");
	var placeholder = document.createElement("div");
placeholder.innerHTML = "<strong>Loading...</strong>";

var iframe = document.createElement("iframe");
iframe.style.display = "none";

var сontainer =  document.getElementById("container_print'.$row['id'].'");

// Прикреплять к контейнеру нужно сразу, так как "appendChild" после события "load" вызовет повторную загрузку и рекурсивное событие load.
сontainer.appendChild(placeholder);
сontainer.appendChild(iframe);

iframe.src = "print_ticket_luggage.php?id='.$row['code'].'";
iframe.addEventListener("load", function(event) {
  console.log("DOM fully loaded and parsed", event.target.src);
  
  сontainer.removeChild(placeholder);
  event.target.style.display = "";
});	
});
</script>
                                   <a  href = update_luggage.php?u='.$row['id'].'> <button type="button" class="btn btn-warning"><i class="uil uil-pen"></i></button></a>

                                    <button type="button" id="delete'.$row['id'].'" class="btn btn-danger"><i class="uil uil-ban"></i></button>

  <div class="modal fade exampleModalFullscreen2'.$row['id'].'" tabindex="-1"
                                            aria-labelledby="exampleModalFullscreenLabel" aria-hidden="true">
                                            <div class="modal-dialog modal-fullscreen">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title" id="exampleModalFullscreenLabel">Print reciepts</h5>
                                                        <button type="button" class="btn-close  close'.$row['id'].'" data-bs-dismiss="modal"
                                                            aria-label="Close"></button>
                                                    </div>
                                                    <div class="modal-body">
                                                        
<div id="container_print2'.$row['id'].'"style="background-color:white;"></div>                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-secondary close2'.$row['id'].'"
                                                            data-bs-dismiss="modal">Close</button>
                                                   
                                                    </div>
                                                </div><!-- /.modal-content -->
                                            </div><!-- /.modal-dialog -->
                                        </div>

<script>

$(".close'.$row['id'].'").click(function(){
	$("#empTable").DataTable().ajax.reload();
});
$(".close2'.$row['id'].'").click(function(){
	$("#empTable").DataTable().ajax.reload();
});

$("#delete'.$row['id'].'").click(function(){
	
$(".exampleModalFullscreen2'.$row['id'].'").modal("show");
	var placeholder = document.createElement("div");
placeholder.innerHTML = "<strong>Loading...</strong>";

var iframe = document.createElement("iframe");
iframe.style.display = "none";

var сontainer =  document.getElementById("container_print2'.$row['id'].'");

// Прикреплять к контейнеру нужно сразу, так как "appendChild" после события "load" вызовет повторную загрузку и рекурсивное событие load.
сontainer.appendChild(placeholder);
сontainer.appendChild(iframe);

iframe.src = "delete_luggage.php?id='.$row['id'].'";
iframe.addEventListener("load", function(event) {
  console.log("DOM fully loaded and parsed", event.target.src);
  
  сontainer.removeChild(placeholder);
  event.target.style.display = "";
});	
});
</script>

			',
	        "amount"=>number_format((float) ($row['amount'] ?? 0), 2),
		    "paid"=>number_format((float) ($row['payed'] ?? $row['paid'] ?? 0), 2),
	        "to_be"=>number_format((float) ($row['to_be'] ?? 0), 2),

		

        );
}

echo json_encode([
	'draw' => $draw,
	'recordsTotal' => (int) $totalRecords,
	'recordsFiltered' => (int) $totalRecordwithFilter,
	'data' => $data,
	// Legacy keys for older DataTables configs
	'iTotalRecords' => (int) $totalRecords,
	'iTotalDisplayRecords' => (int) $totalRecordwithFilter,
	'aaData' => $data,
]);
} catch (Throwable $e) {
	tickets_luggage_datatables_error($draw ?? 0, 'Could not load luggage list');
}
