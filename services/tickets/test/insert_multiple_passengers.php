<?php
require_once __DIR__ . '/../includes/tessera_tenant.php';

session_start();
include 'connect.php';

$select18=mysqli_query($db,"SELECT *  FROM users WHERE username='".@$_SESSION['username']."'  ");
$select_num18=mysqli_num_rows($select18);

if($select_num18 !=0){



$username=$_SESSION['username'];

$n = 15;
$print_id = bin2hex(random_bytes($n));
$sub_route_id =0;





//insert.php
date_default_timezone_set('Africa/Dar_es_Salaam');
$date_insert = date('Y-m-d', time());
$day = date('d', time());
$month = date('m', time());
$year = date('Y', time());
$date_time_insert = date(' h:i A', time());
if(isset($_POST["firstname"]))
{

	

	$order_id = uniqid();

	for($count = 0; $count < count($_POST["firstname"]); $count++)
	{
$firstname = $_POST['firstname'][$count];
$seat_id = $_POST['seat_id'][$count];
$lastname = $_POST['lastname'][$count];
$phone_no = $_POST['phone_no'][$count];
$validation = $_POST['verification'][$count];
$starting_point = $_POST['starting_point'][$count];
$end_point = $_POST['end_point'][$count];
$starting_location = $_POST['starting_location'][$count];
$ending_location = $_POST['ending_location'][$count];
$reason = $_POST['reason'][$count];
$gender = $_POST['gender'][$count];
$age_group = $_POST['age_group'][$count];

if(isset($_POST['trans'][$count])){
$tran = $_POST['trans'][$count];
	
}else{
	$tran = "";

}
if(isset($_POST['foc'][$count])){
	$foc = $_POST['foc'][$count];

}else{
		$foc = "";

}


$select1=mysqli_query($db,"SELECT *  FROM  booking WHERE " . tessera_sql_tenant_eq() . " AND (id='".$_POST['seat_id'][$count]."')");
$select_num1=mysqli_num_rows($select1);
if($select_num1 !=0){
   	WHILE($get13=mysqli_fetch_array($select1)){
 
 $trip_date= $get13['trip_date']; 
 $bus= $get13['bus'];
 $cost= $get13['cost'];
 $route_id= $get13['route_id']; 
 $seat_no= $get13['seat_no']; 
 $route_day_id= $get13['route_day_id'];


}}

$select13=mysqli_query($db,"SELECT *  FROM  route_day WHERE route_id='$route_id'");
$select_num13=mysqli_num_rows($select13);
if($select_num13 !=0){
   	WHILE($get133=mysqli_fetch_array($select13)){
 

 $route_id= $get133['route_id'];
 $hours= $get133['12hours'];

	

}}

$select13=mysqli_query($db,"SELECT *  FROM  route_day WHERE route_id='$route_id'");
$select_num13=mysqli_num_rows($select13);
if($select_num13 !=0){
   	WHILE($get133=mysqli_fetch_array($select13)){
 

 $route_id= $get133['route_id'];
 $hours= $get133['12hours'];

	

}}

//////
		$select14=mysqli_query($db,"SELECT *  FROM  routes WHERE start_route='$starting_location' AND end_route='$ending_location' ");
$select_num14=mysqli_num_rows($select14);
if($select_num14 !=0){
   	WHILE($get134=mysqli_fetch_array($select14)){
    	    $id =$get134['id'];
			$start_route =$get134['start_route'];
			$end_route =$get134['end_route'];
			$route_id =$get134['route_id']; 
		$fare_price =$get134['fare_price']; 
  $route_name= $get134['route_name'];
  $currency= $get134['currency']; 

		
		
		
	
}

$status_booked='complete';

}else{
	
	
			$select145=mysqli_query($db,"SELECT *  FROM  sub_route  WHERE start_route='$starting_location' AND end_route='$ending_location' ");
$select_num145=mysqli_num_rows($select145);
if($select_num145 !=0){
   	WHILE($get1345=mysqli_fetch_array($select145)){
    	    $id =$get1345['id'];
		
			$route_id = $get1345['main_route_no']; 
		$fare_price =$get1345['fare_price']; 
  $route_name= $get1345['route_name'];
  $currency= $get1345['currency']; 
  $sub_route_id= $get1345['sub_route_id']; 

		
		
		
	
}}
$status_booked='half_complete';
		$select14=mysqli_query($db,"SELECT *  FROM  routes WHERE route_id='$route_id' ");
$select_num14=mysqli_num_rows($select14);
if($select_num14 !=0){
   	WHILE($get134=mysqli_fetch_array($select14)){
    	    
			$start_route =$get134['start_route'];
			$end_route =$get134['end_route'];


		
		
		
	
}}	
}
$select1356=mysqli_query($db,"SELECT *  FROM  vehicle WHERE name='$bus'");
$select_num1356=mysqli_num_rows($select1356);
if($select_num1356 !=0){
   	WHILE($get13356=mysqli_fetch_array($select1356)){
 

 $driver= $get13356['driver'];
 $conductor= $get13356['conductor'];

 

}}

$select173=mysqli_query($db,"SELECT *  FROM  currency Where country='$currency'");
$select_num173=mysqli_num_rows($select173);
if($select_num173 !=0){
   	WHILE($get1373=mysqli_fetch_array($select173)){
 
 $rate= $get1373['rate'];
 $initial= $get1373['initial'];  
 $status= $get1373['status']; 

}}

if($status !="Main"){
	$internatinal_cost=$fare_price;
$fare_price=$fare_price*$rate;	

}else{
	$internatinal_cost=0;
}

if(($foc =="checked") or($tran =="checked")){
	$fare_price=0;
}

$insert=mysqli_query($db,"UPDATE  booking SET firstname='$firstname',lastname='$lastname',phone_no='$phone_no',validation='$validation',status='$status_booked',start_location='$starting_point',ending_location='$end_point',cost='$fare_price',reason='$reason',booker='".$_SESSION['username']."',gender='$gender',age_group='$age_group',international_cost='$internatinal_cost',initial='$initial',currency='$currency',book_time='$date_time_insert',transit='$tran',print_id='$print_id',sub_route_id='$sub_route_id' WHERE " . tessera_sql_tenant_eq() . " AND (id='$seat_id')");

	}


}else{

}



$select1=mysqli_query($db,"SELECT *  FROM  manifest WHERE  route_day_id='$route_day_id' and route_id='$route_id' and trip_date='$trip_date'  ");
$select_num1=mysqli_num_rows($select1);
if($select_num1 ==0){

	$select157=mysqli_query($db,"SELECT *  FROM  booking WHERE " . tessera_sql_tenant_eq() . " AND (route_id='$route_id' and route_day_id='$route_day_id' and trip_date='$trip_date')");
$select_num157=mysqli_num_rows($select157);

$sql = $db->query("SELECT SUM(cost) AS  cost  FROM booking WHERE " . tessera_sql_tenant_eq() . " AND (route_id='$route_id' and route_day_id='$route_day_id' and trip_date='$trip_date')");
		if ($sql->num_rows > 0) {
			$response = "";

			while($data = $sql->fetch_array()) {
				$total_cost_old=$data['cost'];
		}}else{
			
		}

$insert=mysqli_query($db,"INSERT INTO manifest (bus,trip_date,insert_date,seats,amount,start_location,end_location,start_time,route_day_id,day,month,year,driver,conductor,route_id,route_name) VALUES
 ('$bus','$trip_date','$date_insert','1','$fare_price','$start_route','$end_route','$hours','$route_day_id','$day','$month','$year','$driver','$conductor','$route_id','$route_name')");
	
}else{
	$select157=mysqli_query($db,"SELECT *  FROM  booking WHERE " . tessera_sql_tenant_eq() . " AND (route_id='$route_id' and route_day_id='$route_day_id' and trip_date='$trip_date')");
$select_num157=mysqli_num_rows($select157);

$sql = $db->query("SELECT SUM(cost) AS  cost  FROM booking WHERE " . tessera_sql_tenant_eq() . " AND (route_id='$route_id' and route_day_id='$route_day_id' and trip_date='$trip_date')");
		if ($sql->num_rows > 0) {
			$response = "";

			while($data = $sql->fetch_array()) {
				$total_cost_old=$data['cost'];
		}}else{
			
		}
	$insert=mysqli_query($db,"UPDATE  manifest SET amount='$total_cost_old',seats='$select_num157' WHERE route_day_id='$route_day_id' and route_id='$route_id' and trip_date='$trip_date'");
	

}





	echo "$print_id";
	
}else{
	echo "log_out";
}

?>
