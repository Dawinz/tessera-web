<?php
include '../connect/connect.php';

$id=$_POST['id'];



$select_currency=mysqli_query($db,"SELECT status  FROM   currency   where id='$id'");
$select_num_currency=mysqli_num_rows($select_currency);
if($select_num_currency !=0){
	
	 	WHILE($get_status=mysqli_fetch_array($select_currency)){
    	    $status =$get_status['status']; 
	}
	if($status=='<span class="badge badge-primary">Primary</span>'){
		echo "found";
	}else{
	$delete=mysqli_query($db,"DELETE from currency WHERE id = '$id'");


	}
}


?>