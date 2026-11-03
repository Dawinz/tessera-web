<?php
require_once __DIR__ . '/includes/tessera_staff_boot.php';
include 'connect.php';

$id=$_POST['delete_id'];

$select222=mysqli_query($db,"SELECT *  FROM  vehicle_name  where id='$id'");
$select_num222=mysqli_num_rows($select222);
if($select_num222 !=0){
   	WHILE($get122=mysqli_fetch_array($select222)){
    	    $due =$get122['due']; 
			if($due == 0){
				$insert=mysqli_query($db,"DELETE from vehicle_name WHERE id = '$id'");

			}else{
								echo "found";

			}

		
	}
}


?>