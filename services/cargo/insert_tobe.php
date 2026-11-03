<?php
require_once __DIR__ . '/includes/tessera_staff_boot.php';
include'connect.php';
$select1=mysqli_query($db,"SELECT *  FROM parcel_info where status = 'paid'");
$select_num1=mysqli_num_rows($select1);

if($select_num1 !=0){
   	WHILE($get13=mysqli_fetch_array($select1)){
    
		  $id=$get13['id'];  
		 
	;
		
	}
}

?>