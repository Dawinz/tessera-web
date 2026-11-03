<?php
session_start();

include 'connect.php';
$select2=mysqli_query($db,"SELECT *  FROM  users  where username='".$_SESSION['username']."'");
$select_num2=mysqli_num_rows($select2);
if($select_num2 !=0){
   	WHILE($get12=mysqli_fetch_array($select2)){
    	    $firstname =$get12['firstname'];  
			$surname =$get12['surname'];
			

		
	}
}


date_default_timezone_set('Africa/Dar_es_Salaam');
$date_insert = date('Y-m-d', time());
$day = date('d', time());
$month = date('m', time());
$year = date('Y', time());
$date_time_insert = date('h:i A', time());
$new_time = date("h:i A", strtotime('+1 hours'));
$booking_timer = date("h:i:s A", strtotime('+10 minute'));

if(isset($_POST["resons"]))
{

	$n = 15;
$print_id = bin2hex(random_bytes($n));



	for($count = 0; $count < count($_POST["resons"]); $count++)
	{

$resons=  $_POST["resons"][$count];
$resons = base64_encode($resons);
$seat_no=  $_POST["seat_no"][$count];



$route_day_id =  @$_POST["route_day_id"][$count];
$trip_date =  @$_POST["trip_date"][$count];
$route_id =  @$_POST["route_id"][$count];
$bus =  @$_POST["bus"][$count];

		$select14=mysqli_query($db,"SELECT *  FROM  routes WHERE route_id='$route_id' ");
$select_num14=mysqli_num_rows($select14);
if($select_num14 !=0){
   	WHILE($get134=mysqli_fetch_array($select14)){

			$start_route =$get134['start_route'];
			$end_route =$get134['end_route'];

}

}

$insert=mysqli_query($db,"INSERT INTO booking (seat_no,route_id,bus,status,route_day_id,trip_date,lock_option,locker,locker_fullname,locked_reasons,issuer,book_status,disable_loan,locked_status,start_route,end_route)
 VALUES ('$seat_no','$route_id','$bus','locked','$route_day_id','$trip_date','locked','".$_SESSION['username']."','$firstname $surname','$resons','".$_SESSION['username']."','disabled','disabled','','$start_route','$end_route')");
echo $trip_date;



	}


}else{

}

?>
