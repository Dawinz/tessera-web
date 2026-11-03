<?php
include 'connect.php';
$select2=mysqli_query($db,"SELECT *  FROM  tbl_sample ORDER BY id DESC LIMIT 1;");
$select_num2=mysqli_num_rows($select2);
if($select_num2 !=0){
   	WHILE($get12=mysqli_fetch_array($select2)){
    	    $first_name =$get12['first_name']; 

		echo  $first_name;
	}
}
?>