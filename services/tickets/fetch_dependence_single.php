<?php
require_once __DIR__ . '/includes/tessera_staff_boot.php';
//fetch.php
if(isset($_POST["query"]))
{
	
include 'connect.php';

$select14=mysqli_query($db,"SELECT *  FROM  route_points WHERE start_location='".$_POST["query"]."' AND end_location='".$_POST["ending_location"]."' ");
$select_num14=mysqli_num_rows($select14);
if($select_num14 !=0){
   	WHILE($get134=mysqli_fetch_array($select14)){
    	    $id =$get134['id'];
	
	echo '<option value="'.$get134["detail"].'">'.$get134["detail"].'</option>';	
		
	
}


}else{

$select1455=mysqli_query($db,"SELECT *  FROM  sub_route_points   WHERE start_location='".$_POST["query"]."' AND end_location='".$_POST["ending_location"]."' and point='start' ");
$select_num1455=mysqli_num_rows($select1455);
if($select_num1455 !=0){
   	WHILE($get13455=mysqli_fetch_array($select1455)){
    	    $id =$get13455['id'];
	
	echo '<option value="'.$get13455["detail"].'">'.$get13455["detail"].'</option>';	
		
		
	
}}

	
	
}


}



?>
