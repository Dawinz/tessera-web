<?php
require_once __DIR__ . '/includes/tessera_staff_boot.php';
if (session_status() !== PHP_SESSION_ACTIVE) {
	session_start();
}
include 'config2.php';
include 'connect.php';
header('Content-Type: application/json; charset=utf-8');
$username=$_SESSION['username'];
$route_array=array();
$select2=mysqli_query($db,"SELECT route_full_name,status  FROM  assigned_routes where username='$username' order by preference desc");
$select_num2=mysqli_num_rows($select2);
if($select_num2 !=0){
   	WHILE($get12=mysqli_fetch_array($select2)){
    	    $route_full_name =$get12['route_full_name'];  
    	    $status =$get12['status'];  
			

   $fullname_man="$route_full_name.$status";

array_push($route_array,$fullname_man);
			
	}
}
$new_array =  implode("$", $route_array);
$cargo_role = '';
$role_row = mysqli_query($db, "SELECT role FROM users WHERE username='" . mysqli_real_escape_string($db, (string) $username) . "' LIMIT 1");
if ($role_row && ($role_info = mysqli_fetch_assoc($role_row))) {
	$cargo_role = (string) ($role_info['role'] ?? '');
}
$GLOBALS['tessera_cargo_checkout_open'] = (strcasecmp($cargo_role, 'Super Administrator') === 0);
function options_change($route_name_check,$new_array) {
	$my_array1 = explode("$", $new_array);
	$route_name_check = "$route_name_check.sender";
if (strpos($new_array, $route_name_check) !== false) {
  return "";
} else {
  
	return "disabled";
}
return $new_array;
}
function options_change_vs($route_name_check,$new_array) {
	if (!empty($GLOBALS['tessera_cargo_checkout_open'])) {
		return "";
	}
	$my_array1 = explode("$", $new_array);
	$route_name_check = "$route_name_check.sender";
if (strpos($new_array, $route_name_check) !== false) {
return "none";
} else {
  
	return "";
}
return $new_array;
}
function options_change_none($route_name_check,$new_array) {
	$my_array1 = explode("$", $new_array);
	$route_name_check = "$route_name_check.sender";
if (strpos($new_array, $route_name_check) !== false) {
  return "";
} else {
  
	return "none";
}
return $new_array;
}

function block_sms($disable){
if($disable ==""){
	$disable="cursor: pointer;";
return $disable;
}else{
$disable="pointer-events: none;";
return $disable;

}
}
function block_manifest($disable){
if($disable ==""){
return;
}else{
$disable='<i class="uil uil-lock text-danger "></i>';
return $disable;

}
}
function remark_check($remark,$date,$name,$status,$route_name,$driver,$conductor,$code,$remark_html){
	$href = 'vehicle_report_new.php?date=' . rawurlencode((string) $date)
		. '&date2=' . rawurlencode((string) $date)
		. '&date3=' . rawurlencode((string) $name)
		. '&open=' . rawurlencode((string) $status)
		. '&route=' . $route_name
		. '&driver=' . $driver
		. '&Conductor=' . $conductor
		. '&code=' . $code
		. '&row_remark=yes&remark=' . $remark_html;
	$hrefSafe = htmlspecialchars($href, ENT_QUOTES, 'UTF-8');
	if ($remark != '') {
		$decoded = base64_decode((string) $remark);
		$plain = trim(html_entity_decode(strip_tags((string) $decoded), ENT_QUOTES, 'UTF-8'));
		if ($plain === '') {
			$plain = 'View remark';
		}
		if (function_exists('mb_strlen') && mb_strlen($plain) > 36) {
			$plain = mb_substr($plain, 0, 36) . '...';
		} elseif (strlen($plain) > 36) {
			$plain = substr($plain, 0, 36) . '...';
		}
		return '<a class="sr-cell-link" href="' . $hrefSafe . '"><i class="uil uil-comment-alt-lines" aria-hidden="true"></i> '
			. htmlspecialchars($plain, ENT_QUOTES, 'UTF-8') . '</a>';
	}
	return '<a class="sr-cell-link text-muted" href="' . $hrefSafe . '"><i class="uil uil-plus" aria-hidden="true"></i> Add remark</a>';
}

