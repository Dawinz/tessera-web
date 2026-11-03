<?php
require_once __DIR__ . '/includes/tessera_tenant.php';

session_start();
$username=$_SESSION['username'];
include 'connect.php';
$id= $_POST['id'];
date_default_timezone_set('Africa/Dar_es_Salaam');
$booking_timer = date("h:i:s A", strtotime('+30 minute'));

$select2=mysqli_query($db,"SELECT *  FROM  users  where username='".$_SESSION['username']."'");
$select_num2=mysqli_num_rows($select2);
if($select_num2 !=0){
   	WHILE($get12=mysqli_fetch_array($select2)){
    	    $firstname =$get12['firstname'];  
			$surname =$get12['surname'];
			$firstStringCharacter = substr($surname, 0, 1);
			

		
	}
}





$update=mysqli_query($db,"UPDATE  booking SET status='booked',lock_option='',locker='".$_SESSION['username']."',locker_fullname='$firstname $surname',timer='$booking_timer' WHERE " . tessera_sql_tenant_eq() . " AND (id='$id')");
echo "".$_SESSION['username']."- $firstname $surname'";

?>