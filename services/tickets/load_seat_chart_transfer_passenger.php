
 <style>
#seat_array {
  spanst-style-type: none;
  margin-top: 8;
  padding: 0;
  overflow: hidden;
  
}

.seat_col {
  float: left;
  color:white;
  
}

.available_new_seat {
color:white;
padding:15px;
margin-left:8px;
width:50px;
background-image: url("images/armchair_white.png");
background-size: contain;
background-repeat: no-repeat;
height:45px;
cursor: pointer;
}
.available_new_seat_booked {
color:black;
padding:15px;
margin-left:8px;
width:50px;
background-image: url("images/armchair_booked.png");
background-size: contain;
background-repeat: no-repeat;
height:45px;
cursor: pointer;
}.available_new_seat_half_booked {
color:black;
padding:15px;
margin-left:8px;
width:50px;
background-image: url("images/armchair_booked.png");
background-size: contain;
background-repeat: no-repeat;
height:45px;
cursor: pointer;
}
.available_new_seat_complete {
color:white;
padding:15px;
margin-left:8px;
width:50px;
background-image: url("images/complete15.png");
background-size: contain;
background-repeat: no-repeat;
height:45px;
cursor: no-drop;

}.available_new_seat_complete_blinker {
color:white;
padding:15px;
margin-left:8px;
width:50px;
background-image: url("images/complete15.png");
background-size: contain;
background-repeat: no-repeat;
height:45px;
cursor: no-drop;
 animation: blinker 1s linear infinite;
}
.available_new_seat_booked_r {
color:black;
padding:15px;
margin-left:8px;
width:50px;
background-image: url("images/reserved.png");
background-size: contain;
background-repeat: no-repeat;
height:45px;
cursor: no-drop;
}.available_new_seat_locked_r {
color:black;
padding:15px;
margin-left:8px;
width:50px;
background-image: url("images/lock.png");
background-size: contain;
background-repeat: no-repeat;
height:45px;
cursor: no-drop;

}.available_new_seat_half {
color:black;
padding:15px;
margin-left:8px;
width:50px;
background-image: url("images/green.png");
background-size: contain;
background-repeat: no-repeat;
height:45px;
cursor: pointer;


}
.center_seat {
color:black;
padding:15px;
margin-left:8px;
width:50px;
background-size: contain;
background-repeat: no-repeat;
height:10px;
}
.bus_name{
	color:white;
	font-weight:bold;
	font-size:17px;
	text-align  :center;
}

@keyframes blinker {
  50% {
    opacity: 0;
  }
}
</style>

<?php
require_once __DIR__ . '/includes/tessera_tenant.php';

$id = @$_GET['id'];
$trip_date = @$_GET['trip_date'];
$trip_date = date("Y-m-d", strtotime($trip_date));  
$route_id = @$_GET['route_id'];
$start_location = @$_GET['start_location'];
$ending_location = @$_GET['ending_location'];
$bus = @$_GET['bus'];
$seat_no_b = @$_GET['seat_no'];
$sub_route_id = @$_GET['sub_route_id'];
$fare_price = @$_GET['fare_price'];
$start_location = @preg_replace('/_/',' ', $start_location);
$ending_location = @preg_replace('/_/',' ', $ending_location);



include 'connect.php';
$select1356=mysqli_query($db,"SELECT *  FROM  vehicle WHERE name='$bus'");
$select_num1356=mysqli_num_rows($select1356);
if($select_num1356 !=0){
   	WHILE($get13356=mysqli_fetch_array($select1356)){
 

 $seats= $get13356['seats'];



}}



?>
<div id="start_location_shift_t"class="display_non"><?php echo $start_location;?></div>
<div id="ending_location_shift_t"class="display_non"><?php echo $ending_location;?></div>
<div id="route_day_id_shift_t"class="display_non"><?php echo $id;?></div>
<div id="trip_date_shift_t"class="display_non"><?php echo $trip_date;?></div>
<div id="route_id_shift_t"class="display_non"><?php echo $route_id;?></div>
<div id="seat_shift_t"class="display_non"><?php echo $seat_no_b;?></div>
<div id="fare_price_shift_t"class="display_non"><?php echo $fare_price;?></div>
<div id="sub_route_id_shift_t"class="display_non"><?php echo $sub_route_id;?></div>
<div id="bus_shift_t"class="display_non"><?php echo $bus;?></div>



<div id="pick">
 <div class="center bus_seat_holder" style="position:relative;left:20%">
                                       <h5 class="route_loaded_date2 "style="color:white;"><?php echo $bus;?></h5> 
									 
									  </div>
