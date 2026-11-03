<?php
session_start();
$username_session=$_SESSION['username'];
include 'connect.php';
$id = $_POST['id'];
$username = $_POST['username'];


$select1=mysqli_query($db,"SELECT status  FROM  users WHERE id='$id' ");
$select_num1=mysqli_num_rows($select1);
if($select_num1 !=0){
WHILE($get12=mysqli_fetch_array($select1)){
    	    $status =$get12['status']; 

			if($status =="active"){
$update=mysqli_query($db,"UPDATE  users SET status='Not Active',button_name='Activate',button_icon='uil uil-check me-2',button_class='btn btn-soft-success' WHERE id='$id'");
if($username_session==$username){
		echo "redirect";
	}else{
		
	}
			}else{
$update=mysqli_query($db,"UPDATE  users SET status='active',button_name='De activate',button_icon='uil uil-exclamation-octagon me-2',button_class='btn btn-soft-danger' WHERE id='$id'");

			}

}


}
	
?>


