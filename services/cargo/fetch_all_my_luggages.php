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
$username = $_POST['username'];
$order_by = "ORDER BY date_insert desc,id desc";
if($start_date!=""){
	$start_date = "date_insert >= '$start_date' and";
	$order_by = "ORDER BY date_insert asc ,id asc";
}
if($end_date!=""){
	$end_date = "date_insert <= '$end_date' and";
	$order_by = "ORDER BY date_insert asc,id asc";
}
$username_query = "username = '$username'";
$searchArray = array();



## Search 
$searchQuery = " ";
if($searchValue != ''){
$searchQuery = " (code LIKE '%$searchValue%') and  ";
}

function  qty ($qty,$scanned){
if ($qty == $scanned){
$qty = $qty;
}else{

$qty = " ".$scanned." out of ".$qty." ";
}
return $qty;
}
function store($id,$store){
    
    if($store=="store"){
$store = '<a target="_blank" href = view.php?u='.$id.'><span class="badge text-white text-bg-primary" style="width:60px">Store</span> </a>';
    }else if ($store=="half_out"){
$store = '<a target="_blank" href = view.php?u='.$id.'><span class="badge text-white  text-bg-warning"style="color:#D6D6D6;width:60px;">Half out</span> </a>';

    }else if($store=="out"){
$store = '<a target="_blank" href = view.php?u='.$id.'><span class="badge text-bg-danger"style="width:60px">Out</span> </a>';

    }
    return $store;
}
function  to_be_paid ($to_be,$to_be_remain,$store){
if ($store != "half_out") {
$to_be = number_format($to_be);
}else{

$to_be = "".number_format($to_be)." <small class='text-muted'>".number_format($to_be_remain)."</small>";
}
return $to_be;
}
function  total_amount ($paid,$paid_remain,$to_be,$to_be_remain,$amount,$store){
if ($store != "half_out") {
$total_amount = number_format($paid+$to_be);
}else{
$total_amount = $to_be_remain+$paid_remain;
$total_amount = "".number_format($amount)."  <small class='text-muted'>".number_format($total_amount)."</small>";
}
return $total_amount;
}
function  paid ($paid,$paid_remain,$store){
if ($store != "half_out") {
$paid = number_format($paid);
}else{

$paid = "".number_format($paid)." <small class='text-muted'>".number_format($paid_remain)."</small>";
}
return $paid;
}
## Total number of records without filtering
$stmt = $conn->prepare("SELECT COUNT(*) AS allcount FROM parcel_info WHERE " . tessera_sql_tenant_eq() . "");
$stmt->execute();
$records = $stmt->fetch();
$totalRecords = $records['allcount'];

## Total number of records with filtering
$stmt = $conn->prepare("SELECT COUNT(*) AS allcount FROM parcel_info WHERE " . tessera_sql_tenant_eq() . " AND ".($route_name)." ".$username_query."  and 1 ");
$stmt->execute($searchArray);
$records = $stmt->fetch();
$totalRecordwithFilter = $records['allcount'];

## Fetch records
$stmt = $conn->prepare("SELECT * FROM parcel_info WHERE " . tessera_sql_tenant_eq() . " AND ".$start_date." ".$end_date ." ".$searchQuery."   ".($route_name)." ".$username_query."  and 1  ".$order_by." LIMIT :limit,:offset");

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
			"account"=>$row['account'],
            "r_name"=>$row['r_name'],
			"code"=>$row['code'],
			"Weight"=>''.$row['weight_value'].''.$row['weight_name'].'',
			"date"=>date("dS-m-Y", strtotime($row['date_insert'])),
	       "amount"=>''.total_amount($row['paid'],$row['paid_remain'],$row['to_be'],$row['to_be_remain'],$row['amount'],$row['store']).'',
			 "to_be"=>''.to_be_paid($row['to_be'],$row['to_be_remain'],$row['store']).'',
			 "paid"=>''.paid($row['paid'],$row['paid_remain'],$row['store']).'',
			  "quantity"=>''.qty($row['question'],$row['scanned'],$row['store']).'',
             "store"=>store($row['id'],$row['store']),

	 "detail"=>base64_decode($row['detail']),	
	 "status"=>$row['status'],			
	"edit"=>'<a  href = update_luggage.php?u='.$row['id'].'> <button type="button" class="btn btn-warning"><i class="uil uil-pen"></i></button></a>',
	"print"=>'<a href="print_ticket_mulitple.php?code='.$row['code'].'"><button id="print'.$row['id'].'"type="button" class="btn btn-soft-primary"><i class="uil uil-print"></i></button></a>

	',
	"delete"=>'<button type="button" id="delete'.$row['id'].'" class="btn btn-danger"><i class="uil uil-ban"></i></button>
	<script>
    $("#delete'.$row['id'].'").click(function(){
$("#delete_luggage").modal("show");

$("#code_no").text("'.$row['code'].'");
$("#luggaeg_p").text("'.$row['code'].'");
$("#l_code").val("'.$row['code'].'");
$("#l_id").val("'.$row['id'].'");
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
