<?php
include '../connect/connect.php';

/*$update=mysqli_query($db,"UPDATE   particulars   SET 
	name='".$_POST['particular_update']."',
	account='".$_POST['account']."',
	category='".$_POST['category_update']."'
	WHERE id='".$_POST['id_no_update']."' ");	
*/
$particular_update = $_POST['particular_update'];
$front_name = $_POST['category_update'];
$id = $_POST['id_no_update'];
$select=mysqli_query($db,"SELECT *  FROM   particular_category   where front_name='$front_name' ");
$select_num=mysqli_num_rows($select);
if($select_num !=0){
   	WHILE($get=mysqli_fetch_array($select)){
    	   
    	    $particulars_detail =$get['particulars_detail']; 
    	    $cat_name =$get['name']; 
    
}}

$select_prev=mysqli_query($db,"SELECT *  FROM   particulars   where id='$id' ");
$select_num_prev=mysqli_num_rows($select_prev);
if($select_num_prev !=0){
   	WHILE($get_prev=mysqli_fetch_array($select_prev)){
    	   
    	    $particular =$get_prev['name']; 
    
}}

$new_array = explode(",", "$particulars_detail");
	  if (($key = array_search($particular, $new_array)) !== false) {
    unset($new_array[$key]);
	  $new_checked =  implode(",",$new_array);

$new_part = "$new_checked,$particular_update";
$update=mysqli_query($db,"UPDATE   particular_category   SET 
	particulars_detail='$new_part'
	WHERE front_name='".$_POST['category_update']."' ");	

	  }else{
		$update=mysqli_query($db,"UPDATE   particular_category   SET 
	particulars_detail='".$_POST['particular_update']."'
	WHERE front_name='".$_POST['category_update']."' ");	  
	  }
	$update=mysqli_query($db,"UPDATE   particulars   SET 
	name='".$_POST['particular_update']."',
	account='".$_POST['account']."',
	cat_group='".$_POST['category_update']."',
	category='$cat_name'
	WHERE id='".$_POST['id_no_update']."' ");	
	$update=mysqli_query($db,"UPDATE   vehicle_account   SET 
	name='".$_POST['particular_update']."'	WHERE name='$particular' ");

?>