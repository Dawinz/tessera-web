<?php
include '../connect/connect.php';


$id = $_POST['id_no'];
$cat_group = "".$_POST['name2']."(".$_POST['group_cat'].")";
$name = $_POST['name2'];

$select=mysqli_query($db,"SELECT *  FROM   particular_category   where id='".$_POST['id_no']."' ");
$select_num=mysqli_num_rows($select);
if($select_num !=0){
   	WHILE($get=mysqli_fetch_array($select)){
    	   
    	    $particulars_detail =$get['particulars_detail']; 
    	    $cat_name =$get['name']; 
    
}}


if($cat_name != $name){

$update=mysqli_query($db,"UPDATE   vehicle_account   SET 
	category='".$_POST['name2']."'	WHERE category='$cat_name' ");

$update=mysqli_query($db,"UPDATE   particular_category   SET 
	name='".$_POST['name2']."',
	category='".$_POST['category2']."'
	WHERE id='".$_POST['id_no']."' ");

	
	
$update=mysqli_query($db,"UPDATE   particulars   SET 
	cat_group='$cat_group',
	category='".$_POST['category2']."'
	WHERE category='".$_POST['category2']."' ");	
}

/*

$update=mysqli_query($db,"UPDATE   particular_category   SET 
	name='".$_POST['name2']."',
	category='".$_POST['category2']."'
	WHERE id='".$_POST['id_no']."' ");

	
	
$update=mysqli_query($db,"UPDATE   particulars   SET 
	cat_group='$cat_group',
	category='".$_POST['category2']."'
	WHERE category='".$_POST['category2']."' ");	
	*/
?>