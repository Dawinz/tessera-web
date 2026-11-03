
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
width:30px;
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
width:30px;
background-image: url("images/armchair_booked.png");
background-size: contain;
background-repeat: no-repeat;
height:45px;
cursor: no-drop;
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
cursor: no-drop;
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

}.available_new_seat_complete_blinker {
color:black;
padding:15px;
margin-left:8px;
width:30px;
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
width:30px;
background-image: url("images/reserved.png");
background-size: contain;
background-repeat: no-repeat;
height:45px;
cursor: no-drop;
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
height:45px;
cursor: no-drop;


}
.center_seat {
color:black;
padding:15px;
margin-left:8px;
width:30px;
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
#seat_no{
padding:10px;	
position:absolute
top:20px;
}
.disabledbutton {
    pointer-events: none;
    opacity: 0.4;
}
@keyframes blinker {
  50% {
    opacity: 0;
  }
}

</style>

<?php
require_once __DIR__ . '/includes/tessera_tenant.php';

include 'page_info_reload.php';

$id = @$_GET['id'];
$trip_date = @$_POST['starting_date_select'];
$trip_date = date("Y-m-d", strtotime($trip_date));  
$id_route = $_POST['trip_start_modal'];
$start_location = @$_GET['start_location'];
$ending_location = @$_GET['ending_location'];
$bus = @$_POST['select_bus_modal'];
$seat_no_b = @$_GET['seat_no'];
$ticket_no_original = @$_POST['ticket_no_original'];
$sub_route_id = @$_GET['sub_route_id'];
$fare_price = @$_GET['fare_price'];


include 'connect.php';
$select1356=mysqli_query($db,"SELECT seats  FROM  vehicle WHERE name='$bus'");
$select_num1356=mysqli_num_rows($select1356);
if($select_num1356 !=0){
   	WHILE($get13356=mysqli_fetch_array($select1356)){
 

 $seats= $get13356['seats'];


}}

$select_route=mysqli_query($db,"SELECT start_route,end_route,route_id,main_id,route_type  FROM  routes WHERE id = '$id_route'");
$select_num_route=mysqli_num_rows($select_route);
if($select_num_route !=0){
   	WHILE($get_route=mysqli_fetch_array($select_route)){
    	    $start_location =$get_route['start_route'];
    	    $ending_location =$get_route['end_route'];
    	    $route_id =$get_route['route_id'];
    	    $main_id =$get_route['main_id'];
    	    $route_type =$get_route['route_type'];
	
}}

if($route_type !="Main Route"){
	$route_id = $main_id;
}


?>
<div id="start_location_shift_t"class="display_none"><?php echo $start_location;?></div>
<div id="ending_location_shift_t"class="display_none"><?php echo $ending_location;?></div>
<div id="trip_date_shift_t"class="display_none"><?php echo $trip_date;?></div>
<div id="bus_shift_t"class="display_none"><?php echo $bus;?></div>
<div id="ticket_no_original"class="display_none"><?php echo $ticket_no_original;?></div>
<div id="new_id"class="display_none
"><?php echo $id_route;?></div>


<?php
$trip_date_seach = date('l', strtotime($trip_date));

	$select=mysqli_query($db,"SELECT *  FROM  route_day where   day='$trip_date_seach'  and route_id ='$route_id' and bus='$bus'  and status='active'  ");
