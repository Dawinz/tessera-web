<?php
require_once __DIR__ . '/includes/tessera_tenant.php';

require_once __DIR__ . '/includes/transfer_direct_visit.php';
if (tessera_transfer_direct_visit('Transfer selected seats')) {
	return;
}
date_default_timezone_set('Africa/Dar_es_Salaam');
$date_insert = date('Y-m-d', time());
$day = date('d', time());
$month = date('m', time());
$year = date('Y', time());
$date_time_insert = date('h:i A', time());
$new_time = date("h:i A", strtotime('+1 hours'));
$booking_timer = date("h:i:s A", strtotime('+10 minute'));

$select_all_seats = $_POST['seat_shift_t'];

    $my_string = $select_all_seats;    

    // passing "," as the delimiter

    $my_array1 = explode(",", $my_string);



for($i = 0;$i < count($my_array1);$i++){
if($i==0){

$first_seat = $my_array1[$i];

}
}


?>
<?php
include 'connect.php';
$new_seat = $_POST['bid'];
$prev_id = $first_seat;
$new_start_route = $_POST['start_location_shift_t'];

$new_seat_end_route = $_POST['ending_location_shift_t'];
$new_start_route= preg_replace('/_/',' ', $new_start_route);
$new_seat_end_route= preg_replace('/_/',' ', $new_seat_end_route);
$new_route_day_id = $_POST['route_day_id_shift_t'];
$new_trip_date= $_POST['trip_date_shift_t'];
$new_route_id = $_POST['route_id_shift_t'];
$new_bus = $_POST['bus_shift_t'];
$current_route_id = $_POST['current_route_id'];
$start_location = $_POST['start_location'];
$end_location = $_POST['end_location'];
$print_id = $_POST['print_id'];


$select_prev_info=mysqli_query($db,"SELECT seat_no,trip_date,route_id,route_day_id  FROM  booking WHERE " . tessera_sql_tenant_eq() . " AND (id='$prev_id')");
$select_num_prev_info=mysqli_num_rows($select_prev_info);
if($select_num_prev_info !=0){
   	WHILE($get_prev_info=mysqli_fetch_array($select_prev_info)){
    	    $seat_no =$get_prev_info['seat_no'];		
    	    $trip_date_prev =$get_prev_info['trip_date'];		
    	    $route_id_prev  =$get_prev_info['route_id'];		
    	    $route_day_id_prev  =$get_prev_info['route_day_id'];		
		
}}

$select14=mysqli_query($db,"SELECT id,fare_price,currency_symbol,currency,route_id,main_id,start_route,end_route  FROM  routes WHERE id='$current_route_id' ");
$select_num14=mysqli_num_rows($select14);
if($select_num14 !=0){
   	WHILE($get134=mysqli_fetch_array($select14)){
    	    $id =$get134['id'];		
		$fare_price =$get134['fare_price']; 
		$currency_symbol =$get134['currency_symbol'];	
		$currency =$get134['currency'];
			$route_id =$get134['route_id']; 
			$start_route =$get134['start_route']; 
			$end_route =$get134['end_route']; 
			$main_id =$get134['main_id']; 

}}
	if($main_id==""){
		$status="complete";
	}else{
		$status="half_complete";
		$route_id = $main_id;
	}

if($main_id==""){
	$update=mysqli_query($db,"UPDATE  booking SET start_location='$start_location', ending_location='$end_location',transfer_to='$seat_no',status = '$status', print_id='$print_id',trip_date='$new_trip_date',bus='$new_bus',route_id='$route_id',seat_no='$new_seat',route_day_id='$new_route_day_id',start_route='$start_route',end_route='$end_route',cost='$fare_price',currency='$currency',initial='$currency_symbol' WHERE " . tessera_sql_tenant_eq() . " AND (id='$prev_id')");
	
}else{

	$update=mysqli_query($db,"UPDATE  booking SET start_location='$start_location', ending_location='$end_location',transfer_to='$seat_no', status = '$status', print_id='$print_id', trip_date='$new_trip_date',bus='$new_bus',route_id='$main_id',sub_route_id='$route_id',seat_no='$new_seat',route_day_id='$new_route_day_id',start_route='$start_route',end_route='$end_route',cost='$fare_price',currency='$currency',initial='$currency_symbol' WHERE " . tessera_sql_tenant_eq() . " AND (id='$prev_id')");

}


