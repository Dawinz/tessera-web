<?php
require_once __DIR__ . '/includes/tessera_staff_boot.php';
require_once __DIR__ . '/includes/sr_env.php';
session_start();
$username=$_SESSION['username'];
include 'connect.php';
require_once __DIR__ . '/includes/tessera_tenant.php';


$select2=mysqli_query($db,"SELECT *  FROM  users  where username='".$_SESSION['username']."'");
$select_num2=mysqli_num_rows($select2);
if($select_num2 !=0){
   	WHILE($get12=mysqli_fetch_array($select2)){
    	    $role =$get12['role'];  		
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
$deleted_time="$date_insert at $date_time_insert  ";
$route_id= $_POST['route_id_modal'];
$trip_date=$_POST['trip_date_modal'];
$select_all_seats=$_POST['select_all_seats'];
$route_day_id=$_POST['route_day_id_modal'];
$reason=$_POST['resoons_to_delete'];



if(($role=="Administrator")or ($role=="Super Administrator")){




    $my_string = $select_all_seats;    

    // passing "," as the delimiter

    $my_array1 = explode(",", $my_string);



for($i = 0;$i < count($my_array1);$i++){
$seat_id = $my_array1[$i];

$select1=mysqli_query($db,"SELECT *  FROM  booking WHERE " . tessera_sql_tenant_eq() . " AND id='$seat_id'");
$select_num1=mysqli_num_rows($select1);
if($select_num1 !=0){
	   	WHILE($get13=mysqli_fetch_array($select1)){
 
 $seat_id= $get13['id']; 
 $trip_date= $get13['trip_date']; 
 $bus= $get13['bus'];
 $cost= $get13['cost'];
 $route_id= $get13['route_id']; 
 $seat_no= $get13['seat_no']; 
 $route_day_id= $get13['route_day_id'];
 $start_location= $get13['start_route'];
 $ending_location= $get13['end_route'];
 $status= $get13['status'];
$firstname= $get13['firstname'];
$lastname= $get13['lastname'];
$phone_no = $get13['phone_no'];
$book_date = $get13['book_date'];
$book_time = $get13['book_time'];
$locker = $get13['locker'];
 $issuer= $get13['issuer']; 
 $start_route= $get13['start_route']; 
 $end_route= $get13['end_route']; 
$seat_booked_date="$book_date at $book_time ";
$user_fullname= "$firstname $lastname";
}

 $date1=date_create($trip_date);
$date2=date_create($date_insert);
$diff=date_diff($date2,$date1);
$date_left =  $diff->format("%R%a");

if($date_left < 0){
			
echo "past";
}else{


	$select1=mysqli_query($db,"SELECT *  FROM  assigned_routes  WHERE route_id='$route_id'  ");
$select_num1=mysqli_num_rows($select1);
if($select_num1 !=0){
   	WHILE($get13=mysqli_fetch_array($select1)){
 $route_name =$get13['route_name']; 

}}

 $delete=mysqli_query($db,"INSERT INTO deleted  
(seat_id,seat_no,username,delete_date,ticket_user,ticket_deleter,status,location,phone_no,seat_insert_time,trip_date,reason) VALUES 
('$seat_id','$seat_no','$issuer','$deleted_time','$user_fullname','".$_SESSION['username']."','deleted','$route_name','$phone_no','$seat_booked_date','$trip_date','$reason')");

//$insert=mysqli_query($db,"DELETE from booking WHERE " . tessera_sql_tenant_eq() . " AND id = '$seat_id'");


		$update=mysqli_query($db,"UPDATE  booking SET delete_book='deleted' WHERE " . tessera_sql_tenant_eq() . " AND (id = '$seat_id')");


			





		$select14=mysqli_query($db,"SELECT *  FROM  routes WHERE start_route='$start_route' AND end_route='$end_route' ");
$select_num14=mysqli_num_rows($select14);
if($select_num14 !=0){
   	WHILE($get134=mysqli_fetch_array($select14)){
    	 
			$route_id =$get134['route_id']; 
		$fare_price =$get134['fare_price']; 
 
  $currency= $get134['currency']; 

	
		
	
}
}else{
$select145=mysqli_query($db,"SELECT *  FROM  sub_route  WHERE start_route='$start_route' AND end_route='$end_route' ");
$select_num145=mysqli_num_rows($select145);
if($select_num145 !=0){
   	WHILE($get1345=mysqli_fetch_array($select145)){
    	    $id =$get1345['id'];
		
		
		$fare_price =$get1345['fare_price']; 
 
  $currency= $get1345['currency']; 
	


		
		
	
	
}}
	
}




$select1356=mysqli_query($db,"SELECT *  FROM  vehicle WHERE name='$bus'");
$select_num1356=mysqli_num_rows($select1356);
if($select_num1356 !=0){
   	WHILE($get13356=mysqli_fetch_array($select1356)){
 

 $driver= $get13356['driver'];
 $conductor= $get13356['conductor'];
 $seats= $get13356['seats'];

 

}}

$select173=mysqli_query($db,"SELECT *  FROM  currency Where country='$currency'");
$select_num173=mysqli_num_rows($select173);
if($select_num173 !=0){
   	WHILE($get1373=mysqli_fetch_array($select173)){
 
 $rate= $get1373['rate'];
 $initial= $get1373['initial'];  

}}

$select1433=mysqli_query($db,"SELECT *  FROM  routes WHERE route_id= '$route_id' ");
$select_num1433=mysqli_num_rows($select1433);
if($select_num1433 !=0){
   	WHILE($get13433=mysqli_fetch_array($select1433)){
    	    $start_route_main =$get13433['start_route'];
    	    $end_route_main =$get13433['end_route'];
			  $route_name= $get13433['route_name'];
	
		
		
		
	
}}

$select157=mysqli_query($db,"SELECT *  FROM  booking WHERE " . tessera_sql_tenant_eq() . " AND (status = 'complete' and route_id='$route_id' and route_day_id='$route_day_id' and trip_date='$trip_date')");
$select_num157=mysqli_num_rows($select157);

$total_seat = $seats - $select_num157;

$connect = sr_mysqli('ticket');
$sum = 0;
$start_route2 = '';
$query = "
SELECT cost  FROM booking WHERE " . tessera_sql_tenant_eq() . " AND (route_id='$route_id' and route_day_id='$route_day_id' and trip_date='$trip_date')";
$result = mysqli_query($connect, $query);
while($row = mysqli_fetch_array($result))
{
 $start_route2 .= $row["cost"];
 $sum = $sum +$row["cost"];    
   
}
 
$select1=mysqli_query($db,"SELECT *  FROM  manifest WHERE  route_day_id='$route_day_id' and route_id='$route_id' and trip_date='$trip_date'  ");
$select_num1=mysqli_num_rows($select1);
if($select_num1 ==0){
	
$insert=mysqli_query($db,"INSERT INTO manifest (bus,trip_date,insert_date,seats,amount,start_location,end_location,start_time,route_day_id,day,month,year,driver,conductor,route_id,route_name) VALUES
 ('$bus','$trip_date','$date_insert','$total_seat','$sum','$start_route_main','$end_route_main','$hours','$route_day_id','$day','$month','$year','$driver','$conductor','$route_id','$route_name')");	
}else{
	$insert=mysqli_query($db,"UPDATE  manifest SET amount='$sum',seats='$total_seat' WHERE route_day_id='$route_day_id' and route_id='$route_id' and trip_date='$trip_date'");
	
}
}
}else{
	echo "no ticket";
}

}

}else{
	echo "not";
}

?>