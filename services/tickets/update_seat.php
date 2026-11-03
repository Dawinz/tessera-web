<?php
require_once __DIR__ . '/includes/tessera_staff_boot.php';
include 'connect.php';
require_once __DIR__ . '/includes/tessera_tenant.php';
date_default_timezone_set('Africa/Dar_es_Salaam');
$date_insert = date('Y-m-d', time());
$day = date('d', time());
$month = date('m', time());
$year = date('Y', time());
$date_time_insert = date('h:i A', time());
$new_time = date("h:i A", strtotime('+1 hours'));
$booking_timer = date("h:i:s A", strtotime('+10 minute'));
$new_seat = $_POST['bid'];
$prev_id = $_POST['ticket_no_original'];
$trip_end_tranit = $_POST['trip_end_tranit'];
$trip_start_tranit = $_POST['trip_start_tranit'];

$new_start_route = $_POST['start_location_shift_t'];

$trip_start = $_POST['trip_start'];
$trip_end = $_POST['trip_end'];

$new_seat_end_route = $_POST['ending_location_shift_t'];
$new_start_route = preg_replace('/_/', ' ', $new_start_route);
$new_seat_end_route = preg_replace('/_/', ' ', $new_seat_end_route);
$trip_start = preg_replace('/_/', ' ', $trip_start);
$trip_end = preg_replace('/_/', ' ', $trip_end);
$new_route_day_id = $_POST['route_day_id_shift_t'];
$new_trip_date = $_POST['trip_date_shift_t'];
$new_route_id = $_POST['route_id_shift_t'];
$new_bus = $_POST['bus_shift_t'];
$new_id = $_POST['new_id'];
$sort = date("Ymd", strtotime($new_trip_date));



$select_prev_info = mysqli_query($db, "SELECT seat_no,trip_date,route_id,route_day_id,status,transit,foc  FROM  booking WHERE id='$prev_id' AND " . tessera_sql_tenant_eq() . " ");
$select_num_prev_info = mysqli_num_rows($select_prev_info);
if ($select_num_prev_info != 0) {
	while ($get_prev_info = mysqli_fetch_array($select_prev_info)) {
		$seat_no = $get_prev_info['seat_no'];
		$trip_date_prev = $get_prev_info['trip_date'];
		$route_id_prev = $get_prev_info['route_id'];
		$route_day_id_prev = $get_prev_info['route_day_id'];
		$status = $get_prev_info['status'];
		$foc = $get_prev_info['foc'];
		$transit = $get_prev_info['transit'];

	}
}
$select14 = mysqli_query($db, "SELECT id,fare_price,currency_symbol,currency,route_id,main_id,start_route,end_route,route_type  FROM  routes WHERE id='$new_id' ");
$select_num14 = mysqli_num_rows($select14);
if ($select_num14 != 0) {
	while ($get134 = mysqli_fetch_array($select14)) {
		$id = $get134['id'];

		$currency_symbol = $get134['currency_symbol'];
		$currency = $get134['currency'];
		$route_id = $get134['route_id'];
		$route_id_main = $get134['route_id'];
		$start_route = $get134['start_route'];
		$end_route = $get134['end_route'];
		$main_id = $get134['main_id'];
		$route_type = $get134['route_type'];


	}
}

$select1356 = mysqli_query($db, "SELECT seats,state,dedicate_seat_price   FROM  vehicle WHERE name='$new_bus'");
$select_num1356 = mysqli_num_rows($select1356);
if ($select_num1356 != 0) {
	while ($get13356 = mysqli_fetch_array($select1356)) {


		$seats = $get13356['seats'];
		$state = $get13356['state'];
		$dedicate_seat_price = $get13356['dedicate_seat_price'];

	}
}



$select_route_id = mysqli_query($db, "SELECT name  FROM   route_points WHERE id='$trip_start_tranit' ");
$select_num_route_id = mysqli_num_rows($select_route_id);
if ($select_num_route_id != 0) {
	while ($get_route_id = mysqli_fetch_array($select_route_id)) {
		$trip_start_tranit = $get_route_id['name'];

	}
}
$select_route_id_end = mysqli_query($db, "SELECT name  FROM   route_points WHERE id='$trip_end_tranit' ");
$select_num_route_id_end = mysqli_num_rows($select_route_id_end);
if ($select_num_route_id_end != 0) {
	while ($get_route_id_end = mysqli_fetch_array($select_route_id_end)) {
		$trip_end_tranit = $get_route_id_end['name'];

	}
}

