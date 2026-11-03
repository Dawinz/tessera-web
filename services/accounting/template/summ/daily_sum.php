<?php
include '../connect/connect.php';
$month = "june";
$year = "2025";
$expenses=0;
$select_bus=mysqli_query($db,"SELECT bus FROM    vehicle_account    where month ='$month' and year ='$year' Group by bus ");
$select_num_bus=mysqli_num_rows($select_bus);
if($select_num_bus !=0){
   	WHILE($get_bus=mysqli_fetch_array($select_bus)){
    	    $bus =$get_bus['bus']; 
echo "<br>$bus";

$select_route=mysqli_query($db,"SELECT fullname FROM    route    where status ='Main'  ");
$select_num_route=mysqli_num_rows($select_route);
if($select_num_route !=0){
   	WHILE($get_route=mysqli_fetch_array($select_route)){
    	    $route_name =$get_route['fullname']; 
echo $route_name;
   $select_bus_daily_summ=mysqli_query($db,"SELECT trip_date FROM    vehicle_account    where month ='$month' and year ='$year' and bus='$bus' and route_name='$route_name'Group by trip_date ");
$select_num_bus_daily_summ=mysqli_num_rows($select_bus_daily_summ);
if($select_num_bus_daily_summ !=0){
   	WHILE($get_bus_daily_summ=mysqli_fetch_array($select_bus_daily_summ)){
    	    $trip_date =$get_bus_daily_summ['trip_date'];
echo $trip_date;

$select_route_sum=mysqli_query($db," SELECT SUM(income) AS income From   vehicle_account   WHERE month ='$month' and year ='$year' and bus='$bus' and route_name='$route_name' and trip_date='$trip_date'  ");
$select_num_route_sum=mysqli_num_rows($select_route_sum);
if($select_num_route_sum !=0){
   	WHILE($get_route_sum=mysqli_fetch_array($select_route_sum)){
    	    $income =$get_route_sum['income']; 
echo "-".number_format($income)." ";
    }}
$select_bus_daily_summ_num=mysqli_query($db,"SELECT income,expenses FROM    vehicle_daily    where month ='$month' and year ='$year' and vehicle='$bus' and date='$trip_date' and route='$route_name'  ");
$select_num_bus_daily_summ_num=mysqli_num_rows($select_bus_daily_summ_num);
if($select_num_bus_daily_summ_num ==0){
    $select_route_sum=mysqli_query($db," SELECT SUM(income) AS income From   vehicle_account   WHERE month ='$month' and year ='$year' and bus='$bus' and route_name='$route_name' and trip_date='$trip_date'  ");
$select_num_route_sum=mysqli_num_rows($select_route_sum);
if($select_num_route_sum !=0){
$sqlQ = "INSERT INTO   vehicle_daily    (vehicle,income,expenses,date,month,year,route) VALUES (?,?,?,?,?,?,?)"; 
                $stmt = $db->prepare($sqlQ); 
                $stmt->bind_param("sssssss", $bus,$income, $expenses,$trip_date,$month,$year,$route_name); 
                $insert = $stmt->execute();	

}else{
    $update=mysqli_query($db,"UPDATE  vehicle_daily  SET 
	income='$income',expenses='$expenses'
	WHERE month ='$month' and year ='$year' and bus='$bus' and trip_date='$trip_date' and route_name='$route_name'  ");	

}
}else{
echo "hamna";

}

	}}
    }}

 
/*
$select_route_sum=mysqli_query($db," SELECT SUM(income) AS income From   vehicle_account     ");
$select_num_route_sum=mysqli_num_rows($select_route_sum);
if($select_num_route_sum !=0){
   	WHILE($get_route_sum=mysqli_fetch_array($select_route_sum)){
    	    $income =$get_route_sum['income']; 
echo "$income <br>";
    }}
*/
}
}
?>