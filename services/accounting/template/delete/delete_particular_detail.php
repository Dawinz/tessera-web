<?php
include '../connect/connect.php';
$id = $_POST['id'];


$select_prev=mysqli_query($db,"SELECT *  FROM   particulars   where id='$id' ");
$select_num_prev=mysqli_num_rows($select_prev);
if($select_num_prev !=0){
   	WHILE($get_prev=mysqli_fetch_array($select_prev)){
    	   
    	    $cat_group =$get_prev['cat_group']; 
    	    $name =$get_prev['name']; 
    
}}

$select=mysqli_query($db,"SELECT *  FROM   particular_category   where front_name='$cat_group' ");
$select_num=mysqli_num_rows($select);
if($select_num !=0){
   	WHILE($get=mysqli_fetch_array($select)){
    	   
    	    $particulars_detail =$get['particulars_detail']; 
    
}}


$new_array = explode(",", "$particulars_detail");
	  if (($key = array_search($name, $new_array)) !== false) {
    unset($new_array[$key]);
	  $new_checked =  implode(",",$new_array);
	  }
	 $update=mysqli_query($db,"UPDATE   particular_category   SET 
	particulars_detail='$new_checked'
	WHERE front_name='$cat_group' ");	
	$insert=mysqli_query($db,"DELETE from  particulars  WHERE id = '$id'");

?>