                    
					<div class="table-responsive">
                    <table style="border:none;">
                      <thead>

                      </thead>
					  <tbody>
<?php
require_once __DIR__ . '/includes/tessera_tenant.php';

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
 $id_new_seat =$get13['id']; 



if($status=="booked"){
	
		echo "<button style='background:none;border:none;cursor:no-drop;'id='book".$alphabet[$x3-1]."$key'><div id='seat".$alphabet[$x3-1]."$key'style='padding:5px ;color:white;'>&nbsp <span id='seat_no' style='background:#B48C1B;'class='seat_no_new".$alphabet[$x3-1]."$key'>".$alphabet[$x3-1]."$key</span><br><img id='chair".$alphabet[$x3-1]."$key'src ='images/bus_yellow.png'></div></button>
	<div style='display:none;'id='seat_number".$alphabet[$x3-1]."$key'>".$alphabet[$x3-1]."$key</div>

	" 
. "";	
	
}else if ($status=="complete"){
		echo "<button style='background:none;border:none;border:none;cursor:no-drop'id='book".$alphabet[$x3-1]."$key'><div id='seat".$alphabet[$x3-1]."$key'style='padding:5px ;'>&nbsp <span id='seat_no' style='background:#DA1616;'class='seat_no_new".$alphabet[$x3-1]."$key'>".$alphabet[$x3-1]."$key</span><br><img id='chair_new".$alphabet[$x3-1]."$key'src ='images/seat_red.png'></div></button>
	<div style='display:none;'id='seat_number".$alphabet[$x3-1]."$key'>".$alphabet[$x3-1]."$key</div>

	" 
. "";	
	
} else if ($status=="half_complete") {
		echo "<button class='ready_buttons'style='background:none;border:none;'id='book_new".$alphabet[$x3-1]."$key'><div id='seat".$alphabet[$x3-1]."$key'style='padding:5px ;' disabled>&nbsp <span id='seat_no' style='background:#f7faf8;color:#3b3b3b'class='seat_no_new".$alphabet[$x3-1]."$key'>".$alphabet[$x3-1]."$key</span><br><img id='chair_new".$alphabet[$x3-1]."$key'src ='images/armchair2.png'></div></button>
	<div style='display:none;'id='seat_number".$alphabet[$x3-1]."$key'>".$alphabet[$x3-1]."$key</div>
	<script>
			$(document).ready(function(){

				var cost= $('#cost').text();
					$('#book_new".$alphabet[$x3-1]."$key').click(function(){


var new_select_seats_update= $('.checked_seats').text();
new_select_seats_update = parseInt(new_select_seats_update);

total_transferd = $('#total_transferd').text();
total_transferd = parseInt(total_transferd);

new_new_total_transferd = total_transferd + 1;
new_total_transferd_operator = new_new_total_transferd + new_select_seats_update;
final_new_total_transferd_operator=new_total_transferd_operator - new_select_seats_update;
var final_new_total_transferd_operator_new = $('.new_select_seat'+final_new_total_transferd_operator).text();
get_old_seat = $('#get_seat_no'+final_new_total_transferd_operator_new).text();

$('.seat_no'+get_old_seat).css('background', '#f7faf8');
$('.seat_no'+get_old_seat).css('color', '#3b3b3b');
$('#chair'+get_old_seat).attr('src','images/armchair2.png');


var select_seats= $('.select_seats').text();
select_seats = parseInt(select_seats);
new_select_seat = select_seats-1;

if(new_select_seat < 0){
}else{
	$('.select_seats').text(new_select_seat);

}
total_transferd = $('#total_transferd').text();
total_transferd = parseInt(total_transferd);
new_total_transferd=total_transferd+1;
if(new_select_seat < 0){
	
}else{
 $('#total_transferd').text(new_total_transferd);
}
var bus = '".$bus."';
route_id='".$route_id."';
trip_date='".$trip_date."';
rout_day_id='".$route_day_id."';
new_seat_no='".$alphabet[$x3-1]."$key';
if((final_new_total_transferd_operator_new=='') || (new_select_seat < 0)){
	$('#book_new".$alphabet[$x3-1]."$key').attr('disabled', true);
	 alertify.error('Select Seat to Transfer');
alertify.set('notifier','position', 'top-right');

}else{
	$('#book_new".$alphabet[$x3-1]."$key').css('cursor', 'no-drop');
$('.seat_no_new".$alphabet[$x3-1]."$key').css('background', '#DA1616');
$('.seat_no_new".$alphabet[$x3-1]."$key').css('color', 'white');
$('#chair_new".$alphabet[$x3-1]."$key').attr('src','images/seat_red.png');
   $.ajax({
				url:'transfer_multiple.php',
				method:'POST',
				data:{bus:bus,new_seat_no:new_seat_no,route_id:route_id,trip_date:trip_date,rout_day_id:rout_day_id,final_new_total_transferd_operator_new:final_new_total_transferd_operator_new},
				success:function(response){
						$('#book_new".$alphabet[$x3-1]."$key').attr('disabled', true);
			$('#current_checked'+final_new_total_transferd_operator_new).css('background', '#45B377');
			$('#current_checked'+final_new_total_transferd_operator_new).css('color', 'white');
	 alertify.success('Seat transfered from  '+get_old_seat+ 'to '+new_seat_no+'');
alertify.set('notifier','position', 'top-right');
$('#load_new_seats').load('load_new_seats.php?route_day_id='+rout_day_id+'&route_id='+route_id+'&trip_date='+trip_date+'');

	}
			
			});	
	
}
		
			});
			
			});
				
				</script>
	" 
. "";	
	
		
}
		
}}else{
		echo "<button class='ready_buttons'style='background:none;border:none;'id='book_new".$alphabet[$x3-1]."$key'><div id='seat".$alphabet[$x3-1]."$key'style='padding:5px ;' disabled>&nbsp <span id='seat_no' style='background:#f7faf8;color:#3b3b3b'class='seat_no_new".$alphabet[$x3-1]."$key'>".$alphabet[$x3-1]."$key</span><br><img id='chair_new".$alphabet[$x3-1]."$key'src ='images/armchair2.png'></div></button>
	<div style='display:none;'id='seat_number".$alphabet[$x3-1]."$key'>".$alphabet[$x3-1]."$key</div>
	<script>
			$(document).ready(function(){

				var cost= $('#cost').text();
					$('#book_new".$alphabet[$x3-1]."$key').click(function(){


var new_select_seats_update= $('.checked_seats').text();
new_select_seats_update = parseInt(new_select_seats_update);

total_transferd = $('#total_transferd').text();
total_transferd = parseInt(total_transferd);

new_new_total_transferd = total_transferd + 1;
new_total_transferd_operator = new_new_total_transferd + new_select_seats_update;
final_new_total_transferd_operator=new_total_transferd_operator - new_select_seats_update;
var final_new_total_transferd_operator_new = $('.new_select_seat'+final_new_total_transferd_operator).text();
get_old_seat = $('#get_seat_no'+final_new_total_transferd_operator_new).text();

$('.seat_no'+get_old_seat).css('background', '#f7faf8');
$('.seat_no'+get_old_seat).css('color', '#3b3b3b');
$('#chair'+get_old_seat).attr('src','images/armchair2.png');


var select_seats= $('.select_seats').text();
select_seats = parseInt(select_seats);
new_select_seat = select_seats-1;

if(new_select_seat < 0){
}else{
	$('.select_seats').text(new_select_seat);

}
total_transferd = $('#total_transferd').text();
total_transferd = parseInt(total_transferd);
new_total_transferd=total_transferd+1;
if(new_select_seat < 0){
	
}else{
 $('#total_transferd').text(new_total_transferd);
}
var bus = '".$bus."';
route_id='".$route_id."';
trip_date='".$trip_date."';
rout_day_id='".$route_day_id."';
new_seat_no='".$alphabet[$x3-1]."$key';
if((final_new_total_transferd_operator_new=='') || (new_select_seat < 0)){
	$('#book_new".$alphabet[$x3-1]."$key').attr('disabled', true);
	 alertify.error('Select Seat to Transfer');
alertify.set('notifier','position', 'top-right');

}else{
	$('#book_new".$alphabet[$x3-1]."$key').css('cursor', 'no-drop');
$('.seat_no_new".$alphabet[$x3-1]."$key').css('background', '#DA1616');
$('.seat_no_new".$alphabet[$x3-1]."$key').css('color', 'white');
$('#chair_new".$alphabet[$x3-1]."$key').attr('src','images/seat_red.png');
   $.ajax({
				url:'transfer_multiple.php',
				method:'POST',
				data:{bus:bus,new_seat_no:new_seat_no,route_id:route_id,trip_date:trip_date,rout_day_id:rout_day_id,final_new_total_transferd_operator_new:final_new_total_transferd_operator_new},
				success:function(response){
					

						$('#book_new".$alphabet[$x3-1]."$key').attr('disabled', true);
			$('#current_checked'+final_new_total_transferd_operator_new).css('background', '#45B377');
			$('#current_checked'+final_new_total_transferd_operator_new).css('color', 'white');
	 alertify.success('Seat transfered from  '+get_old_seat+ 'to '+new_seat_no+'');
alertify.set('notifier','position', 'top-right');
$('#load_new_seats').load('load_new_seats.php?route_day_id='+rout_day_id+'&route_id='+route_id+'&trip_date='+trip_date+'');

	}
			
			});	
	
}
		
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

	" 
. "";	
	
}  else if ($status=="half_complete") {
			echo "<button style='background:none;border:none;'id='book".$alphabet[$x3]."$x'><div id='seat".$alphabet[$x3]."$x'style='border-bottom:2px solid #f2f2f2;padding:5px ;' disabled>&nbsp <span id='seat_no' style='background:green;'class='seat_no".$alphabet[$x3]."$x'>".$alphabet[$x3]."$x</span><br><img id='chair".$alphabet[$x3]."$x'src ='images/armchair2.png'></div></button>
	<div style='display:none;'id='seat_number".$alphabet[$x3]."$x'>".$alphabet[$x3]."$x</div>

	" 
. "";	
	
	
}
		
}}else{
		echo "<button class='ready_buttons'style='background:none;border:none;'id='book_new".$alphabet[$x3-1]."$x'><div id='seat".$alphabet[$x3-1]."$x'style='border-bottom:2px solid #f2f2f2;padding:5px ;' disabled>&nbsp <span id='seat_no' style='background:#f7faf8;color:#3b3b3b'class='seat_no_new".$alphabet[$x3-1]."$x'>".$alphabet[$x3-1]."$x</span><br><img id='chair_new".$alphabet[$x3-1]."$x'src ='images/armchair2.png'></div></button>
	<div style='display:none;'id='seat_number".$alphabet[$x3-1]."$x'>".$alphabet[$x3-1]."$x</div>
	<script>
			$(document).ready(function(){

				var cost= $('#cost').text();
					$('#book_new".$alphabet[$x3-1]."$x').click(function(){


var new_select_seats_update= $('.checked_seats').text();
new_select_seats_update = parseInt(new_select_seats_update);

total_transferd = $('#total_transferd').text();
total_transferd = parseInt(total_transferd);

new_new_total_transferd = total_transferd + 1;
new_total_transferd_operator = new_new_total_transferd + new_select_seats_update;
final_new_total_transferd_operator=new_total_transferd_operator - new_select_seats_update;
var final_new_total_transferd_operator_new = $('.new_select_seat'+final_new_total_transferd_operator).text();
get_old_seat = $('#get_seat_no'+final_new_total_transferd_operator_new).text();

$('.seat_no'+get_old_seat).css('background', '#f7faf8');
$('.seat_no'+get_old_seat).css('color', '#3b3b3b');
$('#chair'+get_old_seat).attr('src','images/armchair2.png');


var select_seats= $('.select_seats').text();
select_seats = parseInt(select_seats);
new_select_seat = select_seats-1;

if(new_select_seat < 0){
}else{
	$('.select_seats').text(new_select_seat);

}
total_transferd = $('#total_transferd').text();
total_transferd = parseInt(total_transferd);
new_total_transferd=total_transferd+1;
if(new_select_seat < 0){
	
}else{
 $('#total_transferd').text(new_total_transferd);
}
var bus = '".$bus."';
route_id='".$route_id."';
trip_date='".$trip_date."';
rout_day_id='".$route_day_id."';
new_seat_no='".$alphabet[$x3-1]."$x';
if((final_new_total_transferd_operator_new=='') || (new_select_seat < 0)){
	$('#book_new".$alphabet[$x3-1]."$x').attr('disabled', true);
	 alertify.error('Select Seat to Transfer');
alertify.set('notifier','position', 'top-right');

}else{
	$('#book_new".$alphabet[$x3-1]."$x').css('cursor', 'no-drop');
$('.seat_no_new".$alphabet[$x3-1]."$x').css('background', '#DA1616');
$('.seat_no_new".$alphabet[$x3-1]."$x').css('color', 'white');
$('#chair_new".$alphabet[$x3-1]."$x').attr('src','images/seat_red.png');
   $.ajax({
				url:'transfer_multiple.php',
				method:'POST',
				data:{bus:bus,new_seat_no:new_seat_no,route_id:route_id,trip_date:trip_date,rout_day_id:rout_day_id,final_new_total_transferd_operator_new:final_new_total_transferd_operator_new},
				success:function(response){
						$('#book_new".$alphabet[$x3-1]."$x').attr('disabled', true);
			$('#current_checked'+final_new_total_transferd_operator_new).css('background', '#45B377');
			$('#current_checked'+final_new_total_transferd_operator_new).css('color', 'white');
	 alertify.success('Seat transfered from  '+get_old_seat+ 'to '+new_seat_no+'');
alertify.set('notifier','position', 'top-right');
$('#load_new_seats').load('load_new_seats.php?route_day_id='+rout_day_id+'&route_id='+route_id+'&trip_date='+trip_date+'');

	}
			
			});	
	
}
		
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
