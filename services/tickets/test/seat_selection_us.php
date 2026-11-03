                    
					<div class="table-responsive">
                    <table style="border:none;">
                      <thead>

                      </thead>
					  <tbody>
<?php
require_once __DIR__ . '/../includes/tessera_tenant.php';

include 'connect.php';

$status = "";
$route_day_id = $_GET['route_day_id'];
$trip_date=$_GET['trip_date'];
$select14=mysqli_query($db,"SELECT *  FROM  route_day WHERE id='$route_day_id' ");
$select_num14=mysqli_num_rows($select14);
if($select_num14 !=0){
   	WHILE($get134=mysqli_fetch_array($select14)){
    	    $route_id =$get134['route_id'];
			$bus =$get134['bus'];
	
		

		
	}
}


$select135=mysqli_query($db,"SELECT seats  FROM  vehicle where name = '$bus' ");
$select_num135=mysqli_num_rows($select135);
if($select_num135 !=0){
   	WHILE($get1335=mysqli_fetch_array($select135)){
$seats= $get1335['seats'];

}}

$x3 =0;
$total_seat = $seats;
$font_seat = $total_seat-5;

$x = 4;
 

 
while($x <= $font_seat) {
	$x3 += 1;
	$alphabet = range('A', 'Z');

$alphabet = range('A', 'Z');




?>
<tr>

<?php
$textArray = ["A", "B", "C","D","E"];
foreach ($textArray as $key=>$value) {
    $key++;
    
	?>
	<td style="width: 20.66%;">
	<?php 
if($key == 3 ){
		$key = "";
		$value="";
		@$image = "";
		
	}if($key > 3 ){
		@$image = "<img src ='images/armchair2.png'>";
		$key = $key - 1;
		$value = ord($value);
		$value = chr($value - 1);
	}if($key < 3 ){
		@$image = "<img src ='images/armchair2.png'>";
	
	}
	if($key ==""){
		
	}else{
		$seat_no = "".$alphabet[$x3-1]."$key";
		
	
								include 'connect.php';
				 $select1=mysqli_query($db,"SELECT *  FROM  booking WHERE " . tessera_sql_tenant_eq() . " AND (route_day_id='$route_day_id' and trip_date='$trip_date' and seat_no='$seat_no') GROUP BY seat_no  ");
$select_num1=mysqli_num_rows($select1);
if($select_num1 !=0){
   	WHILE($get13=mysqli_fetch_array($select1)){
 $status =$get13['status'];  
 $id_seat =$get13['id']; 



if($status=="booked"){
	
		echo "<button style='background:none;border:none;cursor:no-drop;'id='book".$alphabet[$x3-1]."$key'><div id='seat".$alphabet[$x3-1]."$key'style='border-bottom:2px solid #f2f2f2;padding:5px ;color:white;'>&nbsp <span id='seat_no' style='background:#B48C1B;'class='seat_no".$alphabet[$x3-1]."$key'>".$alphabet[$x3-1]."$key</span><br><img id='chair".$alphabet[$x3-1]."$key'src ='images/bus_yellow.png'></div></button>
	<div style='display:none;'id='seat_number".$alphabet[$x3-1]."$key'>".$alphabet[$x3-1]."$key</div>

	" 
. "";	
	
}else if ($status=="complete"){
		echo "<button style='background:none;border:none;border:none;'id='book".$alphabet[$x3-1]."$key'><div id='seat".$alphabet[$x3-1]."$key'style='border-bottom:2px solid #f2f2f2;padding:5px ;'>&nbsp <span id='seat_no' style='background:#DA1616;'class='seat_no".$alphabet[$x3-1]."$key'>".$alphabet[$x3-1]."$key</span><br><img id='chair".$alphabet[$x3-1]."$key'src ='images/seat_red.png'></div></button>
	<div style='display:none;'id='seat_number".$alphabet[$x3-1]."$key'>".$alphabet[$x3-1]."$key</div>
	<div style='display:none;'id='new_text".$alphabet[$x3-1]."$key'>".$id_seat."</div>
	<script>
			$(document).ready(function(){
							$('#book".$alphabet[$x3-1]."$key').click(function(){
var select_seats= $('.select_seats').text();
select_seats = parseInt(select_seats);
new_select_seat = select_seats+1;
$('.select_seats').text(new_select_seat);
			$('#book".$alphabet[$x3-1]."$key').attr('disabled', true);
$('#book".$alphabet[$x3-1]."$key').css('cursor', 'no-drop');
$('#new_text".$alphabet[$x3-1]."$key').addClass('new_select_seat'+new_select_seat);
$('#current_checked".$id_seat."').show();
	 alertify.error('".$alphabet[$x3-1]."$key Checked');
alertify.set('notifier','position', 'top-left');
			});});
			</script>
	" 
. "";	
	
} else if ($status=="half_complete") {
		echo "<button style='background:none;border:none;'id='book".$alphabet[$x3-1]."$key'><div id='seat".$alphabet[$x3-1]."$key'style='border-bottom:2px solid #f2f2f2;padding:5px ;'>&nbsp <span id='seat_no' style='background:green;'class='seat_no".$alphabet[$x3-1]."$key'>".$alphabet[$x3-1]."$key</span><br><img id='chair".$alphabet[$x3-1]."$key'src ='images/armchair_half6.png'></div></button>
	<div style='display:none;'id='seat_number".$alphabet[$x3-1]."$key'>".$alphabet[$x3-1]."$key</div>
	<script>
			$(document).ready(function(){

				var cost= $('#cost').text();
					$('#book".$alphabet[$x3-1]."$key').click(function(){
						$('.spinner-border').show();
$('#chair".$alphabet[$x3-1]."$key').attr('src','images/bus_yellow.png');
						var seat_number = $('#seat".$alphabet[$x3-1]."$key').text();
						 var seat_number = $.trim(seat_number);
						 
						$('#book".$alphabet[$x3-1]."$key').attr('disabled', true);
$('#book".$alphabet[$x3-1]."$key').css('cursor', 'no-drop');
$('.seat_no".$alphabet[$x3-1]."$key').css('background', '#B48C1B');
			var bus = $('#bus').text();
            var route_id = $('#route_id').text();
			var rout_day_id = $('#rout_day_id').text();
			var trip_date = $('#trip_date').text();
			$('.seat_no".$alphabet[$x3-1]."$key').css('background', '#B48C1B');
			
		    $.ajax({
				url:'insert_booking_table.php',
				method:'POST',
				data:{bus:bus,seat_number:seat_number,route_id:route_id,trip_date:trip_date,cost:cost,rout_day_id:rout_day_id},
				success:function(response){
					$('.spinner-border').hide();
		$('#load_seat_table').load('load_seat_table.php?trip_date='+trip_date+'&bus='+bus+'&id='+route_id+'');
			


	}
			
			});	
			
			
			});
			
			});
				
				</script>
	" 
. "";	
}
		
}}else{
		echo "<button style='background:none;border:none; cursor: not-allowed;'id='book".$alphabet[$x3-1]."$key' disabled><div id='seat".$alphabet[$x3-1]."$key'style='border-bottom:2px solid #ff2f2;padding:5px ;' disabled>&nbsp <span id='seat_no' style='background:#f7faf8;color:#3b3b3b'class='seat_no".$alphabet[$x3-1]."$key'>".$alphabet[$x3-1]."$key</span><br><img id='chair".$alphabet[$x3-1]."$key'src ='images/armchair2.png'></div></button>
	<div style='display:none;'id='seat_number".$alphabet[$x3-1]."$key'>".$alphabet[$x3-1]."$key</div>
	<script>
			$(document).ready(function(){

				var cost= $('#cost').text();
					$('#book".$alphabet[$x3-1]."$key').click(function(){
						$('.spinner-border').show();
$('#chair".$alphabet[$x3-1]."$key').attr('src','images/bus_yellow.png');
						var seat_number = $('#seat".$alphabet[$x3-1]."$key').text();
						 var seat_number = $.trim(seat_number);
						$('#book".$alphabet[$x3-1]."$key').attr('disabled', true);
$('#book".$alphabet[$x3-1]."$key').css('cursor', 'no-drop');
$('.seat_no".$alphabet[$x3-1]."$key').css('background', '#B48C1B');
			var bus = $('#bus').text();
            var route_id = $.trim($('#route_id').text());
			var rout_day_id = $.trim($('#rout_day_id').text());
			var trip_date = $('#trip_date').text();
			$('.seat_no".$alphabet[$x3-1]."$key').css('background', '#B48C1B');
			
		    $.ajax({
				url:'insert_booking_table.php',
				method:'POST',
				data:{bus:bus,seat_number:seat_number,route_id:route_id,trip_date:trip_date,cost:cost,rout_day_id:rout_day_id},
				success:function(response){
					$('.spinner-border').hide();
					
										if($.trim(response) == 'first'){
			$('#myModal2').modal('show');

		}
					
		$('#load_seat_table').load('load_seat_table.php?trip_date='+trip_date+'&bus='+bus+'&id='+route_id+'&id='+rout_day_id+'');
		
		$('#load_booking').load('load_booking.php?trip_date='+trip_date+'&bus='+bus+'&id='+route_id+'&id='+rout_day_id+'&route_id='+route_id+'');
		$('#load_booking_sub').load('load_sub_booking.php?trip_date='+trip_date+'&bus='+bus+'&id='+route_id+'&id='+rout_day_id+'&route_id='+route_id+'');
				$('#load_status').load('load_status.php?trip_date='+trip_date+'&bus='+bus+'&id='+route_id+'');

			


	}
			
			});	
			
			
			});
			
			});
				
				</script>
	" 
. "";	
	
	
}
	
	}
	?>
	</td>
	<?php
}

?>


</tr>
<?php
  $x+=4;
}
$x3 = $x3;
	$alphabet = range('A', 'Z');

