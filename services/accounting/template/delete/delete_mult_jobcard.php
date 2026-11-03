<?php
include '../connect/connect.php';


$array_id = explode(",", $_POST['selected_checked']);
foreach ($array_id as $x) {

$select_prev=mysqli_query($db,"SELECT *  FROM   job_card_entries   where id='$x' ");
$select_num_prev=mysqli_num_rows($select_prev);
if($select_num_prev !=0){
   	WHILE($get_prev=mysqli_fetch_array($select_prev)){
    	   
    	    $date =$get_prev['date']; 
    	    $route_name =$get_prev['route_name']; 
    	    $price =$get_prev['price']; 
    
}

$select=mysqli_query($db,"SELECT *  FROM   job_card   where date='$date' and route_name='$route_name'");
$select_num=mysqli_num_rows($select);
if($select_num !=0){
   	WHILE($get=mysqli_fetch_array($select)){
    	   
    	    $amount =$get['amount']; 
    	    $id_j =$get['id']; 
    
}}

$new_price = $amount - $price;
	 $update=mysqli_query($db,"UPDATE   job_card   SET 
	amount='$new_price'
	WHERE id='$id_j' ");	
$insert=mysqli_query($db,"DELETE from  job_card_entries  WHERE id = '$x'");


}

//$insert=mysqli_query($db,"DELETE from  job_card_entries  WHERE id = '$id'");



}

?>