$select_num=mysqli_num_rows($select);
if($select_num ==1){
   	WHILE($get=mysqli_fetch_array($select)){
 
			$route_day_id =$get['id']; 
			$bus =$get['bus']; 


}	
	

?>

<div id="seat_shift_t"class="display_none"><?php echo $seat_no_b;?></div>
<div id="route_id_shift_t"class="display_none"><?php echo $route_id;?></div>
<div id="route_day_id_shift_t"class="display_none"><?php echo $route_day_id;?></div>


<div id="pick">

<img src="images/arrow-right_grey.png">
<?php
	
	

$var_56 = function($route_day_id,$trip_date,$route_id,$seats) {
	include 'connect.php';
$set_selection = [
	'admin' => "1",
	
];

 $select1=mysqli_query($db,"SELECT *  FROM  booking WHERE " . tessera_sql_tenant_eq() . " AND (route_day_id='$route_day_id' and trip_date='$trip_date' and  route_id ='$route_id' and delete_book='')");
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
	
	
$seat_info =  "<span id='test'class='first available_new_seat_complete seat_col 'class='position-absolute  translate-middle  rounded-pill success' data-bs-toggle='tooltip' data-bs-placement='right' title=' ".$id_lock."   '><span  id='seat_no'class='position-absolute  translate-middle  rounded-pill success'style='padding-left:12px;'>".$seat_no_new."</span></span>";

$set_selection["$seat_info"] = $seat_no_new;


}else if($status =="booked"){
		$seat_info ="<span id='test'class='first available_new_seat_booked_r seat_col 'class='position-absolute  translate-middle  rounded-pill success'><span  id='seat_no'class='position-absolute  translate-middle  rounded-pill success'style='padding-left:12px;'>".$seat_no_new."</span></span>";
$set_selection["$seat_info"] = $seat_no_new;
}else if ($status =="locked"){
		$seat_info = "<span id='test'class='first available_new_seat_locked_r seat_col 'class='position-absolute  translate-middle  rounded-pill success'data-bs-toggle='tooltip' data-bs-placement='right' title='Locked by : ".$locker_fullname."  Reason: ".$locked_reasons." ' ><span  id='seat_no'class='position-absolute  translate-middle  rounded-pill success'style='padding-left:12px;'>".$seat_no_new."</span></span>";
	$set_selection["$seat_info"] = $seat_no_new;
}else if ($status =="half_complete"){
	
	if( $amount_left==0){
				$seat_info = "<span id='test'class='first available_new_seat_half seat_col seat_name'class='position-absolute  translate-middle  rounded-pill success' data-bs-toggle='tooltip' data-bs-placement='right' title=' ".$id_lock."   ' ><span  id='seat_no'class='position-absolute  translate-middle  rounded-pill success'style='padding-left:12px;'>".$seat_no_new."</span></span>";
	$set_selection["$seat_info"] = $seat_no_new;

	}else{
					$seat_info =  "<span id='test'class='first available_new_seat_half seat_col seat_name'class='position-absolute  translate-middle  rounded-pill success' data-bs-toggle='tooltip' data-bs-placement='right' title=' ".$id_lock."   ' ><span  id='seat_no'class='position-absolute  translate-middle  rounded-pill success'style='padding-left:12px;'>".$seat_no_new."</span><img class='minus'src='images/minus.png'></span>";
	$set_selection["$seat_info"] = $seat_no_new;

	}
	
	
}




}}

 $alphabet = range('A', 'Z');


 
 $add_number = 0;
 if( $seats==49 ){
	 $seat = array("ii-ii", "ii-ii", "ii-ii", "ii-ii", "ii-ii", "ii-ii", "ii-ii", "ii-ii", "ii-ii", "ii-ii", "ii-ii","ii-ii", "iiiii");

 }else if ( $seats==47 ){
		 $seat = array("ii-ii", "ii-ii", "ii-ii", "ii-ii", "ii-ii", "ii-ii", "ii-ii", "ii-ii", "ii-ii", "ii-ii",  "iiiii");
 
 }else if ( $seats==57 ){
$seat = array("ii-ii", "ii-ii", "ii-ii", "ii-ii", "ii-ii", "ii-ii", "ii-ii", "ii-ii", "ii-ii", "ii-ii", "ii-ii","ii-ii","ii-ii", "iiiii"); 
 }else if ($seats==59 ){
$seat = array("ii-ii", "ii-ii", "ii-ii", "ii-ii", "ii-ii", "ii-ii", "ii-ii", "ii-ii", "ii-ii", "ii-ii", "ii-ii","ii-ii","ii-ii", "iiiii");
 
 }else if ($seats==45 ){
$seat = array("ii-ii", "ii-ii", "ii-ii", "ii-ii", "ii-ii", "ii-ii", "ii-ii", "ii-ii", "ii-ii", "ii-ii",  "iiiii");
 
 }
 else if ($seats==41 ){
$seat = array("ii-ii", "ii-ii", "ii-ii", "ii-ii", "ii-ii", "ii-ii", "ii-ii", "ii-ii", "ii-ii",  "iiiii");
 
 } else if ($seats==37 ){
$seat = array("i-ii", "i-ii", "i-ii", "i-ii", "i-ii", "i-ii", "i-ii", "i-ii", "i-ii","i-ii",  "iiii");
 
 }else if($seats==53){
	$seat = array("ii-ii", "ii-ii", "ii-ii", "ii-ii", "ii-ii", "ii-ii", "ii-ii", "ii-ii", "ii-ii", "ii-ii", "ii-ii","ii-ii","iiiii");

 }
 else if($seats==42){
	$seat = array("i--ii", "i--ii", "i--ii", "ii-ii", "ii-ii", "ii-ii", "ii-ii", "ii-ii", "ii-ii", "ii-ii","iiiii");

 }
