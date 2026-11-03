<?php
require_once __DIR__ . '/includes/tessera_tenant.php';

include 'connect.php';
$select_seat=mysqli_query($db,"SELECT *  FROM  booking WHERE " . tessera_sql_tenant_eq() . " AND (route_day_id='$id' and trip_date='$trip_date' and status='complete' or route_day_id='$id' and trip_date='$trip_date' and status='half_complete')");
$select_num_seat=mysqli_num_rows($select_seat);

$query = "
	SELECT 
	ROW_NUMBER() OVER (
		ORDER BY seat_no ASC 
	) row_num,
    firstname,
    id,
	lastname,
	seat_no,
	phone_no,
	validation,
	start_location,
	ending_location,sub_route_id,amount_left,status,cost,start_route,end_route
FROM 
	booking WHERE   route_day_id='$id' and trip_date='$trip_date' and delete_book='' 
ORDER BY 
	seat_no ASC 
";
$result = mysqli_query($db, $query);
while($row = mysqli_fetch_array($result))
{
	
	       $row_num =$row['row_num'];
		 $firstname =$row['firstname'];
    	    $lastname =$row['lastname'];
			$seat_no =$row['seat_no'];
			$phone_no =$row['phone_no'];
			$id2 =$row['id'];
			$validation =$row['validation'];
			$status =$row['status'];
			$cost =$row['cost'];
			
			$start_location =$row['start_location'];
			$ending_location =$row['ending_location'];
			$start_route =$row['start_route'];
			$end_route =$row['end_route'];
			$sub_route_id =$row['sub_route_id'];
						$amount_left =$row['amount_left'];
		$select1734=mysqli_query($db,"SELECT *  FROM  sub_route Where sub_route_id='$sub_route_id'");
$select_num1734=mysqli_num_rows($select1734);
if($select_num1734 !=0){
   	WHILE($get13734=mysqli_fetch_array($select1734)){
 
 $start_route_sub= $get13734['start_route'];
 $end_route_sub= $get13734['end_route'];
 $a_time= $get13734['a_time'];
 $d_time= $get13734['d_time']; 
}}
}
?>