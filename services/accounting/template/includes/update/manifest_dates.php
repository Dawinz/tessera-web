<?php
include '../../connect/connect.php';
$select_route_main=mysqli_query($db,"SELECT * FROM   manifest    ");
$select_num_route_main=mysqli_num_rows($select_route_main);
if($select_num_route_main !=0){
 	WHILE($get_main=mysqli_fetch_array($select_route_main)){
    	    $vehicle_name =$get_main['bus']; 
    	    $trip_date =$get_main['trip_date']; 
    	    $route_name =$get_main['route_name']; 

          $select_history=mysqli_query($db,"SELECT * FROM   vehicle_history    where bus  ='$vehicle_name' and trip_date='$trip_date' and route_name='$route_name'");
$select_num_history=mysqli_num_rows($select_history);
if($select_num_history ==0){
  
$sqlQ = "INSERT INTO   vehicle_history   (bus,trip_date,route_name) VALUES (?,?,?)"; 
                $stmt = $db->prepare($sqlQ); 
                $stmt->bind_param("sss", $vehicle_name,$trip_date,$route_name); 
                $insert = $stmt->execute();	

}
 }}
?>