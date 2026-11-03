   
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
</style>

<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

include 'connect.php';
require_once __DIR__ . '/includes/tessera_tenant.php';
require_once __DIR__ . '/includes/vehicle_seat_layout.php';
include 'page_info_reload.php';

if (!function_exists('sr_mysqli_count')) {
	function sr_mysqli_count($result): int {
		return ($result instanceof mysqli_result) ? (int) mysqli_num_rows($result) : 0;
	}
}

$id = @$_GET['id'];
$trip_date = @$_GET['trip_date'];
$trip_date = date("Y-m-d", strtotime($trip_date));  

$route_id = @$_GET['route_id'];
$start_location = @$_GET['start_location'];
$ending_location = @$_GET['ending_location'];
$route_name = trim((string) (@$_GET['route_name'] ?? ''));
$bus = @$_GET['bus'];
$sub_route_id = "";
$fare_price = @$_GET['fare_price'];
$start_location = @preg_replace('/_/',' ', $start_location);
$ending_location = @preg_replace('/_/',' ', $ending_location);
$route_name = @preg_replace('/_/',' ', $route_name);
$amount_left =0;

if ($route_id !== '') {
	$ridEsc = mysqli_real_escape_string($db, (string) $route_id);
	$routeMetaQ = mysqli_query(
		$db,
		"SELECT fullname, route_name, start_route, end_route FROM routes
		 WHERE route_id='$ridEsc' OR id='$ridEsc' LIMIT 1"
	);
	if ($routeMetaQ && ($routeMeta = mysqli_fetch_assoc($routeMetaQ))) {
		if ($route_name === '') {
			$route_name = (string) ($routeMeta['fullname'] ?: $routeMeta['route_name'] ?: trim(($routeMeta['start_route'] ?? '') . ' to ' . ($routeMeta['end_route'] ?? '')));
		}
		if ($start_location === '') {
			$start_location = (string) ($routeMeta['start_route'] ?? '');
		}
		if ($ending_location === '') {
			$ending_location = (string) ($routeMeta['end_route'] ?? '');
		}
	}
}
if ($route_name === '') {
	$route_name = trim($start_location . ' to ' . $ending_location);
}
$route_name_js = addslashes($route_name);

$select_sub_route=mysqli_query($db,"SELECT route_type,main_id  FROM  routes WHERE route_id = '$route_id'");
$select_num_sub_route=sr_mysqli_count($select_sub_route);
if($select_num_sub_route !=0){
   	WHILE($get_sub_route=mysqli_fetch_array($select_sub_route)){
    	    $route_type =$get_sub_route['route_type'];
    	    $main_id =$get_sub_route['main_id'];
		

	}}

if($route_type=="Main Route"){
$main_route_id = $route_id;
}else{
$main_route_id = $main_id;
}



$busEsc = mysqli_real_escape_string($db, (string) $bus);
$vehicleCols = tessera_vehicle_has_layout_column($db) ? 'seats,state,seat_layout_json' : 'seats,state';
$select1356=mysqli_query($db,"SELECT $vehicleCols FROM vehicle WHERE name='$busEsc' AND " . tessera_sql_tenant_eq() . " LIMIT 1");
$select_num1356=sr_mysqli_count($select1356);
$vehicleRow = [];
$layoutExtras = [];
if($select_num1356 !=0){
   	WHILE($get13356=mysqli_fetch_array($select1356)){
 $vehicleRow = $get13356;
 $seats= $get13356['seats'];
 $state= $get13356['state'];
 $layoutExtras = tessera_vehicle_layout_extras($get13356);
if($state=="vip"){



$select_fair=mysqli_query($db,"SELECT vip_price  FROM  routes WHERE route_id='$route_id'");
$select_num_fair=sr_mysqli_count($select_fair);
if($select_num_fair !=0){
   	WHILE($get_fair=mysqli_fetch_array($select_fair)){
 

 $vip_price= $get_fair['vip_price'];
$fare_price =$vip_price;

}}


}

}}

$countries ="";
$select_country=mysqli_query($db,"SELECT  country_name  FROM  apps_countries");
$select_num_country=sr_mysqli_count($select_country);
if($select_num_country !=0){
   	WHILE($get_country=mysqli_fetch_array($select_country)){
    	   
 $countries .= '<option value="'.$get_country['country_name'].'">'.$get_country['country_name'].'</option>';
}
}

?>
<div id="countries" style="display:none;"><?php  echo $countries;?></div>



                                                <div class="flex-grow-1 text-truncate">
                                                  
                                                    <div class="flex-grow-1  ">
                                                        <h5 class="font-size-14 mb-1 text-truncate"><div href="#" class="text-dark"> &nbsp <?php echo date("l jS \of F Y", strtotime($trip_date));?> &nbsp </div></h5>
                                                    
													<div class="row">
													<div class="col">
									<p class="text-muted text-truncate mb-0"> &nbsp<?php echo htmlspecialchars($route_name, ENT_QUOTES, 'UTF-8');?> &nbsp </p>
									<p class="text-muted text-truncate mb-0 small"> &nbsp<?php echo htmlspecialchars($bus, ENT_QUOTES, 'UTF-8');?> &nbsp </p>
													</div>
													<?php
													$route_fullname = $route_name;
											
