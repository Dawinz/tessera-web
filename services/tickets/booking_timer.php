<?php
$id=$_GET['id'];
$trip_date=$_GET['trip_date'];
$route_id=$_GET['route_id'];

include 'connect.php';
$select14=mysqli_query($db,"SELECT *  FROM  route_day WHERE id='$id' ");
$select_num14=mysqli_num_rows($select14);
if($select_num14 !=0){
   	WHILE($get134=mysqli_fetch_array($select14)){
    	    $route_id =$get134['route_id'];
			$bus =$get134['bus'];
	
			$departure =$get134['departure'];

		
	}
}

$select14=mysqli_query($db,"SELECT *  FROM  routes WHERE route_id='$route_id' ");
$select_num14=mysqli_num_rows($select14);
if($select_num14 !=0){
   	WHILE($get134=mysqli_fetch_array($select14)){
    	    $fare_price =$get134['fare_price'];
			$active_date =$get134['active_date'];
		

		

		
	}
}
?>
						
						<?php

date_default_timezone_set('Africa/Dar_es_Salaam');
$today_date = date('m/d/Y', time());
$active_date2 =  date("m/d/Y", strtotime($trip_date."+$active_date days"));

$today_day = date('d', strtotime($today_date));
$today_month = date('m', strtotime($today_date));
$today_year = date('y', strtotime($today_date));

$trip_date_day = date('d', strtotime($trip_date));
$trip_date_month = date('m', strtotime($trip_date));
$trip_date_year = date('y', strtotime($trip_date));

$active_date2_day = date('d', strtotime($active_date2));
$active_date2_month = date('m', strtotime($active_date2));
$active_date2_year = date('y', strtotime($active_date2));


if($today_year == $trip_date_year ){
	

	
	if($today_month == $trip_date_month){
			$date_difference=$trip_date_day-$today_day;
	
if($date_difference >=0){

	
	
	$datetime1 = date_create($today_date);
$datetime2 = date_create($trip_date);




$interval = date_diff($datetime2, $datetime1);
$interval =$interval->format('%d');

if($interval==0){
	
$now_time = date('h:i A', time());
$now_time2  = date("H:i", strtotime($now_time));
$departure2  = date("H:i", strtotime($departure));
if($now_time2 < $departure2){

}else{


$update=mysqli_query($db,"UPDATE  manifest SET status='closed'  WHERE  bus='$bus' and trip_date='$trip_date'");
	
	
$trip_date_session=$_GET['trip_date'];
$route_id_session=$_GET['route_id'];


	
}
		?>

		
		<?php

}	else {

}








}else{
	$now_time = date('h:i A', time());
	$time_now24hours  = date("H:i", strtotime($now_time));
   $select145=mysqli_query($db,"SELECT *  FROM  sub_route WHERE main_route_no='$route_id' ");
$select_num145=mysqli_num_rows($select145);


	$id_session=$_GET['id'];
	if($select_num145 !=0){
   	WHILE($get1345=mysqli_fetch_array($select145)){
    	    $id_sub =$get1345['id'];
			$active_day =$get1345['active_day'];
			$start_route =$get1345['start_route'];
			$end_route =$get1345['end_route'];
		$differnce =  $active_day + $date_difference;
			$a_time =$get1345['d_time'];
			$a_time24hours  = date("H:i", strtotime($a_time));
			$active_sub_route_date =  date("m/d/Y", strtotime($trip_date."+$active_day days"));
	if($differnce >=1 ){
		


	}else if($differnce ==0 ){
		if($time_now24hours > $a_time24hours){
		

	
		
		
		
$update=mysqli_query($db,"UPDATE  manifest_sub_route SET status='closed'  WHERE  bus='$bus' and trip_date='$trip_date' and start_location='$start_route'");
	}else{
	}
	} else if($differnce < 0 ){
		
		$update=mysqli_query($db,"UPDATE  manifest_sub_route SET status='closed'  WHERE  bus='$bus' and trip_date='$trip_date' and start_location='$start_route'");

	


	}
	


		
	}
	
}
	


}
	}else if($today_month < $trip_date_month){
		/////month
	
	}
	
	
}else if($trip_date_year > $today_year){
	
//// month
}else if($trip_date_year < $today_year){
	


}






?>
