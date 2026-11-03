<?php
require_once __DIR__ . '/includes/tessera_tenant.php';

session_start();
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
include 'connect.php';
date_default_timezone_set('Africa/Dar_es_Salaam');
$date_insert = date('Y-m-d', time());

$date_time_insert = date('h:i A', time());
$print = date('h:i', time());
$new_time = date("h:i A", strtotime('+1 hours'));
$booking_timer = date("h:i:s A", strtotime('+10 minute'));
$tesseraTenantId = (int) tessera_current_tenant_id();

$select18 = mysqli_query($db, "SELECT *  FROM users WHERE username='" . @$_SESSION['username'] . "'  ");
$select_num18 = mysqli_num_rows($select18);
$error = "";
$route_id_main = $_POST["route_id_main"];

if ($select_num18 != 0) {

	$n = 15;
	$print_id = bin2hex(random_bytes($n));
	$print_id = "p$print_id$print";



	for ($count = 0; $count < count($_POST["firstname"]); $count++) {

		$firstname = $_POST["firstname"][$count];
		$firstname = str_replace("'", "^", $firstname);
		$seat_id = $_POST["seat_id"][$count];
		$lastname = $_POST["lastname"][$count];
		$lastname = str_replace("'", "^", $lastname);
		$phone_no = $_POST["phone_no"][$count];
		$id_type = "";
		$female = $_POST["female"][$count];
		$male = $_POST["male"][$count];
		$verification = @$_POST["verification"][$count];

		$start_location = @$_POST["start_location"][$count];
		$ending_location = @$_POST["ending_location"][$count];
		$starting_point = @$_POST["starting_point"][$count];
		$end_point = @$_POST["end_point"][$count];
		$route_day_id = @$_POST["route_day_id"][$count];
		$sub_route_id = @$_POST["sub_route_id"][$count];
		$trip_date = @$_POST["trip_date"][$count];
		$country = @$_POST["countries"][$count];
		$trip_date = date("Y-m-d", strtotime($trip_date));

		$day = date('d', strtotime($trip_date));
		$month = date('m', strtotime($trip_date));
		$year = date('Y', strtotime($trip_date));

		$sort = "$year$month$day";

		$route_id = @$_POST["route_id"][$count];
		$inter_phone = @$_POST["inter_phone"][$count];

		$start_location = @preg_replace('/_/', ' ', $start_location);
		$ending_location = @preg_replace('/_/', ' ', $ending_location);


		if ($female != "") {
			$gender = $female;
		} else if ($male != "") {
			$gender = $male;
		} else {
			$gender = "";
		}

		$reson = "";
		if (isset($_POST['transit'][$count])) {
			$transit = $_POST['transit'][$count];
			$reson = @$_POST["trans_info"][$count];

		} else {
			$transit = "";
		}
		if (isset($_POST['foc'][$count])) {
			$foc = $_POST['foc'][$count];
			$reson = @$_POST["Foc_info"][$count];
		} else {


			$foc = "";
		}

		$fullname = "$start_location to $ending_location";




		$select13 = mysqli_query($db, "SELECT *  FROM  route_day WHERE id='$route_day_id'");
		$select_num13 = mysqli_num_rows($select13);
		if ($select_num13 != 0) {
			while ($get133 = mysqli_fetch_array($select13)) {


				$route_id = $get133['route_id'];
				$hours = $get133['12hours'];
				$bus = $get133['bus'];

			}
		}


		$select14 = mysqli_query($db, "SELECT *  FROM  routes WHERE route_id = '$route_id_main'");
		$select_num14 = mysqli_num_rows($select14);
		if ($select_num14 != 0) {
			while ($get134 = mysqli_fetch_array($select14)) {
				$id = $get134['id'];
				$start_route = $get134['start_route'];
				$end_route = $get134['end_route'];
				$route_id = $get134['route_id'];
				$fare_price = $get134['fare_price'];
				$route_name = $get134['route_name'];
				$currency = $get134['currency'];
				$main_id = $get134['main_id'];
				$route_type = $get134['route_type'];
			}

		}



		$select1356 = mysqli_query($db, "SELECT *  FROM  vehicle WHERE name='$bus'");
		$select_num1356 = mysqli_num_rows($select1356);
		if ($select_num1356 != 0) {
			while ($get13356 = mysqli_fetch_array($select1356)) {


				$driver = $get13356['driver'];
				$conductor = $get13356['conductor'];
				$seats = $get13356['seats'];



			}
		}

		$select146 = mysqli_query($db, "SELECT *  FROM  routes WHERE route_id='$route_id' ");
		$select_num146 = mysqli_num_rows($select146);
		if ($select_num146 != 0) {
			while ($get1346 = mysqli_fetch_array($select146)) {

				$start_route_main = $get1346['start_route'];
				$end_route_main = $get1346['end_route'];






			}
		}
		$select173 = mysqli_query($db, "SELECT *  FROM  currency Where country='$currency'");
		$select_num173 = mysqli_num_rows($select173);
		if ($select_num173 != 0) {
			while ($get1373 = mysqli_fetch_array($select173)) {

				$rate = $get1373['rate'];
				$initial = $get1373['initial'];

			}
		}

		if ($_POST['adnce_info'][$count] == "") {
			$adnce_info = 0;
			$advance_status = "";
			$ticket_amount_left = 0;
			$disable = "disabled";

		} else {
			$adnce_info = @$_POST["adnce_info"][$count];
			$advance_status = "yes";
			$disable = "";
			$ticket_amount_left = $fare_price - $adnce_info;


		}


		$select1356 = mysqli_query($db, "SELECT seats,state,dedicate_seat_price  FROM  vehicle WHERE name='$bus'");
		$select_num1356 = mysqli_num_rows($select1356);
		if ($select_num1356 != 0) {
			while ($get13356 = mysqli_fetch_array($select1356)) {


				$seats = $get13356['seats'];
				$state = $get13356['state'];
				$dedicate_seat_price = $get13356['dedicate_seat_price'];
				if ($state == "vip") {



					$select_fair = mysqli_query($db, "SELECT vip_price  FROM  routes WHERE start_route='$start_location' and end_route='$ending_location'");
					$select_num_fair = mysqli_num_rows($select_fair);
					if ($select_num_fair != 0) {
						while ($get_fair = mysqli_fetch_array($select_fair)) {


							$vip_price = $get_fair['vip_price'];
							$fare_price = $vip_price;


						}
					}


				}
			}
		}
		$foc_status = "";

		if (isset($_POST['foc'][$count]) or isset($_POST['transit'][$count])) {
			$foc_status = "yes";
			$fare_price = 0;

		}
		$select1 = mysqli_query($db, "SELECT *  FROM  booking WHERE " . tessera_sql_tenant_eq() . " AND (route_day_id='$route_day_id' and trip_date='$trip_date' and seat_no='$seat_id' and route_id ='$route_id' and delete_book='') ORDER BY ID DESC LIMIT 1 ");
		$select_num1 = mysqli_num_rows($select1);
		if ($select_num1 != 0) {
			while ($get13 = mysqli_fetch_array($select1)) {
				$start_route_r = $get13['start_route'];
				$end_route_r = $get13['end_route'];

			}



			if (($start_location == $start_route_main) && ($ending_location == $end_route_main)) {

				$error = "full_no";
			} else {
				$select_position = mysqli_query($db, "SELECT *  FROM  routes   WHERE route_id = '$sub_route_id' ");
				$select_num_position = mysqli_num_rows($select_position);
				if ($select_num_position != 0) {
					while ($get_position = mysqli_fetch_array($select_position)) {
						$position = $get_position['id'];


					}
				}

				$select_position_manage = mysqli_query($db, "SELECT *  FROM  sub_route WHERE main_route_no='$route_id' and start_route !='$start_route_r'  and id > '$position' and start_route='$start_location' and end_route='$ending_location'");
				$select_num_position_manage = mysqli_num_rows($select_position_manage);
				if ($select_num_position_manage != 0) {

					if ($ending_location == $end_route_main) {
						$insert = mysqli_query($db, "INSERT INTO booking (seat_no,route_id,bus,status,exp_time,book_time,book_date,trip_date,route_day_id,day,month,year,issuer,timer,
firstname,lastname,phone_no,validation,cost,reason,start_location,ending_location,gender,id_type,initial,currency,
transit,booker,print_id,amount_left,amount_status,amount_paid,inter_number,sub_route_id,start_route,end_route,book_status,disable_loan,foc,locked_status,country,tenant_id)
 VALUES ('$seat_id','$route_id','$bus','complete','$new_time','$date_time_insert','$date_insert','$trip_date','$route_day_id','$day','$month','$year','" . $_SESSION['username'] . "','',
'$firstname','$lastname','$phone_no','$verification','$fare_price','$reson','$starting_point','$end_point','$gender','$id_type','$initial','$currency','$transit','" . $_SESSION['username'] . "','$print_id',
'$ticket_amount_left','$advance_status','$adnce_info','$inter_phone','$sub_route_id','$start_location','$ending_location','disabled','$disable','$foc','disabled','$country','$tesseraTenantId'
 )");


					} else {
						$insert = mysqli_query($db, "INSERT INTO booking (seat_no,route_id,bus,status,exp_time,book_time,book_date,trip_date,route_day_id,day,month,year,issuer,timer,
firstname,lastname,phone_no,validation,cost,reason,start_location,ending_location,gender,id_type,initial,currency,
transit,booker,print_id,amount_left,amount_status,amount_paid,inter_number,sub_route_id,start_route,end_route,book_status,disable_loan,foc,locked_status,country,tenant_id)
 VALUES ('$seat_id','$route_id','$bus','half_complete','$new_time','$date_time_insert','$date_insert','$trip_date','$route_day_id','$day','$month','$year','" . $_SESSION['username'] . "','',
'$firstname','$lastname','$phone_no','$verification','$fare_price','$reson','$starting_point','$end_point','$gender','$id_type','$initial','$currency','$transit','" . $_SESSION['username'] . "','$print_id',
'$ticket_amount_left','$advance_status','$adnce_info','$inter_phone','$sub_route_id','$start_location','$ending_location','disabled','$disable','$foc','disabled','$country','$tesseraTenantId'
 )");


					}

				} else {
					$error = "finished";
				}




			}
		} else {
			($route_type == "Main Route") ? $status = "complete" : $status = "half_complete";
			($route_type == "Main Route") ? $route_id = $route_id_main : $route_id = $main_id;
			($route_type == "Main Route") ?$sub_route_id = "" : $sub_route_id = $route_id_main;


			$fare_price = fare_price($db, $route_id_main, $state, $dedicate_seat_price, $transit, $foc, $seat_id);

			$insert = "INSERT INTO booking (seat_no,route_id,bus,status,exp_time,book_time,book_date,trip_date,route_day_id,day,month,year,issuer,timer,
	firstname,lastname,phone_no,validation,cost,reason,start_location,ending_location,gender,id_type,initial,currency,
	transit,booker,print_id,amount_left,amount_status,amount_paid,inter_number,sub_route_id,start_route,end_route,book_status,disable_loan,foc,locked_status,country,tenant_id)
	 VALUES ('$seat_id','$route_id','$bus','$status','$new_time','$date_time_insert','$date_insert','$trip_date','$route_day_id','$day','$month','$year','" . $_SESSION['username'] . "','',
	'$firstname','$lastname','$phone_no','$verification','$fare_price','$reson','$starting_point','$end_point','$gender','$id_type','$initial','$currency','$transit','" . $_SESSION['username'] . "','$print_id',
	'$ticket_amount_left','$advance_status','$adnce_info','$inter_phone','$sub_route_id','$start_location','$ending_location','disabled','$disable','$foc','disabled','$country','$tesseraTenantId'
	 )";



			if (mysqli_query($db, $insert)) {
				$last_id = mysqli_insert_id($db);

			}



			$select_transit = mysqli_query($db, "SELECT route_prev,route_name  FROM  route_transit WHERE route_prev='$fullname'");
			$select_num_transit = mysqli_num_rows($select_transit);
			if ($select_num_transit != 0) {
				while ($get_transit = mysqli_fetch_array($select_transit)) {

					$route_prev_transt = $get_transit['route_prev'];
					$route_name_transt = $get_transit['route_name'];


					$insert = mysqli_query($db, "INSERT INTO  transit_seat  (seat_no,username,prev_route,this_route,seat_id) 
					VALUES ('$seat_id','$firstname','$route_prev_transt','$route_name_transt',$last_id)");


				}
			}



		}



		include 'contact.php';

		include 'update/update_ticket_seat.php';

	}






} else {
	$error .= "log_out";
}
if ($error != "") {
	echo "$error";
} else {

	echo "$print_id";


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



