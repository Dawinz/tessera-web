<?php
include '../connect/connect.php';


$Vehicle_update = $_POST['Vehicle_update'];
$item_update = $_POST['item_update'];
$quantity_update = $_POST['quantity_update'];
$price_update = $_POST['price_update'];
$price_update = preg_replace('/[^0-9.]+/', '',$_POST['price_update']);
$date_update = $_POST['date_update'];
$detail_update = $_POST['detail_update'];
$id_no = $_POST['id_no'];

$select=mysqli_query($db,"SELECT date,bus,price FROM   job_card_entries   where id ='$id_no'  ");
$select_num=mysqli_num_rows($select);
if($select_num !=0){
   	WHILE($get=mysqli_fetch_array($select)){
    	    $date =$get['date']; 
    	    $bus =$get['bus']; 
    	    $price =$get['price']; 
	}
}

$select_jobcard=mysqli_query($db,"SELECT name,id,amount FROM   job_card   where date ='$date'  ");
$select_num_jobcard=mysqli_num_rows($select_jobcard);
if($select_num_jobcard !=0){
   	WHILE($get_jobcard=mysqli_fetch_array($select_jobcard)){
    	    $name =$get_jobcard['name']; 
    	    $jobcard_id =$get_jobcard['id']; 
    	    $amount =$get_jobcard['amount']; 
	}
}

if($Vehicle_update != $bus){

$new_array = explode(",", "$name");
	  if (($key = array_search($bus, $new_array)) !== false) {
    unset($new_array[$key]);
	  $new_checked =  implode(",",$new_array);
	  }

$new_bus_name = "$new_checked,$Vehicle_update";
$update=mysqli_query($db,"UPDATE  job_card  SET 
	
	name='$new_bus_name'
	WHERE id = '$jobcard_id'");	

}

if ($price_update != $price){
	$new_total = $amount - $price + $price_update;
	$update=mysqli_query($db,"UPDATE  job_card  SET 
	
	amount='$new_total'
	WHERE id = '$jobcard_id'");	

}

$update=mysqli_query($db,"UPDATE  job_card_entries  SET 
	date='$date_update',
	bus='$Vehicle_update',
	item='$item_update',
	qty='$quantity_update',
	price='$price_update',
	detail='$detail_update'
	WHERE id = '$id_no'");	

?>