<?php
require_once __DIR__ . '/includes/tessera_staff_boot.php';
session_start();
error_reporting(E_ALL);           
ini_set('display_errors', 1);     
ini_set('display_startup_errors', 1);
?>
<?php
include 'connect.php';

?>
<?php

$code =$_POST['code'];
$parcel_info_json =$_POST['parcel_info_json'];





$vehicle_name = $_POST['vehicle_name'];
$trip_date = $_POST['trip_date'];



$to_be = $_POST['to_be'];
$paid = $_POST['paid'];
$qty = $_POST['qty'];
$transit = (isset($_POST['transit'])) ? $transit = $_POST['transit'] : $transit = "";

$qty = preg_replace('/[^0-9.]+/', '', $qty);
$to_be = preg_replace('/[^0-9.]+/', '', $to_be);
$paid = preg_replace('/[^0-9.]+/', '', $paid);
$primary_route = "";

echo $paid;

$select=mysqli_query($db,"SELECT scanned,question,date_insert,account,amount,paid,to_be,paid_remain,to_be_remain,quantity_remain,r_name,status,detail,username,weight_name,weight_value,lugg_value,s_no,r_no   FROM parcel_info where code = '$code'");
$select_num=mysqli_num_rows($select);
if($select_num !=0){
   	WHILE($get=mysqli_fetch_array($select)){
    
		  $scanned=$get['scanned'];  
		  $amount=$get['amount'];  
		  $paid_remain=$get['paid_remain'];  
		  $to_be_remain=$get['to_be_remain'];  
		  $quantity_remain=$get['quantity_remain'];  
		  $receivers_name=$get['r_name'];  
          $status= $get['status'];
         $route=$get['account']; 
         $detail=$get['detail']; 
         $username=$get['username'];
         $weight_name=$get['weight_name'];
         $weight_value=$get['weight_value'];
         $lugg_value=$get['lugg_value'];
         $quantity=$get['question'];
         $date_insert=$get['date_insert'];
         $s_no=$get['s_no'];
         $r_no=$get['r_no'];
 
    }}



