<?php
require_once __DIR__ . '/includes/tessera_tenant.php';

session_start();
$username=$_SESSION['username'];
include 'connect.php';
$id= $_POST['id'];
$reason= $_POST['reason'];
$reason = base64_encode($reason);
$select2=mysqli_query($db,"SELECT *  FROM  users  where username='".$_SESSION['username']."'");
$select_num2=mysqli_num_rows($select2);
if($select_num2 !=0){
   	WHILE($get12=mysqli_fetch_array($select2)){
    	    $firstname =$get12['firstname'];  
			$surname =$get12['surname'];
			

		
	}
}
$update=mysqli_query($db,"UPDATE  booking SET lock_option='locked',status='locked',locker='".$_SESSION['username']."',locker_fullname='$firstname $surname',locked_reasons='$reason' WHERE " . tessera_sql_tenant_eq() . " AND (id='$id')");



?>