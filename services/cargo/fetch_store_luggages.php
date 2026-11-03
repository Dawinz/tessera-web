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
$route_name = $_POST['route_name'];
$start_date = $_POST['start_date'];
$end_date = $_POST['end_date'];
$store_status = $_POST['store'];
$store_query="";
$order_by = "ORDER BY date_insert desc,id desc";

 ($store_status=="") ?$store_query = "store!='out' and": $store_query = " store='$store_status' and";

if($start_date!=""){
	$start_date = "date_insert >= '$start_date' and";
	$order_by = "ORDER BY date_insert asc ,id asc";
}
if($end_date!=""){
	$end_date = "date_insert <= '$end_date' and";
	$order_by = "ORDER BY date_insert asc,id asc";
}
function  to_be_paid ($to_be,$to_be_remain){
if ($to_be == $to_be_remain){
$to_be = $to_be;
}else{

$to_be = "".number_format($to_be)." <small class='text-muted'>".number_format($to_be_remain)."</small>";
}
return $to_be;
}

function  paid ($paid,$paid_remain){
if ($paid == $paid_remain){
$paid = $paid;
}else{

$paid = "".number_format($paid)." <small class='text-muted'>".number_format($paid_remain)."</small>";
}
return $paid;
}
function  qty ($qty,$scanned){
if ($qty == $scanned){
$qty = $qty;
}else{

$qty = " ".$scanned." out of ".$qty." ";
}
return $qty;
}
function  total_amount ($paid,$paid_remain,$to_be,$to_be_remain,$amount){
if (($paid == $paid_remain) AND ($to_be_remain == $to_be) ){
$total_amount = $paid+$to_be;
}else{
$total_amount = $to_be_remain+$paid_remain;
$total_amount = "".number_format($amount)."  <small class='text-muted'>".number_format($total_amount)."</small>";
}
return $total_amount;
}
function store($id,$store){
    
    if($store=="store"){
$store = '<a target="_blank" href = view.php?u='.$id.'><span class="badge text-white text-bg-primary" style="width:60px">Store</span> </a>';
    }else if ($store=="half_out"){
$store = '<a target="_blank" href = view.php?u='.$id.'><span class="badge text-white  text-bg-warning"style="color:#D6D6D6;width:60px;">Half out</span> </a>';

    }else if($store=="store"){
$store = '<a target="_blank" href = view.php?u='.$id.'><span class="badge text-bg-danger">Out</span> </a>';

    }
    return $store;
}


$store = " and store='store'";
$half_out = " and store='half_out'";
$searchArray = array();


## Search 
$searchQuery = " ";
if($searchValue != ''){
$searchQuery = " (code LIKE '%$searchValue%') and  ";
}



## Total number of records without filtering
$stmt = $conn->prepare("SELECT COUNT(*) AS allcount FROM parcel_info WHERE " . tessera_sql_tenant_eq() . "");
$stmt->execute();
$records = $stmt->fetch();
$totalRecords = $records['allcount'];

## Total number of records with filtering
$stmt = $conn->prepare("SELECT COUNT(*) AS allcount FROM parcel_info WHERE " . tessera_sql_tenant_eq() . " AND ".$start_date." ".$end_date." ".$route_name." ".$store_query ." 1  ");
$stmt->execute($searchArray);
$records = $stmt->fetch();
$totalRecordwithFilter = $records['allcount'];

## Fetch records
$stmt = $conn->prepare("SELECT * FROM parcel_info WHERE " . tessera_sql_tenant_eq() . " AND ".$start_date." ".$end_date." ".$route_name." ".$store_query ."  ".$searchQuery." 1  ".$order_by." LIMIT :limit,:offset");

// Bind values
foreach($searchArray as $key=>$search){
    $stmt->bindValue(':'.$key, $search,PDO::PARAM_STR);
}

$stmt->bindValue(':limit', (int)$row, PDO::PARAM_INT);
$stmt->bindValue(':offset', (int)$rowperpage, PDO::PARAM_INT);
$stmt->execute();
$empRecords = $stmt->fetchAll();

$data = array();
$routes = [];
$values = [];
foreach($empRecords as $row){



    $data[] = array(
            "id"=>$row['id'],
			"s_name"=>$row['s_name'],
			"account"=>$row['account'],
            "r_name"=>$row['r_name'],
			"code"=>$row['code'],
			"Weight"=>''.$row['weight_value'].''.$row['weight_name'].'',
			"date"=>date("dS-m-Y", strtotime($row['date_insert'])),
	        "amount"=>''.total_amount($row['paid'],$row['paid_remain'],$row['to_be'],$row['to_be_remain'],$row['amount']).'',
			 "to_be"=>''.to_be_paid($row['to_be'],$row['to_be_remain']).'',
			  "paid"=>''.paid($row['paid'],$row['paid_remain']).'',
			  "quantity"=>''.qty($row['question'],$row['scanned']).'',

	 "detail"=>base64_decode($row['detail']),	
	 "status"=>$row['status'],			
			"store"=>store($row['id'],$row['store']),	
	"edit"=>'<a  href = update_luggage.php?u='.$row['id'].'> <button type="button" class="btn btn-warning"><i class="uil uil-pen"></i></button></a>',
	"print"=>'<button id="print'.$row['id'].'"type="button" class="btn btn-soft-primary"><i class="uil uil-print"></i></button>
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
	',
	"delete"=>'<button type="button" id="delete'.$row['id'].'" class="btn btn-danger"><i class="uil uil-ban"></i></button>
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
    

        );

     $values[] = [
    "route_name" => $row['account'],
    "paid" => $row['paid_remain'],
    "to_be" => $row['to_be_remain'],
    
];

if (in_array($row['account'], $routes)) {
 
} else {

   $routes[] = $row['account'];


}
}

if (!empty($data)) {
   $data[] = array(

    "code"=>"",
     "store"=>"",
    "s_name"=>"",
    "r_name"=>"",
    "date"=>"",
    "Weight"=>"",
    "detail"=>"",
    "quantity"=>"<strong>Currency</strong>",
    "to_be"=>"<strong>Total To be paid</strong>",
    "paid"=>"<strong>Total Paid</strong>",
    "amount"=>"<strong>Total Ammount</strong>",
   
);
}

foreach ($routes as $route_name) {
   $total_to_be_paid = 0;
   $total_paid = 0;
$stmt = $conn->prepare("SELECT currency_symbol from routes where route_full_name ='$route_name'");
$stmt->execute();
$currency = $stmt->fetch(PDO::FETCH_ASSOC);
$currency_symbol = $currency['currency_symbol'];
   
$stmt = $conn->prepare("SELECT SUM(to_be_remain),SUM(to_be) AS to_be_sum,SUM(to_be_remain),SUM(paid_remain) AS paid_sum FROM parcel_info WHERE " . tessera_sql_tenant_eq() . " AND ".$start_date." ".$end_date." account='$route_name' and ".$store_query ." ".$searchQuery." 1  ");
$stmt->execute();
$row = $stmt->fetch(PDO::FETCH_ASSOC);
$to_be_sum = $row['to_be_sum'];
$paid_sum = $row['paid_sum'];




$data[] = array(

    "code"=>"",
     "store"=>"",
    "s_name"=>"",
    "r_name"=>"",
    "date"=>"",
    "Weight"=>"",
    "detail"=>"$route_name",
    "quantity"=>"<h6><span class='badge bg-success '>".$currency_symbol."</H6>",
    "to_be"=>"".number_format($to_be_sum)."",
    "paid"=>"".number_format($paid_sum)."",
    "amount"=>"".number_format($to_be_sum+$paid_sum)."",
   
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
