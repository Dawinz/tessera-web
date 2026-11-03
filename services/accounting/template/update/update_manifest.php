<?php
 include '../connect/connect.php';
$route_name = $_POST['route_name'];
$trip_date = $_POST['trip_date'];
$bus = $_POST['bus'];
$id = $_POST['id'];


$select=mysqli_query($db,"SELECT bus,trip_date,route_name,analysed FROM   manifest  where id = '$id' ");
$select_num=mysqli_num_rows($select);
if($select_num !=0){
   	WHILE($get=mysqli_fetch_array($select)){
    	    $bus_org =$get['bus']; 
    	    $trip_date_org =$get['trip_date']; 
    	    $route_name_orginal =$get['route_name']; 
    	    $analysed =$get['analysed']; 


 }}

 if( $analysed==""){
if($route_name ==$route_name_orginal){
    
$update_vehicle_account=mysqli_query($db,"UPDATE  vehicle_account   SET 
	bus='$bus',trip_date='$trip_date' 
	WHERE  route_name='$route_name' and bus='$bus_org' and trip_date='$trip_date_org' ");
$update_vehicle_account=mysqli_query($db,"UPDATE  manifest   SET 
	bus='$bus',trip_date='$trip_date'
	WHERE  id='$id' ");
    
}else{
    echo "equal";
}
 }else{
	echo " analysed";
 }
?>