$alphabet = range('A', 'Z');

?>
<tr>
<?php  
$x = 1;
 
while($x <= 5) {
	$seat_no2 = ''.$alphabet[$x3].''.$x.'';
	
 ?>
 <td style="width: 20.66%;">
<?php
							include 'connect.php';
				 $select1=mysqli_query($db,"SELECT *  FROM  booking WHERE " . tessera_sql_tenant_eq() . " AND (trip_date='$trip_date' AND bus='$bus' and seat_no='$seat_no2')");
$select_num1=mysqli_num_rows($select1);
if($select_num1 !=0){
   	WHILE($get13=mysqli_fetch_array($select1)){
 $status =$get13['status']; 



if($status=="booked"){
	
		echo "<button style='background:none;border:none;cursor:no-drop;'id='book".$alphabet[$x3]."$x'><div id='seat".$alphabet[$x3]."$x'style='border-bottom:2px solid #f2f2f2;padding:5px ;'>&nbsp <span id='seat_no' style='background:#B48C1B;'class='seat_no".$alphabet[$x3]."$x'>".$alphabet[$x3]."$x</span><br><img id='chair".$alphabet[$x3]."$x'src ='images/bus_yellow.png'></div></button>
	<div style='display:none;'id='seat_number".$alphabet[$x3]."$x'>".$alphabet[$x3]."$x</div>

	" 
. "";	
	
}else if ($status=="complete"){
		echo "<button style='background:none;border:none;border:none;cursor:no-drop'id='book".$alphabet[$x3]."$x'><div id='seat".$alphabet[$x3]."$x'style='border-bottom:2px solid #f2f2f2;padding:5px ;'>&nbsp <span id='seat_no' style='background:#DA1616;'class='seat_no".$alphabet[$x3]."$x'>".$alphabet[$x3]."$x</span><br><img id='chair".$alphabet[$x3]."$x'src ='images/seat_red.png'></div></button>
	<div style='display:none;'id='seat_number".$alphabet[$x3]."$x'>".$alphabet[$x3]."$x</div>
	<script>
			$(document).ready(function(){
alert();
			
			});
			</script>
	" 
. "";	
	
}  else if ($status=="half_complete") {
			echo "<button style='background:none;border:none;'id='book".$alphabet[$x3]."$x'><div id='seat".$alphabet[$x3]."$x'style='border-bottom:2px solid #f2f2f2;padding:5px ;' >&nbsp <span id='seat_no' style='background:green;'class='seat_no".$alphabet[$x3]."$x'>".$alphabet[$x3]."$x</span><br><img id='chair".$alphabet[$x3]."$x'src ='images/armchair2.png'></div></button>
	<div style='display:none;'id='seat_number".$alphabet[$x3]."$x'>".$alphabet[$x3]."$x</div>
	<script>
			$(document).ready(function(){

				var cost= $('#cost').text();
					$('#book".$alphabet[$x3]."$x').click(function(){
$('#chair".$alphabet[$x3]."$x').attr('src','images/bus_yellow.png');
						var seat_number = $('#seat".$alphabet[$x3]."$x').text();
						 var seat_number = $.trim(seat_number);
						$('#book".$alphabet[$x3]."$x').attr('disabled', true);
$('#book".$alphabet[$x3]."$x').css('cursor', 'no-drop');
$('.seat_no".$alphabet[$x3]."$x').css('background', '#B48C1B');
			var bus = $('#bus').text();
            var route_id = $('#route_id').text();
						var rout_day_id = $.trim($('#rout_day_id').text());

			var trip_date = $('#trip_date').text();
			$('.seat_no".$alphabet[$x3]."$x').css('background', '#B48C1B');
			
		    $.ajax({
				url:'insert_booking_table.php',
				method:'POST',
				data:{bus:bus,seat_number:seat_number,route_id:route_id,trip_date:trip_date,cost:cost,rout_day_id:rout_day_id},
				success:function(response){
		$('#load_seat_table').load('load_seat_table.php?trip_date='+trip_date+'&bus='+bus+'&id='+route_id+'&id='+rout_day_id+'');
		
			$('#load_booking').load('load_booking.php?trip_date='+trip_date+'&bus='+bus+'&id='+route_id+'&id='+rout_day_id+'&route_id='+route_id+'');
		$('#load_booking_sub').load('load_sub_booking.php?trip_date='+trip_date+'&bus='+bus+'&id='+route_id+'&id='+rout_day_id+'&route_id='+route_id+'');
				$('#load_status').load('load_status.php?trip_date='+trip_date+'&bus='+bus+'&id='+route_id+'&id='+rout_day_id+'');


	}
			
			});	
			
			
			});
			
			});
				
				</script>
	" 
. "";	
	
	
}
		
}}else{
		echo "<button style='background:none;border:none;'id='book".$alphabet[$x3]."$x'><div id='seat".$alphabet[$x3]."$x'style='border-bottom:2px solid #f2f2f2;padding:5px ;' disabled>&nbsp <span id='seat_no' style='background:#f7faf8;color:#3b3b3b'class='seat_no".$alphabet[$x3]."$x'>".$alphabet[$x3]."$x</span><br><img id='chair".$alphabet[$x3]."$x'src ='images/armchair2.png'></div></button>
	<div style='display:none;'id='seat_number".$alphabet[$x3]."$x'>".$alphabet[$x3]."$x</div>
	<script>
			$(document).ready(function(){

				var cost= $('#cost').text();
					$('#book".$alphabet[$x3]."$x').click(function(){
$('#chair".$alphabet[$x3]."$x').attr('src','images/bus_yellow.png');
						var seat_number = $('#seat".$alphabet[$x3]."$x').text();
						 var seat_number = $.trim(seat_number);
						$('#book".$alphabet[$x3]."$x').attr('disabled', true);
$('#book".$alphabet[$x3]."$x').css('cursor', 'no-drop');
$('.seat_no".$alphabet[$x3]."$x').css('background', '#B48C1B');
			var bus = $('#bus').text();
            var route_id = $('#route_id').text();
						var rout_day_id = $.trim($('#rout_day_id').text());

			var trip_date = $('#trip_date').text();
			$('.seat_no".$alphabet[$x3]."$x').css('background', '#B48C1B');
			
		    $.ajax({
				url:'insert_booking_table.php',
				method:'POST',
				data:{bus:bus,seat_number:seat_number,route_id:route_id,trip_date:trip_date,cost:cost,rout_day_id:rout_day_id},
				success:function(response){
		$('#load_seat_table').load('load_seat_table.php?trip_date='+trip_date+'&bus='+bus+'&id='+route_id+'');
		
			$('#load_booking').load('load_booking.php?trip_date='+trip_date+'&bus='+bus+'&id='+route_id+'&id='+rout_day_id+'&route_id='+route_id+'');
		$('#load_booking_sub').load('load_sub_booking.php?trip_date='+trip_date+'&bus='+bus+'&id='+route_id+'&id='+rout_day_id+'&route_id='+route_id+'');
				$('#load_status').load('load_status.php?trip_date='+trip_date+'&bus='+bus+'&id='+route_id+'');


	}
			
			});	
			
			
			});
			
			});
				
				</script>
	" 
. "";	
	
	
}

?></td>
 <?php
  $x++;
} 
?> 


</tr>
   </tbody>
                    </table>
                </div> 