$arrLength = count($seat);
?>
<div id="seat_array">
<?php

 if( $seats==49 ){
	

 }else if ( $seats==47 ){
		
 
 }else if ( $seats==57 ){

 }else if ( $seats==47 ){
		
 
 }else if ( $seats==37 ){

 }else if ($seats==59 ){
include 'connect.php';

 $select1=mysqli_query($db,"SELECT *  FROM  booking WHERE " . tessera_sql_tenant_eq() . " AND (route_day_id='$route_day_id' and trip_date='$trip_date' and seat_no='xt1' and route_id ='$route_id' and delete_book='')");
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


  $select1=mysqli_query($db,"SELECT *  FROM  booking WHERE " . tessera_sql_tenant_eq() . " AND (route_day_id='$route_day_id' and trip_date='$trip_date' and seat_no='xt2' and route_id ='$route_id' and delete_book='')");
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
			echo "<span id='test'class='first available_new_seat seat_col seat_name disabledbutton'style=''><span  id='seat_no'style='position:relative;bottom:4px;'>xt2</span></span>";

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

if( in_array("$seat_no" ,$set_selection ))
{
	
foreach ($set_selection as $subject => $marks){ 
if($marks==$seat_no){
	    echo "$subject"; 

}else{
	
}
}
}else{
		echo "<span id='test'class='first available_new_seat seat_col seat_name'class='position-absolute  translate-middle  rounded-pill success'data-bs-toggle='tooltip' data-bs-placement='right' title='Transfer here'><span  id='seat_no'class='position-absolute  translate-middle  rounded-pill 'style='padding-left:12px;'>".$letter."".$let_no."</span></span>";

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
$var_56($route_day_id,$trip_date,$route_id,$seats);
}else{
?>
            		<div class="card center" style="">
											
                                            <div class="" style="text-align:center; padding:15px;"class="center">
										
                                                <div class="mb-4">
<img src="images/no-task3.png">                                                </div>
                                                
                                                <h5 class="font-size-15 text-uppercase">
                                                    Seat Chart not Present</h5>
                                                <p class="text-muted mb-0">Seat chart panel<br>Select route and the appropriate route seat chart will appear.</p>
                                            </div>
                                    
                                
										
											</div>
<?php	
}
?>
</div>

<script src="add_seat.js"></script>
<script src="choose_seat_transfer_sel.js<?php echo "?".modified_time("choose_seat_transfer_sel.js").""?>"></script>
				
				
				
		 
<script src="assets/js/app.js"></script>
