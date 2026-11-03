<?php
include 'config2.php';
## Read value
// DataTables may omit order/columns on first paint or custom clients
if (!isset($_POST['order'][0]['column'])) {
	$_POST['order'][0]['column'] = 0;
	$_POST['order'][0]['dir'] = 'desc';
}
if (!isset($_POST['columns'][0]['data'])) {
	$_POST['columns'][0]['data'] = 'trip_date';
}
if (!isset($_POST['search']['value'])) {
	$_POST['search']['value'] = '';
}
$draw = $_POST['draw'];
$row = (int) ($_POST['start'] ?? 0);
$rowperpage = (int) ($_POST['length'] ?? 70); // Rows display per page
if ($rowperpage <= 0 || $rowperpage > 200) {
	$rowperpage = 70;
}
if ($row < 0) {
	$row = 0;
}
$columnIndex = $_POST['order'][0]['column']; // Column index
$columntrip_date = $_POST['columns'][$columnIndex]['data']; // Column trip_date
$columnSortOrder = $_POST['order'][0]['dir']; // asc or desc
$searchValue = $_POST['search']['value']; // Search value
$my_id = trim((string) ($_POST['my_id'] ?? ''));
$start_date =  $_POST['start_date'] ?? '';
$end_date =  $_POST['end_date'] ?? '';
$vehicle = $_POST['vehicle'] ?? '';
$searchArray = array();
$order_by = "ORDER BY trip_date desc,id desc";
// Empty / whitespace my_id means All Routes - do not force route_name='' (that yields zero rows).
if ($my_id === '' || $my_id === "route_name='' and" || $my_id === "route_name='' and ") {
	$my_id = '';
}

if($start_date!=""){
	$start_date =  date("Y-m-d", strtotime($_POST['start_date']));
$start_date = " trip_date >='$start_date' and ";
$order_by = "ORDER BY trip_date asc ,id asc";
} else {
	$start_date = "";
}
if($end_date!=""){
	$end_date =  date("Y-m-d", strtotime($_POST['end_date']));
$end_date = " trip_date <='$end_date' and ";
$order_by = "ORDER BY trip_date asc ,id asc";
} else {
	$end_date = "";
}
if($vehicle!=""){
	
$vehicle = " bus ='$vehicle' and ";
} else {
	$vehicle = "";
}
## Search 
$searchQuery = " ";
if($searchValue != ''){
	$searchQuery = " AND (trip_date LIKE :trip_date or 
        bus  LIKE :bus  ) ";
    $searchArray = array( 
		'trip_date'=>"%$searchValue%", 
		        'bus'=>"%$searchValue%",  
    );
}


$tenantEq = tessera_sql_tenant_eq();
$hasFilter = (trim((string) $my_id) !== '' || trim((string) $vehicle) !== '' || trim((string) $start_date) !== '' || trim((string) $end_date) !== '' || trim((string) $searchQuery) !== '');
if (!$hasFilter) {
	$stmt = $conn->prepare("SELECT COUNT(*) AS allcount FROM manifest WHERE {$tenantEq}");
	$stmt->execute();
	$records = $stmt->fetch();
	$totalRecords = (int) ($records['allcount'] ?? 0);
	$totalRecordwithFilter = $totalRecords;
} else {
	$stmt = $conn->prepare("SELECT COUNT(*) AS allcount FROM manifest WHERE {$tenantEq} AND ".$my_id." ".$vehicle." ".$start_date." ".$end_date."     1  ".$searchQuery);
	$stmt->execute($searchArray);
	$records = $stmt->fetch();
	$totalRecordwithFilter = (int) ($records['allcount'] ?? 0);
	$totalRecords = $totalRecordwithFilter;
}

