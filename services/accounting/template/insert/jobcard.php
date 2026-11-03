<?php
	session_start();

include '../connect/connect.php';
$route_name = $_POST['route_name'];
$id = $_POST['id'];

date_default_timezone_set('Africa/Dar_es_Salaam');
$date_insert = date('Y-m-d', time());

$date_time_insert = date('h:i A', time());
 $current_month = date("F", strtotime('m'));
for($count = 0; $count < count($_POST["Vehicle"]); $count++)

	{
$Vehicle = $_POST['Vehicle'][$count];
$item = $_POST['item'][$count];
$quantity = $_POST['quantity'][$count];
$price = $_POST['price'][$count];
$date = $_POST['job_date'][$count];
$detail = $_POST['detail'][$count];
$detail = $_POST['detail'][$count];
$price = preg_replace('/[^0-9.]+/', '', $price);	


$time=strtotime($date);
$month=date("F",$time);
$year=date("Y",$time);


$select_status=mysqli_query($db,"SELECT amount FROM   job_card   where date ='$date' and route_name='$route_name'  ");
$select_num_status=mysqli_num_rows($select_status);
if($select_num_status !=0){
   	WHILE($get_status=mysqli_fetch_array($select_status)){
    	    $amount =$get_status['amount']; 
	}
}
$select=mysqli_query($db,"SELECT name FROM   job_card   where date ='$date' and route_name='$route_name'  ");
$select_num=mysqli_num_rows($select);
if($select_num !=0){
   	WHILE($get=mysqli_fetch_array($select)){
    	    $name =$get['name']; 
	}
}

$new_array = explode(",", "$name");


if($name==""){
	
	$update=mysqli_query($db,"UPDATE  job_card  SET 
	name='$Vehicle'
	WHERE  date ='$date' ");
	
}else{
		  if (($key = array_search($Vehicle, $new_array)) !== false) {

	
		  }else{
			  $name_plus = "$name,";
$update=mysqli_query($db,"UPDATE  job_card  SET 
	name='$name,$Vehicle'
	WHERE  date ='$date' ");	
		  }
}
$total_amount = $amount+$price;
$update=mysqli_query($db,"UPDATE  job_card  SET 
	amount='$total_amount'
	WHERE  date ='$date' ");	

$sqlQ = "INSERT INTO  job_card_entries  (date,bus,item,qty,price,date_insert,time_insert,route_name,username,detail,month,year) VALUES (?,?,?,?,?,?,?,?,?,?,?,?)"; 
$stmt = $db->prepare($sqlQ); 
$stmt->bind_param("ssssssssssss", $date, $Vehicle,$item,$quantity,$price,$date_insert,$date_time_insert,$route_name,$_SESSION['username'],$detail,$current_month,$year); 
$insert = $stmt->execute();	


$select_vehicle_date=mysqli_query($db,"SELECT * FROM job_card_vehicle WHERE   date='$date' and vehicle ='$Vehicle' and month='$month' and year='$year' ");
$select_vehicle_date=mysqli_query($db,"SELECT * FROM job_card_vehicle WHERE   date='$date' and vehicle ='$Vehicle' and month='$month' and year='$year' ");
$select_num_vehicle_date=mysqli_num_rows($select_vehicle_date);
if($select_num_vehicle_date ==0){

$sqlQ = "INSERT INTO  job_card_vehicle  (date,vehicle,month,year,route_name) VALUES (?,?,?,?,?)"; 
                $stmt = $db->prepare($sqlQ); 
                $stmt->bind_param("sssss", $date, $Vehicle ,$month,$year,$route_name); 
                $insert = $stmt->execute();	
}



	}
	
?>

