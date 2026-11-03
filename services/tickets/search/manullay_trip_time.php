<?php
include '../connect.php';
$id = $_POST['id'];
$route_id = $_POST['route_id'];


?>
<form class="needs-validation" name="form_trip_m" id="form_trip_m" novalidate>
<input type="hidden" id="route_day_id_t" name="route_day_id" value="<?php echo $id;?>">
<input type="hidden" id="route_id_t"name="id" value="<?php echo $route_id;?>">
                                           <table class="table mb-0" id="example">
                                                              <thead class="table-light">
<tr>   
<th></th>    
<th>Start Location</th>              
<th>End Location</th>
<th>Route name</th>
<th>Depature Time</th>
<th>Arrival Time</th>
<th>Update time </th>
<th>Update hourly </th>


</tr>
				
                          </thead>
						  
						  <tbody>
						  
						  
						  <?php
$select_main=mysqli_query($db,"SELECT *  FROM  routes WHERE route_id='$route_id' OR main_id='$route_id' ");
$select_num_main=mysqli_num_rows($select_main);
if($select_num_main !=0){
   	WHILE($get_main=mysqli_fetch_array($select_main)){
    	    $id_main =$get_main['id']; 
    	    $start_route =$get_main['start_route']; 
    	    $end_route =$get_main['end_route']; 
    	    $active_date =$get_main['active_date']; 
    	    $departure =$get_main['departure']; 
    	    $arrival =$get_main['arrival']; 
    	    $route_id_checked =$get_main['route_id']; 
    	   
    	    $fullname =$get_main['fullname']; 
    	    $route_name =$get_main['route_name']; 
$depature_time = "";
$arrival_time = "";


$select_time=mysqli_query($db,"SELECT *  FROM  travaling_time WHERE route_id='$route_id_checked' AND route_day_id='$id' ");
$select_num_time=mysqli_num_rows($select_time);
if($select_num_time !=0){
   	WHILE($get_time=mysqli_fetch_array($select_time)){
    	    $depature_time =$get_time['depature']; 
    	    $arrival_time =$get_time['arrival']; 
			
}}


			
$timeIn12HourFormat = $departure;
if($timeIn12HourFormat==""){
	$timeIn24HourFormat_route = "";
}else{
	$dateTime = new DateTime($timeIn12HourFormat);
$timeIn24HourFormat_route = $dateTime->format('H:i');

}






$timeIn12HourFormat_arrival = $arrival;

if($timeIn12HourFormat_arrival==""){
$timeIn24HourFormat_arrival_route ="";	
}else{
$dateTime_arrival = new DateTime($timeIn12HourFormat_arrival);
$timeIn24HourFormat_arrival_route = $dateTime_arrival->format('H:i');	
}

$select_points=mysqli_query($db,"SELECT *  FROM  route_points WHERE route_id= '$route_id_checked' ");
$select_num_points=mysqli_num_rows($select_points);
if($select_num_points ==0){
	
}else{
	
	
	$select_main=mysqli_query($db,"SELECT *  FROM  route_points  WHERE route_id = '$route_id' and initial_location = 'initial'  ");
$select_num_main=mysqli_num_rows($select_main);
if($select_num_main !=0){
   	WHILE($get_main=mysqli_fetch_array($select_main)){
    	    $route_point_t_id =$get_main['id']; 
   
			
}}

	
	$select_time_bl=mysqli_query($db,"SELECT *  FROM  travaling_time WHERE route_id='$route_id' AND route_day_id='$id' and route_point_id='$route_point_t_id' ");
$select_num_time_bl=mysqli_num_rows($select_time_bl);
if($select_num_time_bl !=0){
   	WHILE($get_time_bl=mysqli_fetch_array($select_time_bl)){
    	    $depature_time =$get_time_bl['depature']; 
    	    $arrival_time =$get_time_bl['arrival']; 
			
}}

	
	
?>
<tr >
<td><span class="main<?php echo $id_main;?>"><img class="img<?php echo $id_main;?>" src="images/left-arrow.png"></span></td>
<td><?php echo $start_route;?></td>
<td><?php echo $end_route;?></td>
<td><?php echo "$route_name"?></td>
<td><input id="departure<?php echo $id_main;?>"type="time" class="form-control "value="<?php if($depature_time==""){ echo $timeIn24HourFormat_route;}else{echo $depature_time;}?>"onfocus="this.showPicker()"></td>
<td><input id="arrival<?php echo $id_main;?>"type="time" class="form-control"value="<?php if($arrival_time==""){echo $timeIn24HourFormat_arrival_route;}else{echo $arrival_time;}?>" onfocus="this.showPicker()"></td>
<td><button type="button" class="btn btn-soft-light"id="update<?php echo $id_main;?>">
                       <i class="uil uil-pen"></i> <span id="text<?php echo $id_main;?>"></span></button></td>
<td><div class="input-group" style="width:100px;"><input type="number" name ="hours[]" class="form-control arr_hour"  value="0"> &nbsp : &nbsp<input type="number" name ="min[]" class="form-control arr_min"  pattern="[0-9]{3}-[0-9]{3}-[0-9]{4}" value="00"></div><input type="hidden" name ="route_id[]" class="form-control" value="<?php echo "$route_id_checked";?>"><input type="hidden"id="fullname<?php echo $id_main;?>" value="<?php echo $route_id_checked;?>"/><input type="hidden"id="value<?php echo $id_main;?>" value="main"/></td>

</tr>

<script>
$(".main<?php echo $id_main;?>").click(function(){
	no = $(this).attr("class").replace(/[^0-9]/gi,'');
	var images = $(".img"+no+"").attr('src');
if(images =="images/left-arrow.png"){
	$(".img"+no+"").attr("src","images/down-arrow.png");
	$(".sub"+no+"").show("drop");
}else{
	$(".img"+no+"").attr("src","images/left-arrow.png");
		$(".sub"+no+"").hide("fast");

}

});

$("#update<?php echo $id_main;?>").click(function(){
$("#text<?php echo $id_main;?>").text("updating....");
departure = $("#departure<?php echo $id_main;?>").val();
arrival = $("#arrival<?php echo $id_main;?>").val();
fullname = $("#fullname<?php echo $id_main;?>").val();
value = $("#value<?php echo $id_main;?>").val();
id = "<?php echo $id;?>";
route_id = "<?php echo $route_id_checked;?>";


error = "";
if(departure==""){
	error +="Fill departure time.";
}if(arrival==""){
	error +="Fill arrival time.";
}
if(error ==""){
	 $.ajax({
				url:"update/update_route_day_time.php",
				method:"POST",
				data:{departure:departure,arrival:arrival,fullname:fullname,value:value,id:id,route_id:route_id},
				success:function(response){
 id = "<?php echo $id;?>";
route_id = "<?php echo $route_id;?>";
//	alert(response);

$("#text<?php echo $id_main;?>").text("");
		


	}
			
			});
	
	
}else{
	
	 alertify.error(error);

}

});

</script>

<?php
$select_points=mysqli_query($db,"SELECT ROW_NUMBER() OVER (ORDER BY id ASC ) row_num,id,name,arrival,time  FROM  route_points WHERE route_id= '$route_id_checked' and point='starting'   ");
$select_num_points=mysqli_num_rows($select_points);
if($select_num_points !=0){
   	WHILE($get_points=mysqli_fetch_array($select_points)){
    	    $id_point =$get_points['id']; 
    	    $name =$get_points['name']; 
    	    $arravial_time =$get_points['arrival']; 
			
    	    $deparute_time =$get_points['time']; 
    	    $row_num =$get_points['row_num']; 
    	    
			
$timeIn12HourFormat = $deparute_time;

if($timeIn12HourFormat==""){


$timeIn24HourFormat = "";	
}else{
	$dateTime = new DateTime($timeIn12HourFormat);
$timeIn24HourFormat = $dateTime->format('H:i');
}
$timeIn12HourFormat_arrival = $arravial_time;
if($timeIn12HourFormat_arrival==""){
	$timeIn24HourFormat_arrival ="";
}else{
	$dateTime_arrival = new DateTime($timeIn12HourFormat_arrival);
$timeIn24HourFormat_arrival = $dateTime_arrival->format('H:i');
}
$depature_time = "";
$arrival_time="";


$select_time_bl=mysqli_query($db,"SELECT *  FROM  travaling_time WHERE route_id='$route_id_checked' AND route_day_id='$id' and route_point_id='$id_point' ");
$select_num_time_bl=mysqli_num_rows($select_time_bl);
if($select_num_time_bl !=0){
   	WHILE($get_time_bl=mysqli_fetch_array($select_time_bl)){
    	    $depature_time =$get_time_bl['depature']; 
    	    $arrival_time =$get_time_bl['arrival']; 
			
}}








    	    ?>
			<tr class="sub<?php echo $id_main;?>" style="display:none;">
			<td></td>
			<td></td>
			<td><?php echo "$row_num. ";?></td>
			<td><?php echo $name;?></td>
			<td><input id="departure_point<?php echo $id_point;?>"type="time" class="form-control"value="<?php if($depature_time==""){echo $timeIn24HourFormat;}else{echo "$depature_time";}?>"onfocus="this.showPicker()"></td>
			<td><input id="arrival_point<?php echo $id_point;?>"type="time" class="form-control"value="<?php if($arrival_time==""){if($timeIn24HourFormat_arrival ==""){ echo $timeIn24HourFormat_arrival_route;}else{echo $timeIn24HourFormat_arrival;}}else{ echo $arrival_time;}?>" onfocus="this.showPicker()"></td>
			<td><button type="button" class="btn btn-soft-light update_sub"id="update_sub<?php echo $id_point;?>">
                       <i class="uil uil-pen"></i> <span id="text<?php echo $id_point;?>"></span></button></td>
						<td><input type="hidden"id="fullname_point<?php echo $id_point;?>" value="<?php echo $id_point;?>"/><input type="hidden"id="value_point<?php echo $id_point;?>" value="points"/></td>

			</tr>
			
<script>

$("#update_sub<?php echo $id_point;?>").click(function(){
departure = $("#departure_point<?php echo $id_point;?>").val();
arrival = $("#arrival_point<?php echo $id_point;?>").val();
fullname = $("#fullname_point<?php echo $id_point;?>").val();
value = $("#value_point<?php echo $id_point;?>").val();
id = "<?php echo $id;?>";
route_id = "<?php echo $route_id_checked;?>";
route_day_id = "<?php echo $route_id_checked;?>";
id_point = "<?php echo $id_point;?>";
$("#text<?php echo $id_point;?>").text("updating....");
error = "";
if(departure==""){
	error +="Fill departure time.";
}if(arrival==""){
	error +="Fill arrival time.";
}
if(error ==""){
	
	 $.ajax({
				url:"update/update_route_day_time.php",
				method:"POST",
				data:{departure:departure,arrival:arrival,fullname:fullname,value:value,id:id,route_id:route_id,route_day_id:route_day_id,id_point:id_point},
				success:function(response){
					alert(response);
id = "<?php echo $id;?>";
route_id = "<?php echo $route_id;?>";
$("#text<?php echo $id_point;?>").text("");



	}
			
			});
	
	
}else{
	

}


	});

</script>
			<?php
			
}}
}
}
}
						  ?>
					<tr >
					<td style="border:none;"></td>
					<td style="border:none;"></td>
					<td style="border:none;"></td>
					<td style="border:none;"></td>
					<td style="border:none;"></td>
					<td style="border:none;"></td>
					<td style="border:none;"></td>
					<td style="border:non;" >  
					<button type="submit" class="btn btn-primary center" id="search_r"> <i class="uil uil-clock"></i> <span class="search_txt">Update hourly</span></button>
                    </td>
					</tr>	  
						  </tbody>
						  
                                          <!-- end tbody --> 
                                            </table>
</form>
<div id ="response_m"></div>
		<script src="js/update/trip_time_auto.js"></script>
		<script>
		$(".arr_hour").keyup(function(){
			hours = $(this).val();
			if(hours >23){
			$(this).val("0");
			}else{
			echo 	
			}
		});
		$(".arr_min").keyup(function(){
			hours = $(this).val();
			if(hours >59){
			$(this).val("00");
			}else{
			echo 	
			}
		});
		</script>
