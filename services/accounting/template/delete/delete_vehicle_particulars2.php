<?php
include '../connect/connect.php';
$id = $_POST['id'];


$select=mysqli_query($db,"SELECT *  FROM   vehicle_particular_details   where id='$id'");
$select_num=mysqli_num_rows($select);
if($select_num !=0){
   	WHILE($get=mysqli_fetch_array($select)){
    	    $name =$get['name']; 
    	    $category_id =$get['category_id']; 
    
	}
}


$select_detail=mysqli_query($db,"SELECT *  FROM   vehicle_category   where id='$category_id' ");
$select_num_detail=mysqli_num_rows($select_detail);
if($select_num_detail !=0){
   	WHILE($get_detail=mysqli_fetch_array($select_detail)){
    	   
    	    $partiuclar =$get_detail['partiuclar']; 
    
}}



$new_array = explode(",", "$partiuclar");
	  if (($key = array_search($name, $new_array)) !== false) {
    unset($new_array[$key]);
	  $new_checked =  implode(",",$new_array);
	  }


 $update=mysqli_query($db,"UPDATE   vehicle_category   SET 
	partiuclar='$new_checked'
	WHERE id='$category_id' ");	
	$insert=mysqli_query($db,"DELETE from  vehicle_particular_details  WHERE id = '$id'");


?>