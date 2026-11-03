<?php
include '../connect/connect.php';

$particular_update = $_POST['particular_update'];
$name_update = $_POST['name_update'];

echo $_POST['name_update'];
$update=mysqli_query($db,"UPDATE   particular_sub   SET 
	name='".$_POST['name_update']."',
	particular_name='".$_POST['particular_update']."'
	WHERE id='".$_POST['id_no']."'  ");

/*

$cat_group = "".$_POST['name2']."(".$_POST['group_cat'].")";


$select=mysqli_query($db,"SELECT *  FROM   particular_category   where id='".$_POST['id_no']."' ");
$select_num=mysqli_num_rows($select);
if($select_num !=0){
   	WHILE($get=mysqli_fetch_array($select)){
    	   
    	    $particulars_detail =$get['particulars_detail']; 
    	    $cat_name =$get['name']; 
    
}}


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