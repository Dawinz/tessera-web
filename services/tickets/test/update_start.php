	<?php
require_once __DIR__ . '/../includes/tessera_tenant.php';

	 include '../connect.php';
	$select14=mysqli_query($db,"SELECT *  FROM  booking WHERE " . tessera_sql_tenant_eq() . " AND (sub_route_id !='')");
$select_num14=mysqli_num_rows($select14);
if($select_num14 !=0){
   	WHILE($get134=mysqli_fetch_array($select14)){
    	    $id =$get134['id'];
    	    $route_id =$get134['route_id'];
				$select145=mysqli_query($db,"SELECT *  FROM  sub_route  WHERE main_route_no='$route_id'");
$select_num145=mysqli_num_rows($select145);
if($select_num145 !=0){
   	WHILE($get1345=mysqli_fetch_array($select145)){
    	    
			$start_route =$get1345['start_route'];
			$end_route =$get1345['end_route'];
	
echo "$start_route $end_route<br>";	

$insert=mysqli_query($db,"UPDATE  booking SET start_route='$start_route',end_route='$end_route' WHERE " . tessera_sql_tenant_eq() . " AND (id='$id')");

}
		
}		
	
}}?>