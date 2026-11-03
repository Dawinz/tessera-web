
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

.available_new_seat_locked {
color:#C5C5C5;
padding:15px;
margin-left:8px;
width:40px;
background-image: url("images/lock_grey.png");
background-size: contain;
background-repeat: no-repeat;
height:45px;
cursor: pointer;
font-size:12px;
}
.available_new_seat_locked_change {
color:black;
padding:15px;
margin-left:8px;
width:40px;
background-image: url("images/lock.png");
background-size: contain;
background-repeat: no-repeat;
height:45px;
cursor: pointer;
}
.center_seat {
color:black;
padding:15px;
margin-left:8px;
width:40px;
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
.available_new_seat_half_r_booked {
color:black;
padding:15px;
margin-left:8px;
width:40px;
background-image: url("images/green.png");
background-size: contain;
background-repeat: no-repeat;
height:45px;
cursor: pointer;


}
.available_new_seat_complete_r_booked_r_booked {
color:white;
padding:15px;
margin-left:8px;
width:40px;
background-image: url("images/complete15.png");
background-size: contain;
background-repeat: no-repeat;
height:45px;
cursor: no-drop;

}
.available_new_seat_booked_r_new {
color:black;
padding:15px;
margin-left:8px;
width:40px;
background-image: url("images/reserved.png");
background-size: contain;
background-repeat: no-repeat;
height:45px;
cursor: no-drop;
}
.available_new_seat_locked_r_booked {
color:black;
padding:15px;
margin-left:8px;
width:40px;
background-image: url("images/lock.png");
background-size: contain;
background-repeat: no-repeat;
height:45px;
cursor: no-drop;

}
</style>

<?php
require_once __DIR__ . '/includes/tessera_tenant.php';

$id = @$_GET['id'];
$trip_date = @$_GET['trip_date'];
$route_id = @$_GET['route_id'];
$start_location = @$_GET['start_location'];
$ending_location = @$_GET['ending_location'];
$bus = @$_GET['bus'];
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
<div id="start_location5"class="display_none"><?php echo $start_location;?></div>
<div id="ending_location5"class="display_none"><?php echo $ending_location;?></div>
<div id="route_day_id5"class="display_none"><?php echo $id;?></div>
<div id="trip_date5"class="display_none"><?php echo $trip_date;?></div>
<div id="route_id25"class="display_none"><?php echo $route_id;?></div>
<div id="fare_price5"class="display_none"><?php echo $fare_price;?></div>
<div id="bus5"class="display_none"><?php echo $bus;?></div>




<div id="pick">


<img src="images/arrow-right_grey.png">
<?php


$var_56 = function($seats,$id,$trip_date,$route_id) {
	
	
	include 'connect.php';
$set_selection = [
	'admin' => "1"
	
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
	
	
$seat_info =  "<span id='test'class='first available_new_seat_complete_r_booked_r_booked seat_col 'class='position-absolute  translate-middle  rounded-pill success' data-bs-toggle='tooltip' data-bs-placement='right' title=' ".$id_lock."   '><span  id='seat_no'class='position-absolute  translate-middle  rounded-pill success'>".$seat_no_new."</span></span>";

$set_selection["$seat_info"] = $seat_no_new;


}else if($status =="booked"){
		$seat_info ="<span id='test'class='first available_new_seat_booked_r_new seat_col 'class='position-absolute  translate-middle  rounded-pill success'><span  id='seat_no'class='position-absolute  translate-middle  rounded-pill success'>".$seat_no_new."</span></span>";
$set_selection["$seat_info"] = $seat_no_new;
}else if ($status =="locked"){
		$seat_info = "<span id='test'class='first available_new_seat_locked_r_booked seat_col 'class='position-absolute  translate-middle  rounded-pill success'data-bs-toggle='tooltip' data-bs-placement='right' title='Locked by : ".$locker_fullname."  Reason: ".$locked_reasons." ' ><span  id='seat_no'class='position-absolute  translate-middle  rounded-pill success'>".$seat_no_new."</span></span>";
	$set_selection["$seat_info"] = $seat_no_new;
}else if ($status =="half_complete"){
	
	if( $amount_left==0){
				$seat_info = "<span id='test'class='first available_new_seat_half_r_booked seat_col seat_name'class='position-absolute  translate-middle  rounded-pill success' data-bs-toggle='tooltip' data-bs-placement='right' title=' ".$id_lock."   ' ><span  id='seat_no'class='position-absolute  translate-middle  rounded-pill success'>".$seat_no_new."</span></span>";
	$set_selection["$seat_info"] = $seat_no_new;

	}else{
					$seat_info =  "<span id='test'class='first available_new_seat_half_r_booked seat_col seat_name'class='position-absolute  translate-middle  rounded-pill success' data-bs-toggle='tooltip' data-bs-placement='right' title=' ".$id_lock."   ' ><span  id='seat_no'class='position-absolute  translate-middle  rounded-pill success'style=''>".$seat_no_new."</span><img class='minus'src='images/minus.png'></span>";
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
$seat = array("ii-ii", "ii-ii", "ii-ii", "ii-ii", "ii-ii", "ii-ii", "ii-ii", "ii-ii", "ii-ii", "ii-ii","iiiii");
 
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
		echo "<span id='test'class='first available_new_seat_complete_r_booked_r_booked seat_col 'style=''><span  id='seat_no'style='position:relative;bottom:4px;'>xt1</span></span>";

}else if($status =="booked"){
			echo "<span id='test'class='first available_new_seat_booked_r_new seat_col 'style=''><span  id='seat_no'style='position:relative;bottom:4px;'>xt1</span></span>";

}else if ($status =="locked"){
			echo "<span id='test'class='first available_new_seat_locked_r_booked seat_col 'style=''><span  id='seat_no'style='position:relative;bottom:4px;'>xt1</span></span>";
	
}else if ($status =="half_complete"){
			echo "<span id='test'class='first available_new_seat_half_r_booked seat_col seat_name'style=''><span  id='seat_no'style='position:relative;bottom:4px;'>xt1</span></span>";
	
}

}else{
			echo "<span id='test'class='first available_new_seat_locked seat_col seat_name'style=''><span  id='seat_no'style='position:relative;bottom:4px;'>xt1</span></span>";

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
		echo "<span id='test'class='first available_new_seat_complete_r_booked_r_booked seat_col 'style=''><span  id='seat_no'style='position:relative;bottom:4px;'>xt2</span></span>";

}else if($status =="booked"){
			echo "<span id='test'class='first available_new_seat_booked_r_new seat_col 'style=''><span  id='seat_no'style='position:relative;bottom:4px;'>xt2</span></span>";

}else if ($status =="locked"){
			echo "<span id='test'class='first available_new_seat_locked_r_booked seat_col 'style=''><span  id='seat_no'style='position:relative;bottom:4px;'>xt2</span></span>";
	
}else if ($status =="half_complete"){
			echo "<span id='test'class='first available_new_seat_half_r_booked seat_col seat_name'style=''><span  id='seat_no'style='position:relative;bottom:4px;'>xt2</span></span>";
	
}

}else{
			echo "<span id='test'class='first available_new_seat_locked seat_col seat_name'style=''><span  id='seat_no'style='position:relative;bottom:4px;'>xt2</span></span>";

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


}
if($status =="complete"){
		echo "<span id='test'class='first available_new_seat_complete_r_booked_r_booked seat_col 'style=''><span  id='seat_no'style='position:relative;bottom:4px;'>xt1</span></span>";

}else if($status =="booked"){
			echo "<span id='test'class='first available_new_seat_booked_r_new seat_col 'style=''><span  id='seat_no'style='position:relative;bottom:4px;'>xt1</span></span>";

}else if ($status =="locked"){
			echo "<span id='test'class='first available_new_seat_locked_r_booked seat_col 'style=''><span  id='seat_no'style='position:relative;bottom:4px;'>xt1</span></span>";
	
}else if ($status =="half_complete"){
			echo "<span id='test'class='first available_new_seat_half_r_booked seat_col seat_name'style=''><span  id='seat_no'style='position:relative;bottom:4px;'>xt1</span></span>";
	
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
		echo "<span id='test'class='first available_new_seat_complete_r_booked_r_booked seat_col 'style=''><span  id='seat_no'style='position:relative;bottom:4px;'>xt2</span></span>";

}else if($status =="booked"){
			echo "<span id='test'class='first available_new_seat_booked_r_new seat_col 'style=''><span  id='seat_no'style='position:relative;bottom:4px;'>xt2</span></span>";

}else if ($status =="locked"){
			echo "<span id='test'class='first available_new_seat_locked_r_booked seat_col 'style=''><span  id='seat_no'style='position:relative;bottom:4px;'>xt2</span></span>";
	
}else if ($status =="half_complete"){
			echo "<span id='test'class='first available_new_seat_half_r_booked seat_col seat_name'style=''><span  id='seat_no'style='position:relative;bottom:4px;'>xt2</span></span>";
	
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
if( in_array("$seat_no" ,$set_selection ))
{

foreach ($set_selection as $subject => $marks){ 
if($marks==$seat_no){
	    echo "$subject"; 

}else{
	
}
}
}else{
		echo "<span id='test'class='first available_new_seat_locked seat_col seat_name'class='position-absolute  translate-middle  rounded-pill success'><span  id='seat_no'class='position-absolute  translate-middle  rounded-pill '>".$letter."".$let_no."</span></span>";

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


		   				<script>
										
										$(document).ready(function(){
											
var trip_start= $("#trip_start option:selected").text();
trip_end= $("#trip_end option:selected").text();
fare_price= $("#fare_price ").text();
fare_price=parseInt(fare_price);
route_day_id= $("#route_day_id5 ").text();
trip_date= $("#trip_date5 ").text();
route_id2= $("#route_id25 ").text();


 $('.seat_name').click(function() {
var bid = $(this).text();	
		new_bid = bid;
			var count = 0;
this_class = $(this).attr("class");

	if((this_class==="first available_new_seat_locked seat_col seat_name" )  || (this_class==="first seat_col seat_name available_new_seat_locked" )){
	$('#load_lock_table').append(add_input_field2(count));
	
	$('audio#pop')[0].play();
	
	$(this).removeClass("available_new_seat_locked");
	$(this).addClass("available_new_seat_locked_change");	
	}else{
	
	$('audio#pop')[0].play();
			var bid = $(this).text();
		
		
		new_bid = bid;
		
		 $('.tr'+new_bid+'').remove();
		 
		 
$(this).addClass("available_new_seat_locked");
	$(this).removeClass("available_new_seat_locked_change");	
	
	}



	
                });

	
	
	function add_input_field2(count)
	{

		var html = '';

			html += '<tr class="tr'+new_bid+'">';

		html += '<td><input type="text" name="resons[]" class="form-control item_name resons" id="resons"/></td>';

html += '<td class="display_none"><input type="text" name="route_day_id[]" class="form-control item_name route_day_id " id="route_day_id"value="'+route_day_id+'"/></td>';
html += '<td class="display_none"><input type="text" name="route_id[]" class="form-control item_name route_id " id="route_id" value="'+route_id2+'"/></td>';
html += '<td class="display_none"><input type="text" name="trip_date[]" class="form-control item_name trip_date " id="trip_date" value="'+trip_date+'"/></td>';
html += '<td class="display_none"><input type="text" name="seat_no[]" class="form-control item_name seat_no " id="seat_no" value="'+new_bid+'"/></td>';
html += '<td class="display_none"><input type="text" name="bus[]" class="form-control item_name bus " id="bus" value="'+bus+'"/></td>';

		return html;

	}


		$(document).on('keyup', '.resons', function(){
		
if ($('.copy_switch').is(':checked')) {
	
			var this_phone_id = $(this).val();
$(".resons").val(this_phone_id);	
		}else{
			
		}
	
	

	
	});
	
		$('#insert_form_lock').on('submit', function(event){

		event.preventDefault();

		var error = '';

		count = 1;

		$('.resons').each(function(){

			if($(this).val() == '')
			{

				error += "<li>Enter Item Name at "+count+" Row</li>";

			}

			count = count + 1;

		});

		count = 1;

	


		var form_data = $(this).serialize();

		if(error == '')
		{

			$.ajax({

				url:"lock_multiple_seats.php",

				method:"POST",

				data:form_data,

				beforeSend:function()
	    		{
$('#submit_button').val("processing....");
	    			$('#submit_button').attr('disabled', 'disabled');

	    		},

				success:function(data)
				{
					
				Swal.fire(
  'Locked!',
  '',
  'success'
)
						$('#submit_button').attr('disabled', false);
$('#submit_button').val("Continue");

$('#load_lock_table').find('tr:gt(0)').remove();

	
				}
			})

		}
		else
		{
			$('#error2').html('<div class="alert alert-danger"><ul>'+error+'</ul></div>');
		}

	});
			


});
										</script>
				
				
		 
