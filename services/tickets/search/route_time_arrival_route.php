<?php
include '../connect.php';
$id = $_POST['id'];
$route_id = $_POST['route_id'];
$route_type = $_POST['route_type'];
$main_id = $_POST['main_id'];

if($route_type="Main Route"){
$route_query = "WHERE route_id='".$route_id."' OR main_id='$route_id' AND route_type='Sub Route'";
}else{
$route_query = "WHERE route_id='".$route_id."' and main_id='".$main_id."'";
}

?>
<form class="needs-validation" name="form_trip_m" id="form_trip_m" novalidate>
<input type="hidden" id="route_day_id_r"name="route_day_id" value="<?php echo $id;?>">
<input type="hidden" id="route_id_r"name="route_day_id" value="<?php echo $route_id;?>">
                                           <table class="table mb-0" id="example">
                                                              <thead class="table-light">
<tr>   
<th></th>    
<th>Start Location</th>              
<th>End Location</th>
<th>Route name</th>
<th>Arrival Time</th>



</tr>
				
                          </thead>
						  
						  <tbody>
						  
						  
						  <?php

						  function arrival_time($db,$id_point,$route_id_checked,$id){
							$arrival="";	
$select_time=mysqli_query($db,"SELECT arrival  FROM   route_time WHERE point_id= '$id_point' and route_id='$route_id_checked' and status='arrival' ");
$select_num_time=mysqli_num_rows($select_time);
if($select_num_time !=0){
   	WHILE($get_time=mysqli_fetch_array($select_time)){
    	    $arrival =$get_time['arrival']; 
    	    

	}}
			echo $arrival;	

}

$select_main=mysqli_query($db,"SELECT *  FROM  routes  $route_query");
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
    	    $route_type =$get_main['route_type']; 
    	    $route_name =$get_main['route_name']; 
    	    $main_id =$get_main['main_id']; 

?>

<tr >
<td><span class="main<?php echo $id_main;?>"><img class="img<?php echo $id_main;?>" src="images/left-arrow.png"></span></td>
<td><?php echo $start_route;?></td>
<td><?php echo $end_route;?></td>
<td><?php echo "$route_name"?></td>
<td></td>


</tr>
<?php


$select_points=mysqli_query($db,"SELECT *  FROM  route_points WHERE route_id= '$route_id_checked' and point='end'  ");
$select_num_points=mysqli_num_rows($select_points);
if($select_num_points !=0){
   	WHILE($get_points=mysqli_fetch_array($select_points)){
    	    $id_point =$get_points['id']; 
    	    $name =$get_points['name']; 
    	    $arravial_time =$get_points['arrival']; 		
    	    $deparute_time =$get_points['time']; 			
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










    	    ?>
			<tr class="sub<?php echo $id_main;?>" style="display:non;">
			<td></td>
			<td></td>
			<td><?php echo $name;?></td>
			<td><input id="arrival_point<?php echo $id_point;?>"type="time" class="form-control"value="<?php arrival_time($db,$id_point,$route_id_checked,$id);?>" onfocus="this.showPicker()"></td>
			<td><button type="button" class="btn btn-soft-light update_sub"id="update_sub<?php echo $id_point;?>">
                       <i class="uil uil-pen"></i> <span id="text<?php echo $id_point;?>"></span></button></td>
			
						<td style="display:none;"><input type="hidden"id="fullname_point<?php echo $id_point;?>" value="<?php echo $id_point;?>"/><input type="hidden"id="value_point<?php echo $id_point;?>" value="points"/></td>

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
route_type = "<?php echo $route_type;?>";
main_id = "<?php echo $main_id;?>";
name = "<?php echo $name;?>";
status = "arrival";
error = "";
if(departure==""){
	error +="Fill departure time.";
}if(arrival==""){
	error +="Fill arrival time.";
}
if(error ==""){
	$("#text<?php echo $id_point;?>").text("updating....");
	 $.ajax({
				url:"update/update_time_arrival_route.php",
				method:"POST",
				data:{name:name,arrival:arrival,fullname:fullname,value:value,id_point:id_point,main_id:main_id,route_type:route_type,route_id:route_id,id:id,status:status},
				success:function(response){
             //alert(response)
id = "<?php echo $id;?>";
route_id = "<?php echo $route_id;?>";
$("#text<?php echo $id_point;?>").text("");
new_response = $.trim(response);
	Swal.fire(
  '<span style="color:white">Success </span>',
 
  
)

	}
			
			});
	
	
}else{
Swal.fire(
  '<span style="color:white">Fill all fields </span>',
  
)

}


	});

</script>
			<?php
			
}}
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
					
					</tr>	  
						  </tbody>
						  
                                          <!-- end tbody --> 
                                            </table>
</form>
<div id ="response_m"></div>
		<script src="js/update/trip_time_auto_route.js"></script>
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