///// very important area  dont change,this changes the ticket array :





$select_all_seats = $_POST['seat_shift_t'];
    $my_string = $select_all_seats;    

    // passing "," as the delimiter

    $my_array1 = explode(",", $my_string);



for($i = 0;$i < count($my_array1);$i++){
$first_seat2 = $my_array1[$i];

if($first_seat2==$prev_id){
	
}else{
	echo "$first_seat2 ";

}
}

//// end of important area
$route_day_id=$new_route_day_id;


$trip_date = $new_trip_date;

$select13=mysqli_query($db,"SELECT route_id,12hours,bus  FROM  route_day WHERE id='$new_route_day_id'");
$select_num13=mysqli_num_rows($select13);
if($select_num13 !=0){
   	WHILE($get133=mysqli_fetch_array($select13)){
 

 $route_id= $get133['route_id'];
 $hours= $get133['12hours'];
 $bus= $get133['bus'];

	

}}

$select1356=mysqli_query($db,"SELECT seats,conductor,driver  FROM  vehicle WHERE name='$bus'");
$select_num1356=mysqli_num_rows($select1356);
if($select_num1356 !=0){
   	WHILE($get13356=mysqli_fetch_array($select1356)){
 

 $driver= $get13356['driver'];
 $conductor= $get13356['conductor'];
 $seats= $get13356['seats'];

 

}}
$select1=mysqli_query($db,"SELECT *  FROM  manifest WHERE  route_day_id='$new_route_day_id' and route_id='$new_route_id' and trip_date='$new_trip_date'  ");
$select_num1=mysqli_num_rows($select1);
if($select_num1 ==0){
$select_route_id=mysqli_query($db,"SELECT start_route,end_route,route_name  FROM  routes WHERE route_id='$new_route_id' ");
$select_num_route_id=mysqli_num_rows($select_route_id);
if($select_num_route_id !=0){
   	WHILE($get_route_id=mysqli_fetch_array($select_route_id)){

			$start_route =$get_route_id['start_route']; 
			$end_route =$get_route_id['end_route']; 
			$route_name =$get_route_id['route_name']; 
}
}

$insert=mysqli_query($db,"INSERT INTO manifest (bus,trip_date,insert_date,seats,amount,start_location,end_location,start_time,route_day_id,day,month,year,driver,conductor,route_id,route_name) VALUES
 ('$bus','$new_trip_date','$date_insert','1','$fare_price','$start_route','$end_route','$hours','$new_route_day_id','$day','$month','$year','$driver','$conductor','$new_route_id','$route_name')");	
$route_day_id=$route_day_id_prev;
$route_id =$route_id_prev;
$trip_date = $trip_date_prev;
$delete_empty_manifest = "delete_previous";

	include 'update/booked_seat.php';
	
}else{
	
$select_old=mysqli_query($db,"SELECT *  FROM booking WHERE " . tessera_sql_tenant_eq() . " AND (route_id='$route_id_prev' and route_day_id='$route_day_id_prev' and trip_date='$trip_date_prev' and delete_book='')");
$select_num_old=mysqli_num_rows($select_old);

if($select_num_old==0){
$delete=mysqli_query($db,"DELETE from manifest  WHERE  route_day_id='$route_day_id_prev' and route_id='$route_id_prev' and trip_date='$trip_date_prev'");


}else{
		
	if($route_day_id_prev !=$route_day_id){
$route_day_id=$route_day_id_prev;
$route_id =$route_id_prev;
$trip_date = $trip_date_prev;

	include 'update/booked_seat.php';
	}
}


	
	include 'update/booked_seat.php';
	


}
mysqli_query($db,"DELETE from manifest WHERE bus = ''");


?>