function Remark_html($remark){
	if($remark==""){
		$remark = "<p>Add Remark</p>";
	}else{
		$remark = base64_decode($remark);
	}
	return $remark;
}

/** Money cell: primary amount + optional converted line with exchange icon. */
function sr_manifest_money($primary, $secondary, $tone = 'neutral') {
	$primaryNum = (float) $primary;
	$secondaryNum = (float) $secondary;
	$html = '<div class="sr-amt">'
		. '<span class="sr-amt-main">' . number_format($primaryNum) . '</span>';
	if (abs($secondaryNum) > 0.00001) {
		$html .= '<span class="sr-amt-sub" title="Converted amount">'
			. '<i class="uil uil-exchange-alt" aria-hidden="true"></i>'
			. '<span>' . number_format($secondaryNum) . '</span>'
			. '</span>';
	}
	$html .= '</div>';
	return $html;
}

function sr_manifest_currency($currency) {
	$label = trim((string) $currency);
	if ($label === '') {
		return '<span class="text-muted">-</span>';
	}
	return '<span class="sr-cell-ico"><i class="uil uil-money-bill" aria-hidden="true"></i>'
		. htmlspecialchars($label, ENT_QUOTES, 'UTF-8') . '</span>';
}

function sr_manifest_id($id, $code, $lockedHtml) {
	$codeSafe = htmlspecialchars((string) $code, ENT_QUOTES, 'UTF-8');
	return '<span class="sr-id-cell">'
		. '<i class="fa fa-spinner fa-spin text-danger loader' . (int) $id . '" style="font-size:14px;display:none;"></i>'
		. ($lockedHtml !== '' && $lockedHtml !== null ? '<span class="me-1">' . $lockedHtml . '</span>' : '')
		. '<span class="sr-cell-ico"><i class="uil uil-ticket" aria-hidden="true"></i>'
		. '<span class="sr-mono">' . $codeSafe . '</span></span>'
		. '</span>';
}

function sr_manifest_route($route) {
	$safe = htmlspecialchars((string) $route, ENT_QUOTES, 'UTF-8');
	if ($safe === '') {
		return '<span class="text-muted">-</span>';
	}
	return '<span class="sr-cell-ico"><i class="uil uil-map-marker" aria-hidden="true"></i><span>' . $safe . '</span></span>';
}

function sr_manifest_vehicle($name) {
	return '<span class="sr-cell-ico"><i class="uil uil-bus" aria-hidden="true"></i>'
		. '<span class="sr-mono">' . htmlspecialchars((string) $name, ENT_QUOTES, 'UTF-8') . '</span></span>';
}

function sr_manifest_date($date) {
	$ts = strtotime((string) $date);
	$label = $ts ? date('d M Y', $ts) : htmlspecialchars((string) $date, ENT_QUOTES, 'UTF-8');
	return '<span class="sr-cell-ico text-nowrap"><i class="uil uil-calender" aria-hidden="true"></i>' . $label . '</span>';
}

function sr_manifest_person($name, $role) {
	$safe = htmlspecialchars((string) $name, ENT_QUOTES, 'UTF-8');
	if ($safe === '') {
		return '<span class="text-muted">-</span>';
	}
	$icon = ($role === 'Conductor') ? 'uil-user-circle' : 'uil-user';
	return '<span class="sr-cell-ico"><i class="uil ' . $icon . '" aria-hidden="true"></i><span>' . $safe . '</span></span>';
}

/**
 * Tiny actions trigger - one shared menu in vehicle_view.php fills hrefs from data-*.
 * Avoids ~2KB of duplicated menu HTML / query strings per row.
 */
