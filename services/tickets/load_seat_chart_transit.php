
 <style>
#seat_array {
  spanst-style-type: none;
  margin-top: 8;
  padding: 0;
  overflow: hidden;
  
}

.seat_col {
  float: left;
 
  
}

.available_new_seat {
color:white;
padding:15px;
margin-left:8px;
width:30px;
background-image: url("images/armchair_white.png");
background-size: contain;
background-repeat: no-repeat;
height:45px;
cursor: pointer;
font-size:13px;
}
.available_new_seat_booked {
color:black;
padding:15px;
margin-left:8px;
width:30px;
background-image: url("images/armchair_booked.png");
background-size: contain;
background-repeat: no-repeat;
height:45px;
cursor: pointer;
}
.available_new_seat_half_booked {
color:black;
padding:15px;
margin-left:8px;
width:30px;
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
width:30px;
background-image: url("images/complete15.png");
background-size: contain;
background-repeat: no-repeat;
height:45px;
cursor: no-drop;

}
.available_new_seat_booked_r {
color:black;
padding:15px;
margin-left:8px;
width:50px;
background-image: url("images/reserved.png");
background-size: contain;
background-repeat: no-repeat;
width:30px;
cursor: no-drop;
height:45px;
}.available_new_seat_locked_r {
color:black;
padding:15px;
margin-left:8px;
width:30px;
background-image: url("images/lock.png");
background-size: contain;
background-repeat: no-repeat;
height:45px;
cursor: no-drop;

}.available_new_seat_half {
color:black;
padding:15px;
margin-left:8px;
width:30px;
background-image: url("images/green.png");
background-size: contain;
background-repeat: no-repeat;
cursor: pointer;
height:45px;

}
.center_seat {
color:black;
padding:15px;
margin-left:8px;
width:30px;
background-size: contain;
background-repeat: no-repeat;
height:45px;
}
.bus_name{
	color:white;
	font-weight:bold;
	font-size:17px;
	text-align  :center;
}
.minus{
	position:relative;
	bottom:40px;
	left:15px;
}
#seat_no{
padding:10px;	
position:absolute
top:20px;
}
@keyframes blinker {
  50% {
    opacity: 0;
  }
}
.blinker{
	
	animation: blinker 1s linear infinite;
}
</style>


<?php
require_once __DIR__ . '/includes/tessera_tenant.php';


$id = $_POST['rout_day_id'];
$trip_date = $_POST['trip_date'];
$route_id = $_POST['route_id'];
$start_location = $_POST['start_location'];
$ending_location = $_POST['ending_location'];
$bus = $_POST['bus'];
$fare_price = $_POST['fare_price'];
$select_all_seats = $_POST['select_all_seats'];
$amount_left =0;



include 'connect.php';
$select1356=mysqli_query($db,"SELECT *  FROM  vehicle WHERE name='$bus'");
$select_num1356=mysqli_num_rows($select1356);
if($select_num1356 !=0){
   	WHILE($get13356=mysqli_fetch_array($select1356)){
 

 $seats= $get13356['seats'];



}}
?>

                                 
                                            
	     

<div id="start_location_shift_t_m3"class="display_none"><?php echo $start_location;?></div>
<div id="ending_location_shift_t_m3"class="display_none"><?php echo $ending_location;?></div>
<div id="route_day_id_shift_t_m3"class="display_none"><?php echo $id;?></div>
<div id="trip_date_shift_t_m3"class="display_none"><?php echo $trip_date;?></div>
<div id="route_id_shift_t_m3"class="display_none"><?php echo $route_id;?></div>
<div id="fare_price_shift_t_m3"class="display_none"><?php echo $fare_price;?></div>
<div id="bus_shift_t_m3"class="display_none"><?php echo $bus;?></div>


<div class="alert alert-danger alert-top-border alert-dismissible fade show" role="alert">
                                                <div id="finish_c" class="text-success"></div>
                                             <span class="holder" ><span class="seat_name_class_manifset">
											 <div style=" overflow: hidden;" id="selected_list_ticket"><div id="select_all_seats_shift_t_m3"class="display_non"><?php echo $select_all_seats;?></div></div>
											 </span></span> 
                                                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                                            </div>
	   
<div id="pick">
 <div class="" >
 
 
 
<img src="images/arrow-right_grey.png">
<?php

