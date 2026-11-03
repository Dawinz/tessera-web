<?php
require_once __DIR__ . '/includes/tessera_staff_boot.php';
$id=$_GET['route_day_id'];
$trip_date=$_GET['trip_date'];
date_default_timezone_set('Africa/Dar_es_Salaam');
$date_insert = date('l jS \of F Y', time());
$date_time_insert = date(' h:i A', time());

include 'connect.php';
$select14=mysqli_query($db,"SELECT *  FROM  route_day WHERE id='$id' ");
$select_num14=mysqli_num_rows($select14);
if($select_num14 !=0){
   	WHILE($get134=mysqli_fetch_array($select14)){
    	    $route_id =$get134['route_id'];
			$bus =$get134['bus'];
	
		

		
	}
}

$select14=mysqli_query($db,"SELECT *  FROM  routes WHERE route_id='$route_id' ");
$select_num14=mysqli_num_rows($select14);
if($select_num14 !=0){
   	WHILE($get134=mysqli_fetch_array($select14)){
    	    $fare_price =$get134['fare_price'];
			$start_route_r =$get134['start_route'];
			$end_route_r =$get134['end_route'];

		

		
	}
}

$select145=mysqli_query($db,"SELECT *  FROM  vehicle WHERE name='$bus' ");
$select_num145=mysqli_num_rows($select145);
if($select_num145 !=0){
   	WHILE($get1345=mysqli_fetch_array($select145)){
    	    $driver =$get1345['driver'];
    	    $conductor =$get1345['conductor'];

		

		
	}
}




?>
     <table class="table mb-0">
		<th></th>
		<th>Fullname</th>
		<th>Seat</th>
		<th> no</th>
		<th>Tel-no</th>

		
		
		<?php
		
		$select1450=mysqli_query($db,"
		
		SELECT 
	ROW_NUMBER() OVER (
		ORDER BY id
	) row_num,
    firstname,
    id,
	lastname,
	seat_no,
	phone_no,
	validation,
	start_location,
	ending_location
FROM 
	booking WHERE   route_day_id='$id' and trip_date='$trip_date' and status !='booked'
ORDER BY 
	id 
		
		");
$select_num1450=mysqli_num_rows($select1450);
if($select_num1450 !=0){
   	WHILE($get13450=mysqli_fetch_array($select1450)){
		    $id = $get13450['id'];
    	    $firstname =$get13450['firstname'];
    	    $lastname =$get13450['lastname'];
			$seat_no =$get13450['seat_no'];
			$phone_no =$get13450['phone_no'];
			$id2 =$get13450['id'];
			$validation =$get13450['validation'];
			
			$start_location =$get13450['start_location'];
			$ending_location =$get13450['ending_location'];
			$row_num =$get13450['row_num'];
    	?>
		<tr>
		<td><?php echo $row_num;?></td>
		<td><?php echo "$firstname";?></td>
		<td><?php echo "$seat_no";?></td>
		<td><?php echo "$id2";?></td>
		<td><?php echo "$phone_no";?>
		    <span style="display:none;background:#F34E4E;border-radius:15px;" id="current_checked<?php echo "$id";?>">
                                            <i class=" uil  uil-check " style="padding:2px;color:white;"></i>
                                        </span>
		</td>
		<td>
                                     
                                   
		</td>
		

		
		</tr>
		
		
		
		<?php

		

		
	}
}
		?>
		
		
		
		</table> 