<?php
require_once __DIR__ . '/includes/tessera_staff_boot.php';
$route_day_id=$_GET['route_day_id'];
$trip_date=$_GET['trip_date'];
date_default_timezone_set('Africa/Dar_es_Salaam');
$date_insert = date('l jS \of F Y', time());
$date_time_insert = date(' h:i A', time());

include 'connect.php';
$select14=mysqli_query($db,"SELECT *  FROM  route_day WHERE id='$route_day_id' ");
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
		<th>Seat</th><th>Undo</th>
		
		
		
		
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
	ending_location,transfer_id
FROM 
	booking WHERE   route_day_id='$route_day_id' and trip_date='$trip_date' and status !='booked'
ORDER BY 
	id 
		
		");
$select_num1450=mysqli_num_rows($select1450);
if($select_num1450 !=0){
   	WHILE($get13450=mysqli_fetch_array($select1450)){
    	    $firstname =$get13450['firstname'];
    	    $lastname =$get13450['lastname'];
			$seat_no =$get13450['seat_no'];
			$phone_no =$get13450['phone_no'];
			$id2 =$get13450['id'];
			$validation =$get13450['validation'];
			$validation =$get13450['validation'];
			
			$start_location =$get13450['start_location'];
			$ending_location =$get13450['ending_location'];
			$transfer_id =$get13450['transfer_id'];
			$id =$get13450['id'];
			$row_num =$get13450['row_num'];
    	?>
		<tr>
		<td><?php echo $row_num;?></td>
		<td><?php echo "$firstname";?></td>
		<td><?php echo "$seat_no";?></td>
		
		
		
		<td>
		<?php if($transfer_id==0){
			
		}else{
			?>
			<button type="button" id="undo<?php echo $id;?>"class="btn btn-soft-danger"><i class="uil  uil-corner-up-left "></i></button>
			<script>
			$(document).ready(function(){
$("#undo<?php echo $id;?>").click(function(){
id= "<?php echo $id;?>";
route_day_id_new_one="<?php echo $route_day_id;?>";
route_id_new_one="<?php echo $route_id;?>";
trip_date_new_one="<?php echo $trip_date;?>";
$.ajax({
				url:"undo.php",
				method:"POST",
				data:{id:id},
				success:function(response){
						if($.trim(response)==""){
							
						}else{
				response_back = $.trim(response);			
$('#chair'+response_back).attr('src','images/seat_red.png');
$('.seat_no'+response_back).css('background', '#DA1616');
$('.seat_no'+response_back).css('color', 'white');

$('#chair_new'+response_back).attr('src','images/armchair2.png');
$('.seat_no_new'+response_back).css('background', '#f7faf8');
$('.seat_no_new'+response_back).css('color', '#3b3b3b');

	 alertify.success('undo :'+response_back);
alertify.set('notifier','position', 'top-right');						}
$("#load_new_seats").load("load_new_seats.php?route_day_id="+route_day_id_new_one+"&route_id="+route_id_new_one+"&trip_date="+trip_date_new_one+"");

	}
			
			});

});
			});
			</script>
			<?php
			
		}?>
		</td>
		
		</tr>
		
		
		
		<?php

		

		
	}
}
		?>
		
		
		
		</table> 