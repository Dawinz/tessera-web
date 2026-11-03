<?php
require_once __DIR__ . '/includes/tessera_staff_boot.php';
include 'config2.php';
## Read value
$draw = $_POST['draw'];
$row = $_POST['start'];
$rowperpage = $_POST['length']; // Rows display per page
$columnIndex = $_POST['order'][0]['column']; // Column index
$columnName = $_POST['columns'][$columnIndex]['data']; // Column name
$columnSortOrder = $_POST['order'][0]['dir']; // asc or desc
$searchValue = $_POST['search']['value']; // Search value
$username = $_POST['username'];
$date_now = $_POST['date_now'];


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
$stmt = $conn->prepare("SELECT COUNT(*) AS allcount FROM parcel_info WHERE " . tessera_sql_tenant_eq() . "");
$stmt->execute();
$records = $stmt->fetch();
$totalRecords = $records['allcount'];

## Total number of records with filtering
$stmt = $conn->prepare("SELECT COUNT(*) AS allcount FROM parcel_info WHERE " . tessera_sql_tenant_eq() . " AND 1 ".$searchQuery);
$stmt->execute($searchArray);
$records = $stmt->fetch();
$totalRecordwithFilter = $records['allcount'];

## Fetch records
$stmt = $conn->prepare("SELECT * FROM parcel_info WHERE " . tessera_sql_tenant_eq() . " AND username='$username' and 1 ".$searchQuery." ORDER BY ".$columnName." ".$columnSortOrder." LIMIT :limit,:offset");

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

iframe.src = "print_ticket.php?id='.$row['code'].'";
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
	        "amount"=>number_format($row['amount'],2),
		    "paid"=>number_format($row['paid'],2),
	        "to_be"=>number_format($row['to_be'],2),

		

        );
}

## amount 
$amount  = array(
    "draw" => intval($draw),
    "iTotalRecords" => $totalRecords,
    "iTotalDisplayRecords" => $totalRecordwithFilter,
    "aaData" => $data
);

echo json_encode($amount );