<img src="images/arrow-right_grey.png">
<?php

$var_56 = function($seats,$id,$trip_date,$route_id) {
 $alphabet = range('A', 'Z');


 
 $add_number = 0;
if( $seats==49 ){
	 $seat = array("ii-ii", "ii-ii", "ii-ii", "ii-ii", "ii-ii", "ii-ii", "ii-ii", "ii-ii", "ii-ii", "ii-ii", "ii-ii", "iiiii");

 }else if ( $seats==47 ){
		 $seat = array("ii-ii", "ii-ii", "ii-ii", "ii-ii", "ii-ii", "ii-ii", "ii-ii", "ii-ii", "ii-ii", "ii-ii",  "iiiii");
 
 }else if ( $seats==57 ){
$seat = array("ii-ii", "ii-ii", "ii-ii", "ii-ii", "ii-ii", "ii-ii", "ii-ii", "ii-ii", "ii-ii", "ii-ii", "ii-ii","ii-ii","ii-ii", "iiiii"); 
 }else if ($seats==59 ){
	 
	 
$seat = array("ii-ii", "ii-ii", "ii-ii", "ii-ii", "ii-ii", "ii-ii", "ii-ii", "ii-ii", "ii-ii", "ii-ii", "ii-ii","ii-ii","ii-ii", "iiiii");
 
 }
  else if ($seats==37 ){
$seat = array("i-ii", "i-ii", "i-ii", "i-ii", "i-ii", "i-ii", "i-ii", "i-ii", "i-ii","i-ii",  "iiii");
 
 }
 $arrLength = count($seat);
?>
<div id="seat_array">
<?php

 if( $seats==49 ){
	

 }else if ( $seats==47 ){
		
 
 }else if ( $seats==57 ){

 }else if ($seats==59 ){
include 'connect.php';

 $select1=mysqli_query($db,"SELECT *  FROM  booking WHERE " . tessera_sql_tenant_eq() . " AND (route_day_id='$id' and trip_date='$trip_date' and seat_no='xt1' and route_id ='$route_id' and delete_book='')");
$select_num1=mysqli_num_rows($select1);
if($select_num1 !=0){
   	WHILE($get13=mysqli_fetch_array($select1)){
 $seat_no_d =$get13['seat_no']; 
 $status =$get13['status']; 
 $locker_fullname =$get13['locker_fullname']; 
 $locked_reasons =$get13['locked_reasons']; 
$locked_reasons = base64_decode($locked_reasons);


}
if($status =="complete"){
		echo "<span id='test'class='first available_new_seat_complete seat_col 'style=''><span  id='seat_no'style='position:relative;bottom:4px;'>xt1</span></span>";

}else if($status =="booked"){
			echo "<span id='test'class='first available_new_seat_booked_r seat_col 'style=''><span  id='seat_no'style='position:relative;bottom:4px;'>xt1</span></span>";

}else if ($status =="locked"){
			echo "<span id='test'class='first available_new_seat_locked_r seat_col 'style=''><span  id='seat_no'style='position:relative;bottom:4px;'>xt1</span></span>";
	
}else if ($status =="half_complete"){
			echo "<span id='test'class='first available_new_seat_half seat_col seat_name'style=''><span  id='seat_no'style='position:relative;bottom:4px;'>xt1</span></span>";
	
}

}else{
			echo "<span id='test'class='first available_new_seat seat_col seat_name'style=''><span  id='seat_no'style='position:relative;bottom:4px;'>xt1</span></span>";

}


  $select1=mysqli_query($db,"SELECT *  FROM  booking WHERE " . tessera_sql_tenant_eq() . " AND (route_day_id='$id' and trip_date='$trip_date' and seat_no='xt2' and route_id ='$route_id' and delete_book='')");
$select_num1=mysqli_num_rows($select1);
if($select_num1 !=0){
   	WHILE($get13=mysqli_fetch_array($select1)){
 $seat_no_d =$get13['seat_no']; 
 $status =$get13['status']; 
 $locker_fullname =$get13['locker_fullname']; 
 $locked_reasons =$get13['locked_reasons']; 
$locked_reasons = base64_decode($locked_reasons);


}
if($status =="complete"){
		echo "<span id='test'class='first available_new_seat_complete seat_col 'style=''><span  id='seat_no'style='position:relative;bottom:4px;'>xt2</span></span>";

}else if($status =="booked"){
			echo "<span id='test'class='first available_new_seat_booked_r seat_col 'style=''><span  id='seat_no'style='position:relative;bottom:4px;'>xt2</span></span>";

}else if ($status =="locked"){
			echo "<span id='test'class='first available_new_seat_locked_r seat_col 'style=''><span  id='seat_no'style='position:relative;bottom:4px;'>xt2</span></span>";
	
}else if ($status =="half_complete"){
			echo "<span id='test'class='first available_new_seat_half seat_col seat_name'style=''><span  id='seat_no'style='position:relative;bottom:4px;'>xt2</span></span>";
	
}

}else{
			echo "<span id='test'class='first available_new_seat seat_col seat_name'style=''><span  id='seat_no'style='position:relative;bottom:4px;'>xt2</span></span>";

} 

 
 
 }

		

?>
</div>
<?php

for($i = 0; $i < $arrLength; $i++) {
	?>
	<div id="seat_array">
	<?php
	
	$value = $seat[$i];
	$my_array1 = str_split($value);
$sub_string_total = strlen($value);

$str = $value; 
$sub_string_no= substr_count($str, "i"); 

$new_no = 1;
for($x = 0; $x < $sub_string_total; $x++) {	

	$new_number = $value[$x];
if($new_number=="-"){
	echo "<span class='first center_seat seat_col'></span>";
}else{
$new_no = $new_no +1;	
$new_minus = $new_no - 1;
$new_again = $new_minus + $add_number;
$let_no = $new_minus;
$new_seat_row=$i+1;
$letter = $alphabet[$i];
$seat_no = ''.$letter.''.$let_no.'';

//////////// start//////
include 'connect.php';
$id = @$_GET['id'];
$trip_date = @$_GET['trip_date'];
$route_id = @$_GET['route_id'];
$start_location = @$_GET['start_location'];
$ending_location = @$_GET['ending_location'];
$bus = @$_GET['bus'];
$fare_price = @$_GET['fare_price'];
$start_location = @preg_replace('/_/',' ', $start_location);
$ending_location = @preg_replace('/_/',' ', $ending_location);

 $select1=mysqli_query($db,"SELECT *  FROM  booking WHERE " . tessera_sql_tenant_eq() . " AND (route_day_id='$id' and trip_date='$trip_date' and seat_no='$seat_no' and route_id ='$route_id' and delete_book='')");
$select_num1=mysqli_num_rows($select1);
if($select_num1 !=0){
   	WHILE($get13=mysqli_fetch_array($select1)){
 $seat_no_d =$get13['seat_no']; 
 $status =$get13['status']; 
 $locker_fullname =$get13['locker_fullname']; 
 $locked_reasons =$get13['locked_reasons']; 
$locked_reasons = base64_decode($locked_reasons);
}

if($status =="complete"){
	
	if($seat_no == @$_GET['seat_no']){
			echo "<span id='test'class='first available_new_seat_complete_blinker seat_col 'style=''><span  id='seat_no'style='position:relative;bottom:4px;'>".$letter."".$let_no."</span></span>";
	
	}else{
				echo "<span id='test'class='first available_new_seat_complete seat_col 'style=''><span  id='seat_no'style='position:relative;bottom:4px;'>".$letter."".$let_no."</span></span>";

	}

}else if($status =="booked"){
			echo "<span id='test'class='first available_new_seat_booked_r seat_col 'style=''><span  id='seat_no'style='position:relative;bottom:4px;'>".$letter."".$let_no."</span></span>";

}else if ($status =="locked"){
			echo "<span id='test'class='first available_new_seat_locked_r seat_col 'style=''><span  id='seat_no'style='position:relative;bottom:4px;'>".$letter."".$let_no."</span></span>";
	
}else if ($status =="half_complete"){
			echo "<span id='test'class='first available_new_seat_half seat_col seat_name'style=''><span  id='seat_no'style='position:relative;bottom:4px;'>".$letter."".$let_no."</span></span>";
	
}
}else{
		echo "<span id='test'class='first available_new_seat seat_col seat_name'style=''><span  id='seat_no'style='position:relative;bottom:4px;'>".$letter."".$let_no."</span></span>";

}


}
}

$add_number=$add_number + $sub_string_no;
$add_number = $add_number  ;
echo "<br>";
?>
</div>
<?php

}

};
$var_56($seats,$id,$trip_date,$route_id);
?>
</div>

<script src="add_seat.js"></script>
		   		<script src="choose_seat_transfer_passenger.js"></script>
				
				
		 
