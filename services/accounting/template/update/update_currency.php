<?php
$name_update = $_POST['name_update'];
$value_update = $_POST['value_update'];
$symbol_update = $_POST['symbol_update'];
$detail_update = $_POST['detail_update'];
$id_no = $_POST['id_no'];
include '../connect/connect.php';

$select_status=mysqli_query($db,"SELECT status,value_no FROM   currency   where id ='".$_POST['id_no']."'  ");
$select_num_status=mysqli_num_rows($select_status);
if($select_num_status !=0){
   	WHILE($get_status=mysqli_fetch_array($select_status)){
    	    $status =$get_status['status']; 
    	    $value =$get_status['value_no']; 
			
}}


if($status=='<span class="badge badge-primary">Primary</span>'){
	$update=mysqli_query($db,"UPDATE  currency  SET 
	name='".$_POST['name_update']."',
	symbol='".$_POST['symbol_update']."',	
	detail='".$_POST['detail_update']."'	
	WHERE id='".$id_no."' ");
}else{

$update=mysqli_query($db,"UPDATE  currency  SET 
	name='".$_POST['name_update']."',
	value_no='".preg_replace('/[^0-9.]+/', '',$_POST['value_update'])."',	
	symbol='".$_POST['symbol_update']."',	
	detail='".$_POST['detail_update']."'	
	WHERE id='".$id_no."' ");	

}
?>