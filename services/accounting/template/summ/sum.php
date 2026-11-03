<?php
include '../connect/connect.php';
$month = "june";
$year = "2025";
$select_bus=mysqli_query($db,"SELECT bus FROM    vehicle_account    where month ='$month' and year ='$year' Group by bus ");
$select_num_bus=mysqli_num_rows($select_bus);
if($select_num_bus !=0){
   	WHILE($get_bus=mysqli_fetch_array($select_bus)){
    	    $bus =$get_bus['bus']; 

$select_bus_daily=mysqli_query($db,"SELECT trip_date FROM    vehicle_account    where month ='june' and year ='2025' and bus='$bus' Group by trip_date ");
$select_num_bus_daily=mysqli_num_rows($select_bus_daily);
if($select_num_bus_daily !=0){
   	WHILE($get_bus_daily=mysqli_fetch_array($select_bus_daily)){
    	    $trip_date =$get_bus_daily['trip_date']; 
 $total_income_daily = 0;
 $total_expnses_daily = 0;   

  $total_income_month = 0;
 $total_expnses_month = 0;  

$select_bus_daily_summ=mysqli_query($db,"SELECT income,expenses FROM    vehicle_account    where month ='$month' and year ='$year' and bus='$bus' and trip_date='$trip_date'  ");
$select_num_bus_daily_summ=mysqli_num_rows($select_bus_daily_summ);
if($select_num_bus_daily_summ !=0){
   	WHILE($get_bus_daily_summ=mysqli_fetch_array($select_bus_daily_summ)){
    	    $income =$get_bus_daily_summ['income'];
			$expenses =$get_bus_daily_summ['expenses']; 
 $total_income_daily += $income;
 $total_expnses_daily += $expenses;
    
	}}
$select_bus_daily_summ_num=mysqli_query($db,"SELECT income,expenses FROM    vehicle_daily    where month ='$month' and year ='$year' and vehicle='$bus' and date='$trip_date'  ");
$select_num_bus_daily_summ_num=mysqli_num_rows($select_bus_daily_summ_num);
if($select_num_bus_daily_summ_num ==0){
$sqlQ = "INSERT INTO   vehicle_daily    (vehicle,income,expenses,date,month,year) VALUES (?,?,?,?,?,?)"; 
                $stmt = $db->prepare($sqlQ); 
                $stmt->bind_param("ssssss", $bus,$total_income_daily, $total_expnses_daily,$trip_date,$month,$year); 
                $insert = $stmt->execute();	
}else{
$update=mysqli_query($db,"UPDATE  vehicle_account  SET 
	income='$total_income_daily',expenses='$total_expnses_daily'
	WHERE month ='$month' and year ='$year' and bus='$bus' and trip_date='$trip_date' ");	

}



	}}


$select_bus_month_summ=mysqli_query($db,"SELECT income,expenses FROM    vehicle_account    where month ='$month' and year ='$year' and bus='$bus'   ");
$select_num_bus_month_summ=mysqli_num_rows($select_bus_month_summ);
if($select_num_bus_month_summ !=0){
   	WHILE($get_bus_month_summ=mysqli_fetch_array($select_bus_month_summ)){
    	    $income_month =$get_bus_month_summ['income'];
			$expenses_month =$get_bus_month_summ['expenses']; 
 $total_income_month += $income_month;
 $total_expnses_month += $expenses_month;
    
	}}
	//echo " $total_income_month $total_expnses_month<br>";
$select_bus_month_summ_num=mysqli_query($db,"SELECT income,expenses FROM     vehicle_monthly     where month ='$month' and year ='$year' and vehicle='$bus'   ");
$select_num_bus_month_summ_num=mysqli_num_rows($select_bus_month_summ_num);
if($select_num_bus_month_summ_num ==0){

	$sqlQ = "INSERT INTO    vehicle_monthly     (vehicle,income,expenses,month,year) VALUES (?,?,?,?,?)"; 
                $stmt = $db->prepare($sqlQ); 
                $stmt->bind_param("sssss", $bus,$total_income_month, $total_expnses_month,$month,$year); 
                $insert = $stmt->execute();	
}else{
$update=mysqli_query($db,"UPDATE   vehicle_monthly   SET 
	income='$total_income_month',expenses='$total_expnses_month'
	WHERE month ='$month' and year ='$year' and vehicle='$bus' and month='$trip_date' ");	
}

	}
}
?>