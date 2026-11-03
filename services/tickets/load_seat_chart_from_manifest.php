
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
include 'connect.php';
require_once __DIR__ . '/includes/tessera_tenant.php';
$id = @$_GET['id'];
$trip_date = @$_GET['trip_date'];
$trip_date = date("Y-m-d", strtotime($trip_date));  
$route_id = @$_GET['route_id'];
$start_location = @$_GET['start_location'];
$ending_location = @$_GET['ending_location'];
$manifest_seat_no = @$_GET['manifest_seat_no'];

$sub_route_id = @$_GET['sub_route_id'];
$fare_price = @$_GET['fare_price'];
$start_location = @preg_replace('/_/',' ', $start_location);
$ending_location = @preg_replace('/_/',' ', $ending_location);
$amount_left =0;


	$select14=mysqli_query($db,"SELECT *  FROM  routes WHERE start_route='$start_location' AND end_route='$ending_location' ");
$select_num14=mysqli_num_rows($select14);
if($select_num14 !=0){
   	WHILE($get134=mysqli_fetch_array($select14)){
    	    $fare_price =$get134['fare_price'];
			$start_route =$get134['start_route'];
			$end_route =$get134['end_route'];
}
}

$select=mysqli_query($db,"SELECT *  FROM  route_day where   id='$id' ");
$select_num=mysqli_num_rows($select);
if($select_num !=0){
   	WHILE($get=mysqli_fetch_array($select)){
    	   
$bus =$get['bus'];
}}




$select1356=mysqli_query($db,"SELECT *  FROM  vehicle WHERE name='$bus'");
$select_num1356=mysqli_num_rows($select1356);
if($select_num1356 !=0){
   	WHILE($get13356=mysqli_fetch_array($select1356)){
 

 $seats= $get13356['seats'];



}}

$select1356=mysqli_query($db,"SELECT seats,state   FROM  vehicle WHERE name='$bus'");
$select_num1356=mysqli_num_rows($select1356);
if($select_num1356 !=0){
   	WHILE($get13356=mysqli_fetch_array($select1356)){
 

 $seats= $get13356['seats'];
 $state= $get13356['state'];
if($state=="vip"){



$select_fair=mysqli_query($db,"SELECT vip_price  FROM  routes WHERE start_route='$start_location' and end_route='$ending_location'");
$select_num_fair=mysqli_num_rows($select_fair);
if($select_num_fair !=0){
   	WHILE($get_fair=mysqli_fetch_array($select_fair)){
 

 $vip_price= $get_fair['vip_price'];
$fare_price =$vip_price;

}}else{
	$select_fair=mysqli_query($db,"SELECT vip_price  FROM  sub_route WHERE start_route='$start_location' and end_route='$ending_location'");
$select_num_fair=mysqli_num_rows($select_fair);
if($select_num_fair !=0){
   	WHILE($get_fair=mysqli_fetch_array($select_fair)){
 

 $vip_price= $get_fair['vip_price'];
$fare_price =$vip_price;

}}
}


}

}}

$countries ="";
$select_country=mysqli_query($db,"SELECT  country_name  FROM  apps_countries");
$select_num_country=mysqli_num_rows($select_country);
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
                                                        <p class="text-muted text-truncate mb-0"> &nbsp<?php echo $bus;?> &nbsp </p>
                                                    </div>
                                                </div>
                                            <hr>
<div id="start_location32"class="display_none"><?php echo $start_location;?></div>
<div id="ending_location32"class="display_none"><?php echo $ending_location;?></div>
<div id="route_day_id"class="display_none"><?php echo $id;?></div>
<div id="trip_date"class="display_none"><?php echo $trip_date;?></div>
<div id="route_id"class="display_none"><?php echo $route_id;?></div>
<div id="fare_price"class="display_none"><?php echo $fare_price;?></div>
<div id="sub_route_id"class="display_none"><?php echo $sub_route_id;?></div>
<div id="manifest_seat_no"class="display_none"><?php echo $manifest_seat_no;?></div>
<div id="bus"class="display_none"><?php echo $bus;?></div>


<div id="pick">


 <div class="" >
<img src="images/arrow-right_grey.png">
<?php

$var_56 = function($seats,$id,$trip_date,$route_id) {
include 'connect.php';
$set_selection = [
	'admin' => "1",
	
];

 $select1=mysqli_query($db,"SELECT *  FROM  booking WHERE route_day_id='$id' and trip_date='$trip_date' and  route_id ='$route_id' and delete_book='' AND " . tessera_sql_tenant_eq() . "");
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
 else if ($seats==41 ){
$seat = array("ii-ii", "ii-ii", "ii-ii", "ii-ii", "ii-ii", "ii-ii", "ii-ii", "ii-ii", "ii-ii",  "iiiii");
 
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

 $select1=mysqli_query($db,"SELECT *  FROM  booking WHERE route_day_id='$id' and trip_date='$trip_date' and seat_no='xt1' and route_id ='$route_id' and delete_book='' AND " . tessera_sql_tenant_eq() . "");
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


  $select1=mysqli_query($db,"SELECT *  FROM  booking WHERE route_day_id='$id' and trip_date='$trip_date' and seat_no='xt2' and route_id ='$route_id'  and delete_book='' AND " . tessera_sql_tenant_eq() . "");
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

 $select1=mysqli_query($db,"SELECT *  FROM  booking WHERE route_day_id='$id' and trip_date='$trip_date' and seat_no='xt1' and route_id ='$route_id' and delete_book='' AND " . tessera_sql_tenant_eq() . "");
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


  $select1=mysqli_query($db,"SELECT *  FROM  booking WHERE route_day_id='$id' and trip_date='$trip_date' and seat_no='xt2' and route_id ='$route_id' and delete_book='' AND " . tessera_sql_tenant_eq() . "");
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
					echo "<span id='test'class='first available_new_seat_complete seat_col 'class='position-absolute  translate-middle  rounded-pill success'><span  id='seat_no'class='class='position-absolute  translate-middle  rounded-pill success''>xt2</span></span>";

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
		echo "<span id='test'class='first available_new_seat seat_col seat_name'class='position-absolute  translate-middle  rounded-pill success'><span  id='seat_no'class='position-absolute  translate-middle  rounded-pill '>".$letter."".$let_no."</span></span>";

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
</div>

<script src="add_seat.js"></script>
		   		<script src="choose_seat_manifest.js"></script>
				<?php
				if(isset($_GET['manifest_seat_no'])){
					?>
					<script src="choose_seat_manifest_selected.js"></script>
					<?php
					
				}
				?>
				
		   		
				
				
		 
<script src="assets/js/app.js"></script>