$var_56 = function($seats,$id,$trip_date,$route_id) {
include 'connect.php';
$set_selection = [
	'admin' => "1",
	
];

 $select1=mysqli_query($db,"SELECT *  FROM  booking WHERE " . tessera_sql_tenant_eq() . " AND (route_day_id='$id' and trip_date='$trip_date' and  route_id ='$route_id' and delete_book='')");
$select_num1=mysqli_num_rows($select1);
if($select_num1 !=0){
   	WHILE($get13=mysqli_fetch_array($select1)){
 $status =$get13['status']; 
 $seat_no_new =$get13['seat_no']; 
 $id_lock =$get13['id']; 
 $locker_fullname =$get13['locker_fullname']; 
 $locked_reasons =$get13['locked_reasons']; 
 $amount_left =$get13['amount_left']; 
$locked_reasons = base64_decode($locked_reasons);
if($status =="complete"){
	
	
$seat_info =  "<span id='test'class='first available_new_seat_complete seat_col 'class='position-absolute  translate-middle  rounded-pill success' data-bs-toggle='tooltip' data-bs-placement='right' title=' ".$id_lock."   '><span  id='seat_no'class='position-absolute  translate-middle  rounded-pill success'>".$seat_no_new."</span></span>";

$set_selection["$seat_info"] = $seat_no_new;


}else if($status =="booked"){
		$seat_info ="<span id='test'class='first available_new_seat_booked_r seat_col 'class='position-absolute  translate-middle  rounded-pill success'><span  id='seat_no'class='position-absolute  translate-middle  rounded-pill success'>".$seat_no_new."</span></span>";
$set_selection["$seat_info"] = $seat_no_new;
}else if ($status =="locked"){
		$seat_info = "<span id='test'class='first available_new_seat_locked_r seat_col 'class='position-absolute  translate-middle  rounded-pill success'data-bs-toggle='tooltip' data-bs-placement='right' title='Locked by : ".$locker_fullname."  Reason: ".$locked_reasons." ' ><span  id='seat_no'class='position-absolute  translate-middle  rounded-pill success'>".$seat_no_new."</span></span>";
	$set_selection["$seat_info"] = $seat_no_new;
}else if ($status =="half_complete"){
	
	if( $amount_left==0){
				$seat_info = "<span id='test'class='first available_new_seat_half seat_col seat_name'class='position-absolute  translate-middle  rounded-pill success' data-bs-toggle='tooltip' data-bs-placement='right' title=' ".$id_lock."   ' ><span  id='seat_no'class='position-absolute  translate-middle  rounded-pill success'>".$seat_no_new."</span></span>";
	$set_selection["$seat_info"] = $seat_no_new;

	}else{
					$seat_info =  "<span id='test'class='first available_new_seat_half seat_col seat_name'class='position-absolute  translate-middle  rounded-pill success' data-bs-toggle='tooltip' data-bs-placement='right' title=' ".$id_lock."   ' ><span  id='seat_no'class='position-absolute  translate-middle  rounded-pill success'>".$seat_no_new."</span><img class='minus'src='images/minus.png'></span>";
	$set_selection["$seat_info"] = $seat_no_new;

	}
	
	
}




}}


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
 
 }else if ($seats==45 ){
$seat = array("ii-ii", "ii-ii", "ii-ii", "ii-ii", "ii-ii", "ii-ii", "ii-ii", "ii-ii", "ii-ii", "ii-ii",  "iiiii");
 
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
		echo "<span id='test'class='first available_new_seat_complete seat_col 'class='position-absolute  translate-middle  rounded-pill success'><span  id='seat_no'class='position-absolute  translate-middle  rounded-pill success'>xt1</span></span>";

}else if($status =="booked"){
			echo "<span id='test'class='first available_new_seat_booked_r seat_col '><span class='position-absolute  translate-middle  rounded-pill success' id='seat_no'>xt1</span></span>";

}else if ($status =="locked"){
			echo "<span id='test'class='first available_new_seat_locked_r seat_col '><span class='position-absolute  translate-middle  rounded-pill success'  id='seat_no'>xt1</span></span>";
	
}else if ($status =="half_complete"){
			echo "<span id='test'class='first available_new_seat_half seat_col seat_name'><span class='position-absolute  translate-middle  rounded-pill success' id='seat_no'>xt1</span></span>";
	
}

}else{
			echo "<span id='test'class='first available_new_seat seat_col seat_name'><span class='position-absolute  translate-middle  rounded-pill success' id='seat_no'class='position-absolute  translate-middle  rounded-pill success'>xt1</span></span>";

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
		echo "<span id='test'class='first available_new_seat_complete seat_col 'class='position-absolute  translate-middle  rounded-pill success'><span  id='seat_no'class='position-absolute  translate-middle  rounded-pill success'>xt2</span></span>";

}else if($status =="booked"){
			echo "<span id='test'class='first available_new_seat_booked_r seat_col 'class='position-absolute  translate-middle  rounded-pill success'><span  id='seat_no'class='position-absolute  translate-middle  rounded-pill success'>xt2</span></span>";

}else if ($status =="locked"){
			echo "<span id='test'class='first available_new_seat_locked_r seat_col 'class='position-absolute  translate-middle  rounded-pill success'><span  id='seat_no'class='position-absolute  translate-middle  rounded-pill success'>xt2</span></span>";
	
}else if ($status =="half_complete"){
			echo "<span id='test'class='first available_new_seat_half seat_col seat_name'class='position-absolute  translate-middle  rounded-pill success'><span  id='seat_no'class='position-absolute  translate-middle  rounded-pill success'>xt2</span></span>";
	
}

}else{
			echo "<span id='test'class='first available_new_seat seat_col seat_name'class='position-absolute  translate-middle  rounded-pill success'><span  id='seat_no'class='position-absolute  translate-middle  rounded-pill success'>xt2</span></span>";

} 

 
 }
