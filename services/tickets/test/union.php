<?php
include '../connect.php';



$select1=mysqli_query($db,"
SELECT routes.start_route, sub_route.sub_route_id, sub_route.end_route
FROM routes
INNER JOIN sub_route ON sub_route.start_route='Nakonde';
");
$select_num1=mysqli_num_rows($select1);
if($select_num1 !=0){
WHILE($get13=mysqli_fetch_array($select1)){
	
	 $start_route =$get13['start_route']; 
echo "$start_route <br>";
}
}


?>