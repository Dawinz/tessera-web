<?php
require_once __DIR__ . '/../includes/tessera_tenant.php';


include 'connect/connect_single.php';

$select_new=mysqli_query($db,"SELECT id  FROM  booking WHERE " . tessera_sql_tenant_eq() . " AND (route_id='$new_route_id' and route_day_id='$new_route_day_id' and trip_date='$new_trip_date' and delete_book='')");
$select_num_new=mysqli_num_rows($select_new);
$total_seat_new = $select_num_new ;

$sum_new = 0;

$query_new = "
SELECT cost  FROM booking WHERE " . tessera_sql_tenant_eq() . " AND (route_id='$new_route_id' and route_day_id='$new_route_day_id' and trip_date='$new_trip_date' and delete_book='')";
$result_new = mysqli_query($db, $query_new);
while($row_new = mysqli_fetch_array($result_new))
{
 $sum_new = $sum_new +$row_new["cost"];    
   
}



$select_old=mysqli_query($db,"SELECT id  FROM  booking WHERE " . tessera_sql_tenant_eq() . " AND (route_id='$route_id_prev' and route_day_id='$route_day_id_prev' and trip_date='$trip_date_prev' and delete_book='')");
$select_num_old=mysqli_num_rows($select_old);
$total_seat_old = $select_num_old ;

$sum_old = 0;

$query = "
SELECT cost  FROM booking WHERE " . tessera_sql_tenant_eq() . " AND (route_id='$route_id_prev' and route_day_id='$route_day_id_prev' and trip_date='$trip_date_prev' and delete_book='')";
$result = mysqli_query($db, $query);
while($row = mysqli_fetch_array($result))
{
 $sum_old = $sum_old +$row["cost"];    
   
}

$update=mysqli_query($db,"UPDATE  manifest SET amount='$sum_old',seats='$total_seat_old' WHERE route_day_id='$route_day_id_prev' and route_id='$route_id_prev' and trip_date='$trip_date_prev'");
$update=mysqli_query($db,"UPDATE  manifest SET amount='$sum_new',seats='$total_seat_new' WHERE route_day_id='$new_route_day_id' and route_id='$new_route_id' and trip_date='$new_trip_date'");

if($total_seat_old==0){
    $insert=mysqli_query($db,"DELETE from manifest WHERE route_day_id='$route_day_id_prev' and route_id='$route_id_prev' and trip_date='$trip_date_prev'");
}


?>