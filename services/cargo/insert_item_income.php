<?php
require_once __DIR__ . '/includes/tessera_staff_boot.php';
session_start();
?>
<?php

$category = $_POST['category'];
$name = $_POST['name'];

date_default_timezone_set('Africa/Dar_es_Salaam');
$date_insert = date(' m/d/Y', time());
$date_time_insert = date(' h:i A', time());


include'connect.php';
$select1=mysqli_query($db,"SELECT *  FROM  expenses_list WHERE name='$name' ");
$select_num1=mysqli_num_rows($select1);
if($select_num1 !=0){
		echo "exist";

}else{
			  $insert=mysqli_query($db,"INSERT INTO expenses_list VALUES('','$name','$category','$date_insert','$date_time_insert','".$_SESSION['username']."','income')");

}


	
    	  

?>