function sr_manifest_options_menu(array $row, $new_array) {
	$id = (int) $row['id'];
	$name = (string) $row['Name'];
	$date = (string) $row['date'];
	$route = (string) $row['Route'];
	$driver = (string) $row['Driver'];
	$conductor = (string) $row['Conductor'];
	$code = (string) $row['code'];
	$status = (string) $row['status'];
	$currency = (string) $row['currency'];
	$canEdit = options_change($route, $new_array) === '' ? '1' : '0';
	/* options_change_vs / options_change_none return "" (allowed) or "none" (blocked). */
	$canCheckout = (options_change_vs($route, $new_array) === 'none') ? '0' : '1';
	$canLoad = (options_change_none($route, $new_array) === 'none') ? '0' : '1';
	$canSms = (block_sms($row['disable2'] ?? '') === 'pointer-events: none;') ? '0' : '1';
	$h = static function ($v) {
		return htmlspecialchars((string) $v, ENT_QUOTES, 'UTF-8');
	};

	return '<button type="button" class="btn btn-sm sr-more-btn js-manifest-more"'
		. ' data-id="' . $id . '"'
		. ' data-name="' . $h($name) . '"'
		. ' data-date="' . $h($date) . '"'
		. ' data-route="' . $h($route) . '"'
		. ' data-driver="' . $h($driver) . '"'
		. ' data-conductor="' . $h($conductor) . '"'
		. ' data-code="' . $h($code) . '"'
		. ' data-status="' . $h($status) . '"'
		. ' data-currency="' . $h($currency) . '"'
		. ' data-remark="' . $h(base64_encode((string) ($row['remark_html'] ?? ''))) . '"'
		. ' data-can-edit="' . $canEdit . '"'
		. ' data-can-checkout="' . $canCheckout . '"'
		. ' data-can-load="' . $canLoad . '"'
		. ' data-can-sms="' . $canSms . '"'
		. ' aria-label="More actions">'
		. '<i class="mdi mdi-dots-horizontal font-size-18"></i></button>';
}

## Read value
$draw = $_POST['draw'];
$row = (int) ($_POST['start'] ?? 0);
$rowperpage = (int) ($_POST['length'] ?? 25); // Rows display per page
if ($rowperpage <= 0 || $rowperpage > 200) {
	$rowperpage = 25;
}
if ($row < 0) {
	$row = 0;
}
$columnIndex = $_POST['order'][0]['column']; // Column index
$columnName = $_POST['columns'][$columnIndex]['data']; // Column name
$columnSortOrder = $_POST['order'][0]['dir']; // asc or desc
$searchValue = $_POST['search']['value']; // Search value
$route = $_POST['route'];
$route_name_select = $_POST['route_name_select'];

$start_date = $_POST['start_date'];
$end_date = $_POST['end_date'];
$vehicle = $_POST['vehicle'];
$hasDateSort = false;
try {
	$chk = $conn->query("SHOW COLUMNS FROM vehicle_name LIKE 'date_sort'");
	$hasDateSort = $chk && $chk->fetch();
} catch (Throwable $e) {
	$hasDateSort = false;
}
$order_by = $hasDateSort ? "ORDER BY date_sort DESC, id DESC" : "ORDER BY date DESC, id DESC";
if($start_date!=""){
	$startEsc = addslashes($start_date);
	$start_date = $hasDateSort
		? "date_sort >= '$startEsc' and"
		: "date >= '$startEsc' and";
	$order_by = $hasDateSort ? "ORDER BY date_sort ASC, id ASC" : "ORDER BY date ASC, id ASC";
}
if($end_date!=""){
	$endEsc = addslashes($end_date);
	$end_date = $hasDateSort
		? "date_sort <= '$endEsc' and"
		: "date <= '$endEsc' and";
	$order_by = $hasDateSort ? "ORDER BY date_sort ASC, id ASC" : "ORDER BY date ASC, id ASC";
}
if($vehicle!=""){
	$vehicle = "Name = '" . addslashes($vehicle) . "' and";
}else{
	$vehicle ="";
}