$route_fullname_esc = mysqli_real_escape_string($db, $route_fullname);
$select1=mysqli_query($db,"SELECT id  FROM  transit_seat WHERE prev_route='$route_fullname_esc'  ");
$select_num1=sr_mysqli_count($select1);
if($select_num1 !=0){
   
													?>
													<div class="col" style="position:absolute; left:140px;top:20px;">
													 <button style="position:absolute;"type="button" class="btn header-item noti-icon tranfer_btn" id="page-header-notifications-dropdown"data-bs-toggle="modal"data-bs-target=".bs-example-modal-lg">
                                <i class="icon-sm" data-feather="bell"></i>
                                <span class="noti-dot bg-danger"></span>
                            </button>
													</div>
													<script>
													$(".tranfer_btn").click(function(){
	route_name = <?php echo "'$route_name_js'";?>;
	rout_day_id ="<?php echo $id; ?>";
	trip_date ="<?php echo $trip_date; ?>";
	route_id ="<?php echo $route_id; ?>";
	start_location ="<?php echo $start_location; ?>";
	ending_location ="<?php echo $ending_location; ?>";
	bus ="<?php echo $bus; ?>";
	fare_price ="<?php echo $fare_price; ?>";
	
	$.ajax({
    url:"dependence/transit_table.php",
    method:"POST",
    data:{route_name:route_name,
	      rout_day_id:rout_day_id,
	      trip_date:trip_date,
	      route_id:route_id,
	      start_location:start_location,
	      ending_location:ending_location,
	      fare_price:fare_price,
	      bus:bus
	},
	   beforeSend: function(){
		$("#transit_load").text("loading.....");

            
            },
    success:function(data){
$("#transit_load").html(data);
$("#loading_text").text(<?php echo "' Divert to $bus'"?>);
    }
   })
													});
													</script>
													<?php
}
													?>
													
													</div>
													
													</div>
                                                </div>
                                            
	      <hr>

<div id="start_location2"class="display_none"><?php echo $start_location;?></div>
<div id="ending_location2"class="display_none"><?php echo $ending_location;?></div>
<div id="route_day_id"class="display_none"><?php echo $id;?></div>
<div id="trip_date"class="display_none"><?php echo $trip_date;?></div>
<div id="route_id"class="display_none"><?php echo $route_id;?></div>
<div id="fare_price"class="display_none"><?php echo $fare_price;?></div>
<div id="total_price_ticket" class="display_none">0</div>
<audio id="pop" preload="none" aria-hidden="true"></audio>
<div id="sub_route_id"class="display_none"><?php echo $sub_route_id;?></div>
<div id="bus"class="display_none"><?php echo $bus;?></div>



<div id="pick">
 <div class="schedule-seat-chart-wrap">
<div class="schedule-seat-chart-front" aria-hidden="true">
<img src="images/arrow-right_grey.png" alt="">
</div>
<?php

$var_56 = function($seats,$id,$trip_date,$main_route_id) {
include 'connect.php';
$set_selection = [
	'admin' => "1",
	
];

 $select1=mysqli_query($db,"SELECT *  FROM  booking WHERE route_day_id='$id' and trip_date='$trip_date' and  route_id ='$main_route_id' and delete_book='' AND " . tessera_sql_tenant_eq() . "");
$select_num1=sr_mysqli_count($select1);
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
 
 if (!empty($vehicleRow)) {
	 $seat = tessera_vehicle_row_patterns($vehicleRow);
 } else {
	 $seat = tessera_default_seat_patterns((int) $seats);
 }
 
$arrLength = count($seat);
?>
<div id="seat_array">
<?php

 if (!empty($layoutExtras)) {
include 'connect.php';

 $select1=mysqli_query($db,"SELECT *  FROM  booking WHERE route_day_id='$id' and trip_date='$trip_date' and seat_no='xt1' and route_id ='$main_route_id' and delete_book='' AND " . tessera_sql_tenant_eq() . "");
$select_num1=sr_mysqli_count($select1);
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


  $select1=mysqli_query($db,"SELECT *  FROM  booking WHERE route_day_id='$id' and trip_date='$trip_date' and seat_no='xt2' and route_id ='$main_route_id' and delete_book='' AND " . tessera_sql_tenant_eq() . "");
$select_num1=sr_mysqli_count($select1);
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
		echo "<span id='test'class='first available_new_seat seat_col seat_name'class='position-absolute  translate-middle  rounded-pill success'><span  id='seat_no'class='position-absolute  translate-middle  rounded-pill '>".$letter."".$let_no."</span></span>";

}





}
}

$add_number=$add_number + $sub_string_no;
$add_number = $add_number  ;
?>
</div>
<?php

}

};
$var_56($seats,$id,$trip_date,$main_route_id);
?>
</div></div>

<script src="add_seat.js"></script>
		   		<script src="choose_seat.js?<?php  echo modified_time("choose_seat.js");?>"></script>
				
				
		 
<script src="assets/js/app.js"></script>
				<script src="js/transit.js"></script>
   