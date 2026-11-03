<?php
include '../connect/connect.php';
$id = $_POST['id'];



$select_prev=mysqli_query($db,"SELECT particular_name,name  FROM   particular_sub   where id='$id' ");
$select_num_prev=mysqli_num_rows($select_prev);
if($select_num_prev !=0){
   	WHILE($get_prev=mysqli_fetch_array($select_prev)){
    	   
    	    $particular_name =$get_prev['particular_name']; 
    	    $name =$get_prev['name']; 

}}


$select=mysqli_query($db,"SELECT *  FROM   particulars   where name='$particular_name' ");
$select_num=mysqli_num_rows($select);
if($select_num !=0){
   	WHILE($get=mysqli_fetch_array($select)){
    	   
    	    $sub_particulars =$get['sub_particulars']; 
    
}}
//echo "$sub_particulars/$name";


$new_array = explode(",", "$sub_particulars");
	  if (($key = array_search($name, $new_array)) !== false) {
    unset($new_array[$key]);
	  $new_checked =  implode(",",$new_array);
	  }
	//  echo $new_checked;
	  
	 $update=mysqli_query($db,"UPDATE   particulars   SET 
	sub_particulars='$new_checked'
	WHERE name='$particular_name' ");	
	$insert=mysqli_query($db,"DELETE from  particular_sub  WHERE id = '$id'");

?>