$searchArray = array();

## Search 
$searchQuery = " ";
if($searchValue != ''){
	$searchQuery = " AND (Name LIKE :Name or 
        Driver  LIKE :Driver  OR 
        date LIKE :date OR Conductor Like :Conductor ) ";
    $searchArray = array( 
        'Name'=>"%$searchValue%", 
        'Driver'=>"%$searchValue%",
        'date'=>"%$searchValue%",
		'Conductor'=>"%$searchValue%"
    );
}

## Totals - one COUNT when unfiltered; otherwise filtered COUNT only (skip second full scan)
$routeFilter = trim((string) $route_name_select);
$hasFilter = ($searchValue != '' || $vehicle != '' || $start_date != '' || $end_date != '' || $routeFilter !== '');
if (!$hasFilter) {
	$stmt = $conn->prepare("SELECT COUNT(*) AS allcount FROM vehicle_name");
	$stmt->execute();
	$records = $stmt->fetch();
	$totalRecords = (int) ($records['allcount'] ?? 0);
	$totalRecordwithFilter = $totalRecords;
} else {
	$stmt = $conn->prepare("SELECT COUNT(*) AS allcount FROM vehicle_name WHERE   $vehicle $start_date $end_date $route_name_select   1 ".$searchQuery);
	$stmt->execute($searchArray);
	$records = $stmt->fetch();
	$totalRecordwithFilter = (int) ($records['allcount'] ?? 0);
	/* DataTables still wants recordsTotal; avoid a second full-table COUNT when filtered. */
	$totalRecords = $totalRecordwithFilter;
}

## Fetch records - lean columns only (never SELECT * / img SVG blobs)
$leanCols = "id, code, Name, Driver, Conductor, Route, date, status, disable2, currency, luggage_no, to_pay, paid, l_to_be, l_paid, l_amount, remark_test, remark_html";
$stmt = $conn->prepare("SELECT $leanCols FROM vehicle_name WHERE $vehicle $start_date $end_date $route_name_select   1 ".$searchQuery." $order_by LIMIT :limit,:offset");

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
            "code"=> sr_manifest_id($row['id'], $row['code'], block_manifest($row['disable2'])),
			"Name"=> sr_manifest_vehicle($row['Name']),
			"Driver"=> sr_manifest_person($row['Driver'], 'Driver'),
            "Route"=> sr_manifest_route($row['Route']),
			"Conductor"=> sr_manifest_person($row['Conductor'], 'Conductor'),
			"date"=> sr_manifest_date($row['date']),
			"status"=>$row['status'],
			"remark_test"=>''.remark_check($row['remark_test'],$row['date'],$row['Name'],$row['status'],base64_encode($row['Route']),base64_encode($row['Driver']),base64_encode($row['Conductor']),base64_encode($row['code']),urlencode($row['remark_html'] )),
			
		
	 "luggage_no"=>$row['luggage_no'],
	 "options"=> sr_manifest_options_menu($row, $new_array),
	 "to_pay"=> sr_manifest_money($row['to_pay'], $row['l_to_be'], 'due'),
	 "paid"=> sr_manifest_money($row['paid'], $row['l_paid'], 'paid'),
	 "total"=> sr_manifest_money((float) $row['paid'] + (float) $row['to_pay'], $row['l_amount'], 'total'),
	 "currency"=> sr_manifest_currency($row['currency']),
		
        );
}

## account 
$response  = array(
    "draw" => intval($draw),
    "iTotalRecords" => $totalRecords,
    "iTotalDisplayRecords" => $totalRecordwithFilter,
    "aaData" => $data
);

$json = json_encode($response, JSON_INVALID_UTF8_SUBSTITUTE | JSON_UNESCAPED_UNICODE);
echo $json !== false ? $json : '{"draw":0,"iTotalRecords":0,"iTotalDisplayRecords":0,"aaData":[],"error":"encode"}';
