<?php
require_once __DIR__ . '/../includes/tessera_tenant.php';

include 'connect/connect_single.php';

$select1=mysqli_query($db,"SELECT *  FROM  manifest WHERE  route_day_id='$route_day_id' and route_id='$route_id' and trip_date='$trip_date'  ");
$select_num1=mysqli_num_rows($select1);
if($select_num1 ==0){
	
$insert=mysqli_query($db,"INSERT INTO manifest (bus,trip_date,insert_date,seats,amount,start_location,end_location,start_time,route_day_id,day,month,year,driver,conductor,route_id,route_name,sort) VALUES
 ('$bus','$trip_date','$date_insert','1','$fare_price','$start_route_main','$end_route_main','$hours','$route_day_id','$day','$month','$year','$driver','$conductor','$route_id','$route_name','$sort')");	

}else{
$select157=mysqli_query($db,"SELECT *  FROM  booking WHERE " . tessera_sql_tenant_eq() . " AND (status = 'complete'  and route_id='$route_id' and route_day_id='$route_day_id' and trip_date='$trip_date' and delete_book='' OR status = 'half_complete'  and route_id='$route_id' and route_day_id='$route_day_id' and trip_date='$trip_date' and delete_book='')");
$select_num157=mysqli_num_rows($select157);
$total_seat = $select_num157 ;

$sum = 0;
$start_route2 = '';
$query = "
SELECT cost  FROM booking WHERE " . tessera_sql_tenant_eq() . " AND (route_id='$route_id' and route_day_id='$route_day_id' and trip_date='$trip_date')";
$result = mysqli_query($db, $query);
while($row = mysqli_fetch_array($result))
{
 $start_route2 .= $row["cost"];
 $sum = $sum +$row["cost"];    
   
}
	
$insert=mysqli_query($db,"UPDATE  manifest SET amount='$sum',seats='$total_seat' WHERE route_day_id='$route_day_id' and route_id='$route_id' and trip_date='$trip_date'");
	
}

?>