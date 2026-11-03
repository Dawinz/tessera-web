<?php
$state_id = $_POST['state_id'];
				include 'config.php';
    $select1=mysqli_query($db,"SELECT * FROM tkt_booking where trip_id_no = '$state_id'");
$select_num1=mysqli_num_rows($select1);
if($select_num1 !=0){
    	WHILE($get1=mysqli_fetch_array($select1)){
			$id = $get1['trip_id_no'];
	
			
    	  }
		  		
				 $query = "SELECT * FROM trip where trip_id= '$id' ";
  $result = $db->query($query);
  if ($result->num_rows > 0 ) {
               while ($row = $result->fetch_assoc()) {
               
				$route_id = $row['type'];
               }
			
 $query = "SELECT * FROM fleet_type where id= '$route_id' ";
  $result = $db->query($query);
  if ($result->num_rows > 0 ) {
               while ($row = $result->fetch_assoc()) {
               $r_id =  $row['id']; 
			   $seat =  $row['total_seat'];
			   $bus= $row['type'];   
			   $id= $row['id'];
echo '<div class="seat_no">'.$seat.'</div><div class="id">'.$id.'</div><div class="bus">'.$bus.'</div><div class="route_id">'.$state_id.'</div>';
               }
			   
            }
            }
		  }





?>