else if ($seats==45 ){
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
 $amount_left =@$get13['amount_left']; 


}
if($status =="complete"){
	
	if( $amount_left==0){
			echo "<span id='test'class='first available_new_seat_complete seat_col 'class='position-absolute  translate-middle  rounded-pill success'><span  id='seat_no'class='position-absolute  translate-middle  rounded-pill success'>xt1</span></span>";
	
	}else{
				echo "<span id='test'class='first available_new_seat_complete seat_col 'class='position-absolute  translate-middle  rounded-pill success'><span  id='seat_no'class='position-absolute  translate-middle  rounded-pill success'>xt1</span><img class='minus'src='images/minus.png'></span>";

	}

}else if($status =="booked"){
			echo "<span id='test'class='first available_new_seat_booked_r seat_col 'class='position-absolute  translate-middle  rounded-pill success'><span  id='seat_no'class='position-absolute  translate-middle  rounded-pill success'>xt1</span></span>";

}else if ($status =="locked"){
			echo "<span id='test'class='first available_new_seat_locked_r seat_col 'class='position-absolute  translate-middle  rounded-pill success'><span  id='seat_no'class='position-absolute  translate-middle  rounded-pill success'>xt1</span></span>";
	
}else if ($status =="half_complete"){
			echo "<span id='test'class='first available_new_seat_half seat_col seat_name'class='position-absolute  translate-middle  rounded-pill success'><span  id='seat_no'class='position-absolute  translate-middle  rounded-pill success'>xt1</span></span>";
	
}

}else{
				echo "<span id='test'class='first available_new_seat seat_col seat_name'class='position-absolute  translate-middle  rounded-pill success'><span  id='seat_no'class='position-absolute  translate-middle  rounded-pill success'>xt1</span></span>";


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
 $amount_left =$get13['amount_left']; 


}
if($status =="complete"){
		if( $amount_left==0){
					echo "<span id='test'class='first available_new_seat_complete seat_col 'class='position-absolute  translate-middle  rounded-pill success' ><span  id='seat_no'class='class='position-absolute  translate-middle  rounded-pill success''>xt2</span></span>";

		}else{
					echo "<span id='test'class='first available_new_seat_complete seat_col 'class='position-absolute  translate-middle  rounded-pill success'><span  id='seat_no'class='position-absolute  translate-middle  rounded-pill success'>xt2</span><img class='minus'src='images/minus.png'></span>";

		}

}else if($status =="booked"){
			echo "<span id='test'class='first available_new_seat_booked_r seat_col 'class='position-absolute  translate-middle  rounded-pill success'><span  id='seat_no'class='position-absolute  translate-middle  rounded-pill success'>xt2</span></span>";

}else if ($status =="locked"){
			echo "<span id='test'class='first available_new_seat_locked_r seat_col 'class='position-absolute  translate-middle  rounded-pill success'><span  id='seat_no'class='position-absolute  translate-middle  rounded-pill success'>xt2</span></span>";
	
}else if ($status =="half_complete"){
	if( $amount_left==0){
echo "<span id='test'class='first available_new_seat_half seat_col seat_name'class='position-absolute  translate-middle  rounded-pill success'><span  id='seat_no'class='position-absolute  translate-middle  rounded-pill success'>xt2</span></span>";

	}else{
echo "<span id='test'class='first available_new_seat_half seat_col seat_name'class='position-absolute  translate-middle  rounded-pill success'><span  id='seat_no' class='position-absolute  translate-middle  rounded-pill success'>xt2</span><img class='minus'src='images/minus.png'></span>";
	
	}
	
}

}else{
			echo "<span id='test'class='first available_new_seat seat_col seat_name'class='position-absolute  translate-middle  rounded-pill success'><span  id='seat_no'class='class='position-absolute  translate-middle  rounded-pill success''>xt2</span></span>";

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

if( in_array("$seat_no" ,$set_selection ))
{
	
foreach ($set_selection as $subject => $marks){ 
if($marks==$seat_no){
	    echo "$subject"; 

}else{
	
}
}
}else{
		echo "<span  style=''id='test'class='this_l".$letter."".$let_no." first available_new_seat seat_col seat_name'class='position-absolute  translate-middle  rounded-pill success'><span  id='seat_no'class='position-absolute  translate-middle  rounded-pill '>".$letter."".$let_no."</span></span>";

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
</div></div>

<script src="add_seat.js"></script>
<script src="choose_seat_transit.js"></script>
				
				
		 
<script src="assets/js/app.js"></script>