## Fetch records - lean columns (avoid SELECT *)
$leanCols = "id, trip_date, open_date, code, analysed, route_name, to_be, paid, amount, expenses, income, ticket, bus, icon, checked, lock_icon";
$stmt = $conn->prepare("SELECT {$leanCols} FROM manifest WHERE {$tenantEq} AND ".$my_id." ".$vehicle."  ".$start_date." ".$end_date."    1  ".$searchQuery." ".$order_by." LIMIT :limit,:offset");

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
          
		
			"trip_date"=>date("l jS/m/Y", strtotime($row['trip_date'])),
			"open_date"=>$row['open_date'],
			"code"=>$row['code'],
			"analysed"=>$row['analysed'],
			"route_name"=>'<strong><span style="font-family:Monospace;">'.$row['route_name'].'</span></strong>',
			"to_be"=>number_format($row['to_be']),
			"paid"=>number_format($row['paid']),
			"cargo"=>number_format($row['amount'],2),
			"expenses"=>number_format($row['expenses'],2),
			"income"=>number_format($row['income'],2),
			"ticket"=>number_format($row['ticket'],2),
			"bus"=>trim(($row['bus'] ?? '') . ' ' . ($row['icon'] ?? '')),
			/* Legacy alias - some cached clients still request column data 'buss' */
			"buss"=>trim(($row['bus'] ?? '') . ' ' . ($row['icon'] ?? '')),
			"balance"=>number_format($row['income']-$row['expenses'],2),
			"balance_view"=>$row['income']-$row['expenses'],
			"insert"=>'<a class="btn btn-success btn-sm sr-act-btn" title="Insert" href="compose_vehicle.php?route_name='.rawurlencode((string)$row['route_name']).'&bus='.rawurlencode((string)$row['bus']).'&trip_date='.rawurlencode((string)$row['trip_date']).'&checked='.rawurlencode(base64_encode((string)$row['checked'])).'&main_page=manifest.php"><i class="bx bx-plus"></i></a>',
			"view"=>'<a class="btn btn-primary btn-sm sr-act-btn" title="View" href="manifest_view.php?route_name='.rawurlencode((string)$row['route_name']).'&bus='.rawurlencode((string)$row['bus']).'&trip_date='.rawurlencode((string)$row['trip_date']).'&checked='.rawurlencode(base64_encode((string)$row['checked'])).'&main_page=manifest.php"><i class="bx bx-show"></i></a>',
			"update"=>'<button type="button" class="btn btn-warning btn-sm sr-act-btn js-manifest-update" title="Update"'
				.' data-id="'.htmlspecialchars((string)$row['id'], ENT_QUOTES, 'UTF-8').'"'
				.' data-route="'.htmlspecialchars((string)$row['route_name'], ENT_QUOTES, 'UTF-8').'"'
				.' data-trip-date="'.htmlspecialchars((string)$row['trip_date'], ENT_QUOTES, 'UTF-8').'"'
				.' data-bus="'.htmlspecialchars((string)$row['bus'], ENT_QUOTES, 'UTF-8').'"'
				.' data-bs-toggle="offcanvas" data-bs-target="#offcanvasTop" aria-controls="offcanvasTop">'
				.'<i class="bx bx-pencil"></i></button>',
			"lock"=>'<button type="button" class="btn btn-warning btn-sm sr-act-btn js-manifest-lock" title="Lock"'
				.' data-bus="'.htmlspecialchars((string)$row['bus'], ENT_QUOTES, 'UTF-8').'"'
				.' data-trip-date="'.htmlspecialchars((string)$row['trip_date'], ENT_QUOTES, 'UTF-8').'"'
				.' data-bs-toggle="modal" data-bs-target="#lockmodal">'
				.'<i class="bx '.htmlspecialchars(str_replace(array('typcn-','-outline'), array('bx-',''), (string)$row['lock_icon']), ENT_QUOTES, 'UTF-8').'"></i></button>',
			"delete"=>'<button type="button" class="btn btn-danger btn-sm sr-act-btn js-manifest-delete" title="Delete"'
				.' data-id="'.htmlspecialchars((string)$row['id'], ENT_QUOTES, 'UTF-8').'"'
				.' data-bus="'.htmlspecialchars((string)$row['bus'], ENT_QUOTES, 'UTF-8').'"'
				.' data-trip-date="'.htmlspecialchars((string)$row['trip_date'], ENT_QUOTES, 'UTF-8').'"'
				.' data-route="'.htmlspecialchars((string)$row['route_name'], ENT_QUOTES, 'UTF-8').'">'
				.'<i class="bx bx-trash"></i></button>',
   );
}

## account 
$response  = array(
    "draw" => intval($draw),
    "recordsTotal" => intval($totalRecords),
    "recordsFiltered" => intval($totalRecordwithFilter),
    "data" => $data,
    /* Legacy DataTables keys */
    "iTotalRecords" => intval($totalRecords),
    "iTotalDisplayRecords" => intval($totalRecordwithFilter),
    "aaData" => $data
);

header('Content-Type: application/json; charset=utf-8');
echo json_encode($response);