if($qty > $scanned){
    echo "Manifest Quantity is Greater than Actual Quantity";
}
else if(($qty <=0)AND ($transit=="")){
    echo "Manifest Quantity Can not be Zero or less than zero ";
}
else if($to_be >$to_be_remain ){
    echo "Manifest To be paid is much higher than Actual To be paid";
}
else if($paid >$paid_remain ){
    echo "Manifest paid is much higher than Actual  paid";
}
else if (($qty < $scanned) & ($to_be == $to_be_remain) & ($status == "to_be") or ($qty < $scanned) & ($paid == $paid_remain) & ($status == "paid")){
    echo "Manifest Quantity Does not match Actual Ammount";
}
else if (($qty < $scanned) & ($to_be == $to_be_remain) & ($paid == $paid_remain) ){
    echo "Manifest Quantity Does not match Actual Amount Remain";
}
else{

$q_left_manifest=0;
$select_manifest=mysqli_query($db,"SELECT * FROM `manifest` WHERE code='$code' ORDER BY id DESC LIMIT 1 ");
$select_num_manifest=mysqli_num_rows($select_manifest);
if($select_num_manifest !=0){
   	WHILE($get_manifest=mysqli_fetch_array($select_manifest)){
    	    $q_left_manifest =$get_manifest['q_left']; 

		
	}
}  

if($transit==""){
$new_q_left = $qty + $q_left_manifest ;

$parcel_to_be = $to_be_remain - $to_be;
$parcel_paid = $paid_remain - $paid;
$parcel_scan =   $scanned - $qty;
$parcel_total_amount = $parcel_paid + $parcel_to_be;
$total_amount = $to_be + $paid;
if($parcel_scan ==0){
$insert=mysqli_query($db,"UPDATE parcel_info SET scanned='$parcel_scan',quantity_remain='$parcel_scan',store='out',paid_remain='$parcel_paid',to_be_remain='$parcel_to_be',trip_date='$trip_date',bus='$vehicle_name' WHERE code ='$code'");
}else{
$insert=mysqli_query($db,"UPDATE parcel_info SET scanned='$parcel_scan',quantity_remain='$parcel_scan',store='half_out',paid_remain='$parcel_paid',to_be_remain='$parcel_to_be',trip_date='$trip_date',bus='$vehicle_name' WHERE code ='$code'");

}

 $sqlQ = "INSERT INTO  manifest  (code,username,amount,trip_date,bus,paid,to_be,status,uploader,q_left,quantity,detail,parcel_uploader,parcel_route,parcel_date,quantity_insert,weight_name,weight_value,lugg_value,sender_no,reciever_no) VALUES
  (?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)"; 
                $stmt = $db->prepare($sqlQ); 
                $stmt->bind_param("sssssssssssssssssssss", $code, $receivers_name,$total_amount,$trip_date,$vehicle_name,$paid,$to_be,$status,$_SESSION['username'],$new_q_left,$quantity,$detail,$username,$route,$date_insert,$qty,$weight_name,$weight_value,$lugg_value,$s_no,$r_no); 
                $insert = $stmt->execute();	



$select13=mysqli_query($db,"SELECT to_pay,paid,due,Route   FROM  vehicle_name  WHERE date ='$trip_date' and Name='$vehicle_name' ");
$select_num13=mysqli_num_rows($select13);
if($select_num13 !=0){
   	WHILE($get133=mysqli_fetch_array($select13)){
    	 
					$to_pay_vehicle =$get133['to_pay']; 
					$paid_vehicle =$get133['paid']; 
					$due_vehicle =$get133['due']; 
					$main_route_name =$get133['Route']; 
}}


$select_name=mysqli_query($db,"SELECT currency_symbol  FROM  routes  WHERE route_full_name ='$main_route_name' ");
$select_num_name=mysqli_num_rows($select_name);
if($select_num_name !=0){
   	WHILE($get_name=mysqli_fetch_array($select_name)){
    	 
					$currency_main_route =$get_name['currency_symbol']; 
		
}}


if($route  == $main_route_name){
$currency_value = 1;
}else{
$currency_value= currency_value($db,$route,$currency_main_route);


}

//echo "$route  - $main_route_name - $currency_main_route - $currency_route_currency $currency_value";
$vehicle_to_paid = $to_be + $to_pay_vehicle;
$vehicle_paid = $paid + $paid_vehicle;
$vehicle_total = $vehicle_paid + $vehicle_to_paid;

$vehicle_to_paid = round($vehicle_to_paid * $currency_value);
$vehicle_paid = round($vehicle_paid * $currency_value);
$vehicle_total = round($vehicle_total * $currency_value);



//$update=mysqli_query($db,"UPDATE vehicle_name SET to_pay='$vehicle_to_paid',paid='$vehicle_paid',due='$vehicle_total' WHERE date ='$trip_date' and Name='$vehicle_name'");





}

}

function currency_value($db, $route, $currency_main_route) {

$select_route_currency=mysqli_query($db,"SELECT currency_symbol  FROM  routes  WHERE route_full_name ='$route' ");
$select_num_route_currency=mysqli_num_rows($select_route_currency);
if($select_num_route_currency !=0){
   	WHILE($get_route_currency=mysqli_fetch_array($select_route_currency)){
    	 
					$currency_route_currency=$get_route_currency['currency_symbol']; 
		
}}
 if($currency_main_route !=$currency_route_currency){
$select_c_value=mysqli_query($db,"SELECT value  FROM  currency_value  WHERE first_point ='$currency_route_currency' and second_point='$currency_main_route' ");
$select_num_c_value=mysqli_num_rows($select_c_value);
if($select_num_c_value !=0){
   	WHILE($get_c_value=mysqli_fetch_array($select_c_value)){
    	 
					$currency_value=$get_c_value['value']; 
		
}}
}else{
    $currency_value = 1;
}
return $currency_value;
}

?>