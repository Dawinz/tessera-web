<?php
require_once __DIR__ . '/includes/tessera_tenant.php';

session_start();
$username=$_SESSION['username'];
$date_insert = date('Y-m-d', time());
$day = date('d', time());
$month = date('m', time());
$year = date('Y', time());
$date_time_insert = date(' h:i A', time());
$id = $_POST['id'];
include 'connect.php';

$select135=mysqli_query($db,"SELECT *  FROM  booking WHERE " . tessera_sql_tenant_eq() . " AND (id='$id')");
$select_num135=mysqli_num_rows($select135);
if($select_num135 !=0){
   	WHILE($get1335=mysqli_fetch_array($select135)){
 $ticket_id= $get1335['id'];
 $route_day_id= $get1335['route_day_id'];
 $trip_date= $get1335['trip_date'];
 $route_id = $get1335['route_id'];
 $transfer_id = $get1335['transfer_id'];
 $seat_no = $get1335['seat_no'];

}}




$select1352=mysqli_query($db,"SELECT *  FROM  transit WHERE new_route_id='$route_id' and new_route_day_id='$route_day_id' and new_trip_date='$trip_date' and ticket_id='$transfer_id'");
$select_num1354=mysqli_num_rows($select1352);
if($select_num1354 !=0){
   	WHILE($get13354=mysqli_fetch_array($select1352)){
 $old_route_day_id= $get13354['old_route_day_id'];
 $old_trip_date= $get13354['old_trip_date'];
 $old_route_id = $get13354['old_route_id'];
 $old_seat = $get13354['old_seat'];

$select1354=mysqli_query($db,"SELECT *  FROM  booking WHERE " . tessera_sql_tenant_eq() . " AND (seat_no='$old_seat' and route_id='$old_route_id' and route_day_id='$old_route_day_id' and trip_date='$old_trip_date')");
$select_num1354=mysqli_num_rows($select1354);
if($select_num1354 ==0){
	
	$insert=mysqli_query($db,"UPDATE  booking SET   trip_date='$old_trip_date',route_id='$old_route_id',seat_no='$old_seat',route_day_id='$old_route_day_id' WHERE " . tessera_sql_tenant_eq() . " AND (id='$ticket_id')");
$insert=mysqli_query($db,"INSERT INTO transit (ticket_id,old_route_day_id,old_route_id,old_trip_date,new_route_day_id, new_route_id ,new_trip_date,old_seat,date_insert,time_insert,user,status) VALUES
('$id','$route_day_id','$route_id','$trip_date','$old_route_day_id','$old_route_id','$old_trip_date','$seat_no','$date_insert','$date_time_insert','".$_SESSION['username']."','undo')");

echo "$old_seat";

}else{

	
}


}}else{
	echo "no";
}



$select15=mysqli_query($db,"SELECT *  FROM  booking WHERE " . tessera_sql_tenant_eq() . " AND (status='complete' and route_id='$old_route_id' and route_day_id='$old_route_day_id' and trip_date='$old_trip_date')");
$select_num15=mysqli_num_rows($select15);

	
$sql = $db->query("SELECT SUM(cost) AS  cost  FROM booking WHERE " . tessera_sql_tenant_eq() . " AND (status='complete' and route_id='$old_route_id' and route_day_id='$old_route_day_id' and trip_date='$old_trip_date')");
		if ($sql->num_rows > 0) {
			$response = "";

			while($data = $sql->fetch_array()) {
				$total_cost=$data['cost'];
		}}else{
			
		}
	$insert=mysqli_query($db,"UPDATE  manifest SET amount='$total_cost',seats='$select_num15' WHERE route_day_id='$old_route_day_id' and route_id='$old_route_id' and trip_date='$old_trip_date'");
	


$select155=mysqli_query($db,"SELECT *  FROM  booking WHERE " . tessera_sql_tenant_eq() . " AND (status='complete' and route_id='$route_id' and route_day_id='$route_day_id' and trip_date='$trip_date')");
$select_num155=mysqli_num_rows($select155);

	
$sql = $db->query("SELECT SUM(cost) AS  cost  FROM booking WHERE " . tessera_sql_tenant_eq() . " AND (status='complete' and route_id='$route_id' and route_day_id='$route_day_id' and trip_date='$trip_date')");
		if ($sql->num_rows > 0) {
			$response = "";

			while($data = $sql->fetch_array()) {
				$total_cost=$data['cost'];
		}}else{
			
		}
	$insert=mysqli_query($db,"UPDATE  manifest SET amount='$total_cost',seats='$select_num155' WHERE route_day_id='$route_day_id' and route_id='$route_id' and trip_date='$trip_date'");
	


?>