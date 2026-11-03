<?php
include '../connect.php';

$select1=mysqli_query($db,"SELECT *  FROM  sub_route_points  ");
$select_num1=mysqli_num_rows($select1);
if($select_num1 !=0){
   	WHILE($get13=mysqli_fetch_array($select1)){
    	    $name =$get13['name']; 
			$time =$get13['time']; 
			$id =$get13['id']; 
	
$sort = "$name - $time";
$update=mysqli_query($db,"UPDATE  sub_route_points SET detail='$sort' WHERE id='$id'");

		
	}
}

?>