if ($route_type == "Main Route") {
	$fare_price = fare_price($db, $route_id_main, $state, $dedicate_seat_price, $transit, $foc, $new_seat);
	$update = mysqli_query($db, "UPDATE  booking SET start_location='$trip_start_tranit',ending_location='$trip_end_tranit',status = '$status', trip_date='$new_trip_date',bus='$new_bus',route_id='$route_id',seat_no='$new_seat',route_day_id='$new_route_day_id',start_route='$start_route',end_route='$end_route',cost='$fare_price',currency='$currency',initial='$currency_symbol' WHERE " . tessera_sql_tenant_eq() . " AND (id='$prev_id')");


} else {
	$fare_price = fare_price($db, $route_id_main, $state, $dedicate_seat_price, $transit, $foc, $new_seat);


	$update = mysqli_query($db, "UPDATE  booking SET start_location='$trip_start_tranit',ending_location='$trip_end_tranit',status = '$status', trip_date='$new_trip_date',bus='$new_bus',route_id='$main_id',sub_route_id='$route_id',seat_no='$new_seat',route_day_id='$new_route_day_id',start_route='$start_route',end_route='$end_route',cost='$fare_price',currency='$currency',initial='$currency_symbol' WHERE " . tessera_sql_tenant_eq() . " AND (id='$prev_id')");

}
$select1 = mysqli_query($db, "SELECT *  FROM  manifest WHERE  route_day_id='$new_route_day_id' and route_id='$new_route_id' and trip_date='$new_trip_date'  ");
$select_num1 = mysqli_num_rows($select1);
if ($select_num1 == 0) {


	$select_route_id = mysqli_query($db, "SELECT start_route,end_route,route_name  FROM  routes WHERE route_id='$new_route_id' ");
	$select_num_route_id = mysqli_num_rows($select_route_id);
	if ($select_num_route_id != 0) {
		while ($get_route_id = mysqli_fetch_array($select_route_id)) {

			$start_route = $get_route_id['start_route'];
			$end_route = $get_route_id['end_route'];
			$route_name = $get_route_id['route_name'];
		}
	}
	$select1356 = mysqli_query($db, "SELECT *  FROM  vehicle WHERE name='$new_bus'");
	$select_num1356 = mysqli_num_rows($select1356);
	if ($select_num1356 != 0) {
		while ($get13356 = mysqli_fetch_array($select1356)) {


			$driver = $get13356['driver'];
			$conductor = $get13356['conductor'];
			$seats = $get13356['seats'];
		}
	}

	$select13 = mysqli_query($db, "SELECT *  FROM  route_day WHERE id='$new_route_day_id'");
	$select_num13 = mysqli_num_rows($select13);
	if ($select_num13 != 0) {
		while ($get133 = mysqli_fetch_array($select13)) {


			$route_id = $get133['route_id'];
			$hours = $get133['12hours'];
			$bus = $get133['bus'];



		}
	}
	$insert = mysqli_query($db, "INSERT INTO manifest (bus,trip_date,insert_date,seats,amount,start_location,end_location,start_time,route_day_id,day,month,year,driver,conductor,route_id,route_name) VALUES
 ('$new_bus','$new_trip_date','$date_insert','1','$fare_price','$start_route','$end_route','$hours','$new_route_day_id','$day','$month','$year','$driver','$conductor','$new_route_id','$route_name')");





	include 'update/booked_seat.php';

} else {



	include 'update/booked_seat.php';

}

function fare_price($db, $route_id_main, $state, $dedicate_seat_price, $transit, $foc, $seat_id)
{

	
	if ($transit != "") {
		$price = 0;
	} else if ($foc != "") {
		$price = 0;

	} else {
		$price_array = [];
		$select2 = mysqli_query($db, "SELECT dedicate_seat_price,price,price_state  FROM  ticket_price  where route_id='$route_id_main' and vehicle_type='$state' ");
		$select_num2 = mysqli_num_rows($select2);
		if ($select_num2 != 0) {
			while ($get12 = mysqli_fetch_array($select2)) {
				$price = $get12['price'];
				$seats = $get12['dedicate_seat_price'];
				$price_state = $get12['price_state'];
				$price_array[] = [
					"price" => $price,
					"seats" => $seats,
					"price_state" => $price_state,
				];

			}
		}
		$default_price = "";
		foreach ($price_array as $row) {

			$seats = $row["seats"];

			$price_state = $row["price_state"];
			if ($price_state == "Default") {
				$default_price = $row["price"];
			}

			if ($seats == $dedicate_seat_price) {

				if ($seats == "") {
					$price = $default_price;
				} else {
					$codes = explode(',', $seats);
					if (in_array($seat_id, $codes)) {
						$price = $row["price"];
					} else {
						$price = $default_price;
					}

				}


			}

		}
	}



	return $price;

}