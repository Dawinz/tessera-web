<?php
include 'config.php';
$date_s = $_POST['date_s'];
$date2_s = $_POST['date2_s'];
$trip_s = $_POST['trip_s'];
$trips_assign = $_POST['trips_assign'];
## Read value
$draw = $_POST['draw'];
$row = $_POST['start'];

$rowperpage = $_POST['length']; // Rows display per page
$columnIndex = $_POST['order'][0]['column']; // Column index
$columnName = $_POST['columns'][$columnIndex]['data']; // Column name
$columnSortOrder = $_POST['order'][0]['dir']; // asc or desc
$searchValue = $_POST['search']['value']; // Search value

## Search 
$searchQuery = " ";
if($searchValue != ''){
	$searchQuery = "and (fullname like '%".$searchValue."%' or 
        reciver like '%".$searchValue."%' or 
        fullname like'%".$searchValue."%') ";
}

## Total number of records without filtering
$sel = mysqli_query($db,"select count(*) as allcount from luggages_account");
$records = mysqli_fetch_assoc($sel);
$totalRecords = $records['allcount'];

## Total number of records with filtering
$sel = mysqli_query($db,"select count(*) as allcount from luggages_account WHERE 1 ".$searchQuery);
$records = mysqli_fetch_assoc($sel);
$totalRecordwithFilter = $records['allcount'];

## Fetch records
$empQuery = "select * from luggages_account WHERE trips='$trip_s' and date <= '$date2_s' and assign_trip = '$trips_assign'and 1 ".$searchQuery." order by ".$columnName." ".$columnSortOrder." limit ".$row.",".$rowperpage;
$empRecords = mysqli_query($db, $empQuery);
$data = array();

while ($row = mysqli_fetch_assoc($empRecords)) {
    $data[] = array(
    		"fullname"=>'<a href="view-email.php?u='.$row['id'].'">'.$row['fullname'].'</div>',
    		"reciver"=>$row['reciver'],
    		"phone_no"=>$row['phone_no'],
    		"trips"=>$row['trips'],
    		"assign_trip"=>$row['assign_trip'],
			"code"=>$row['code'],
			"amount"=>$row['amount'],
			"date"=>$row['date']
    	);
}

## Response
$response = array(
    "draw" => intval($draw),
    "iTotalRecords" => $totalRecords,
    "iTotalDisplayRecords" => $totalRecordwithFilter,
    "aaData" => $data
);

echo json_encode($response);
