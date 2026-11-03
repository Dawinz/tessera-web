
	  <table id="example"  class="table table-hover table-nowrap mb-0 align-middle table-check" style="">
                                            <thead class="bg-light">
                                                <tr>
                                                    <th class="rounded-start" style="width: 15px;">
                                                  Route 
                                                    </th>
                                                    
													<th>Bus</th>
													<th>Dept</th>
                                                    <th>Arrival</th>
                                                    <th>Cost</th>
                                                    <th>Available</th>
                                              <th >Book</th> 
											  <th>lock</th>
											  <th ></th>
											  <th></th>
                                                </tr>
                                                <!-- end tr -->
                                            </thead>
                                            <!-- end thead -->
                      <tbody> 
	  <?php
	  session_start();
include 'connect.php';
	  $username=$_SESSION['username'];
	   $select2=mysqli_query($db,"SELECT *  FROM  users  where username='$username'");
$select_num2=mysqli_num_rows($select2);
if($select_num2 !=0){
   	WHILE($get12=mysqli_fetch_array($select2)){
    	    $level =$get12['role']; 

	
	}
}

	 $route_id="";
 $starting_location = $_GET['starting_location'];
$ending_location = $_GET['ending_location'];

$start_location = preg_replace('/_/',' ', $starting_location);
$ending_location = preg_replace('/_/',' ', $ending_location);
$trip_date= $_GET['starting_date'];
$trip_date = date("Y-m-d", strtotime($trip_date));  


date_default_timezone_set('Africa/Dar_es_Salaam');
$date_insert = date('Y-m-d', time());
$trip_date_tomorrow = date('l', strtotime($trip_date));

	
 $date1=date_create($trip_date);
$date2=date_create($date_insert);
$diff=date_diff($date2,$date1);
$date_left =  $diff->format("%R%a");

if(($date_left < 0) and ($level=="Agent")){
			

}else{
	

	$select14=mysqli_query($db,"SELECT *  FROM  routes WHERE start_route='$start_location' ");
$select_num14=mysqli_num_rows($select14);
if($select_num14 !=0){
   	WHILE($get134=mysqli_fetch_array($select14)){
    	    $id =$get134['id'];
			$start_route =$get134['start_route'];
			$end_route =$get134['end_route'];
			$route_id =$get134['route_id']; 
		$fare_price =$get134['fare_price']; 
			$detail =$get134['detail'];
        $d_time =$get134['departure']; 

		$trip_date_tomorrow = date('l', strtotime($trip_date));
		$select=mysqli_query($db,"SELECT *  FROM  route_day where   day='$trip_date_tomorrow' and route_id ='$route_id' and status='active' ORDER BY day ");
$select_num=mysqli_num_rows($select);
if($select_num !=0){
   	WHILE($get=mysqli_fetch_array($select)){
    	   
			$route_id =$get['route_id'];
$id2 =$get['id'];
			$arrival =$get['arrival'];
			$departure =$get['departure'];
			$time =$get['12hours'];
			$trip_day =$get['day']; 
			$bus =$get['bus']; 
			$now_time = date('h:i A', time());
	

$select14=mysqli_query($db,"SELECT *  FROM  routes WHERE route_id='$route_id'  ");
$select_num14=mysqli_num_rows($select14);
if($select_num14 !=0){
   	WHILE($get134=mysqli_fetch_array($select14)){
    	    $id =$get134['id'];
			$start_route =$get134['start_route'];
			$end_route =$get134['end_route'];
			$route_id =$get134['route_id']; 
			$fare_price =$get134['fare_price'];
			$currency_symbol =$get134['currency_symbol'];
			$detail =$get134['detail']; 
			$route_name =$get134['route_name']; 

		

}}
$select_state=mysqli_query($db,"SELECT seats,state   FROM  vehicle WHERE name='$bus'");
$select_num_state=mysqli_num_rows($select_state);
if($select_num_state !=0){
   	WHILE($get_state=mysqli_fetch_array($select_state)){
 


 $state= $get_state['state'];

 if($state=="vip"){



$select_fair=mysqli_query($db,"SELECT vip_price  FROM  routes WHERE route_id='$route_id'");
$select_num_fair=mysqli_num_rows($select_fair);
if($select_num_fair !=0){
   	WHILE($get_fair=mysqli_fetch_array($select_fair)){
 

 $vip_price= $get_fair['vip_price'];
$fare_price =$vip_price;

}}



	}}
}
	

						$select1730=mysqli_query($db,"SELECT *  FROM  vehicle Where name='$bus' ");
$select_num1730=mysqli_num_rows($select1730);
if($select_num1730 !=0){
	   	WHILE($get13730=mysqli_fetch_array($select1730)){
 
 $seats_bus= $get13730['seats'];



}
}	
$seats=0;
$select173=mysqli_query($db,"SELECT *  FROM  manifest Where bus='$bus' and trip_date='$trip_date'");
$select_num173=mysqli_num_rows($select173);
if($select_num173 !=0){
	   	WHILE($get1373=mysqli_fetch_array($select173)){
 
 $seats= $get1373['seats'];


}
}else{
$seats2= $seats_bus;	
}
	
?>
 <tr >                                                   
												   <td>
                                                   <span ><?php echo "".base64_decode($detail)." (".$route_name.")";?></span>
                                                    </td>   <td>
                                                   <?php echo $bus;?>
                                                    </td>
                                                    <td class="fw-medium">
                                                        <?php echo $departure;?>
                                                    </td>
                                                    <td>
                                                  <?php
												  echo $arrival;
												  ?>
                                                    </td>

                                                    <td>
                                                 <?php echo ''.number_format($fare_price).' '.$currency_symbol.''?>
                                                    </td>
                                                    <td>
                                                   <?php echo $seats_bus-$seats;;?>
                                                    </td>
                                                    <td>
 <button type="button" class="btn btn-outline-primary"id="book<?php echo $id2;?>">Book </button>                                                      </td>

                                                    <td>
                                                 <div class="square-switch">
                                                            <input type="checkbox" id="square-switch<?php echo $id2;?>" switch="bool" class="open_booking_switch"  />
                                                            <label for="square-switch<?php echo $id2;?>" data-on-label="Open "
                                                                data-off-label=" Lock" class="mb-0"></label>
                                                        </div>
                                                    </td>            <td >
                                                     <div class="dropdown" >
                                                            <a href="#" class="dropdown-toggle card-drop"
                                                                data-bs-toggle="dropdown" aria-expanded="false">
                                                                <i
                                                                    class="mdi mdi-dots-horizontal font-size-18 text-muted"></i>
                                                            </a>
                                                            <ul class="dropdown-menu dropdown-menu-end" >
                                                                <li><div style="cursor: pointer;" id="manifest<?php echo $id2;?>" class="dropdown-item" ><i
                                                                            class="mdi mdi-list-status font-size-16 text-success me-1"></i>
                                                                        Manifest</div></li>
                                                                <li><div style="cursor: pointer;"id="seat_arrangement<?php echo $id2;?>" class="dropdown-item"><i
                                                                            class="mdi mdi-clipboard-text font-size-16 text-info me-1"></i>
                                                                        Seat Chart Manifest</a></div><li><div style="cursor: pointer;"id="charg_summary<?php echo $id2;?>"href="#" class="dropdown-item"><i
                                                                            class="mdi mdi-clipboard-plus font-size-16 text-primary me-1"></i>
                                                                        Charge Summary</div></li>
                                                            </ul>
                                                        </div>
                                                    </td>
                                                    <td><?php echo $id2;?></td>
													</tr>



				<script>
													$(document).ready(function(){
														
															$("#manifest<?php echo $id2;?>").click(function(){
															$(".bs-example-modal-xl_manifest").modal("show");
														 $(".trip_date_text").text("<?php echo date("l jS \of F Y", strtotime($trip_date));?>");
                                                         $(".bus_name").text("<?php echo @$bus;?>");
														 				id = "<?php echo @$id2;?>";
															trip_date = "<?php echo $trip_date;?>";
															route_id = "<?php echo $route_id;?>";
																bus = "<?php echo $bus;?>";

															
												
														$("#manifest_place_holder").load("passenger_manifest_whole.php?id="+id+"&trip_date="+trip_date+"&route_id="+route_id+"&bus="+bus);

														 
														 
														});
														
														
														
														$("#book<?php echo $id2;?>").click(function(){
														$(".route_selection_spiner").show();
															id = "<?php echo $id2;?>";
															trip_date = "<?php echo $trip_date;?>";
															route_id = "<?php echo $route_id;?>";
															bus = "<?php echo $bus;?>";
															fare_price = "<?php echo $fare_price;?>";
															start_location = "<?php echo str_replace(" " , "_", $start_route);?>";
															ending_location = "<?php echo  str_replace(" " , "_", $end_route);?>";
															
                                                         $(".alert_class").show();
                                                         $(".route_loaded_date").text("<?php echo date("l jS \of F Y", strtotime($trip_date));?>");
                                                         $(".load_route_bus").text("<?php echo $bus;?>");
													
														 $.ajax({
				url:"load_rout_starting_p.php",
				method:"POST",
				data:{start_location:start_location,route_id:route_id},
				success:function(response){
		$('#trip_start').html(response);
				
	}
			
			});		

		
			$.ajax({
				url:"load_rout_starting_p.php",
				method:"POST",
				data:{ending_location:ending_location,route_id:route_id},
				success:function(response){
				
		$('#trip_end').html(response);
				
	}
			
			});
$("#load_seat_chart").load("load_seat_chart.php?id="+id+"&trip_date="+trip_date+"&route_id="+route_id+"&start_location="+start_location+"&ending_location="+ending_location+"&bus="+bus+"&fare_price="+fare_price);

														
														});
														
														$("#square-switch<?php echo $id2;?>").click(function(){
																	id = "<?php echo $id2;?>";
															trip_date = "<?php echo $trip_date;?>";
															route_id = "<?php echo $route_id;?>";
															bus = "<?php echo $bus;?>";
															fare_price = "<?php echo $fare_price;?>";
															start_location = "<?php echo str_replace(" " , "_", $start_route);?>";
															ending_location = "<?php echo  str_replace(" " , "_", $end_route);?>";
															
														$(".bs-example-modal-xl").modal("show");
												$("#load_booking_seat_chart").load("placeholder_seat.html");

														$("#load_booking_seat_chart").load("load_booking_seat_chart.php?id="+id+"&trip_date="+trip_date+"&route_id="+route_id+"&start_location="+start_location+"&ending_location="+ending_location+"&bus="+bus+"&fare_price="+fare_price);

														});
														
															$("#charg_summary<?php echo $id2;?>").click(function(){
																id = "<?php echo @$id2;?>";
															trip_date = "<?php echo $trip_date;?>";
															route_id = "<?php echo $route_id;?>";
																bus = "<?php echo $bus;?>";
															
														$(".exampleModalFullscreen").modal('show');
																		var placeholder = document.createElement("div");
placeholder.innerHTML = "<strong>Loading...</strong>";

var iframe = document.createElement("iframe");
iframe.style.display = "none";

var сontainer =  document.getElementById("container_print");

// Прикреплять к контейнеру нужно сразу, так как "appendChild" после события "load" вызовет повторную загрузку и рекурсивное событие load.
сontainer.appendChild(placeholder);
сontainer.appendChild(iframe);

iframe.src = "total_charge_sum.php?id="+id+"&trip_date="+trip_date+"&route_id="+route_id;
iframe.addEventListener("load", function(event) {
  console.log("DOM fully loaded and parsed", event.target.src);
  
  сontainer.removeChild(placeholder);
  event.target.style.display = "";
});	
														});
														
														
														
														
															$("#seat_arrangement<?php echo $id2;?>").click(function(){
																id = "<?php echo @$id2;?>";
															trip_date = "<?php echo $trip_date;?>";
															route_id = "<?php echo $route_id;?>";
																bus = "<?php echo $bus;?>";
															
														$(".exampleModalFullscreen_seat").modal('show');
																		var placeholder = document.createElement("div");
placeholder.innerHTML = "<strong>Loading...</strong>";

var iframe = document.createElement("iframe");
iframe.style.display = "none";

var сontainer =  document.getElementById("container_print_seat");

// Прикреплять к контейнеру нужно сразу, так как "appendChild" после события "load" вызовет повторную загрузку и рекурсивное событие load.
сontainer.appendChild(placeholder);
сontainer.appendChild(iframe);

iframe.src = "passenger_seat_chart.php?id="+id+"&trip_date="+trip_date+"&route_id="+route_id;
iframe.addEventListener("load", function(event) {
  console.log("DOM fully loaded and parsed", event.target.src);
  
  сontainer.removeChild(placeholder);
  event.target.style.display = "";
});	
														});	
														
														
														
														
													});
													
													</script>
<?php




			
}}
	}
}else{
	$select145=mysqli_query($db,"SELECT *  FROM  sub_route  WHERE start_route='$start_location' ");
$select_num145=mysqli_num_rows($select145);
if($select_num145 !=0){
   	WHILE($get1345=mysqli_fetch_array($select145)){
    	    $id =$get1345['id'];
			$start_route =$get1345['start_route'];
			$end_route =$get1345['end_route'];
			$route_id = $get1345['main_route_no']; 
		$fare_price =$get1345['fare_price']; 
			$detail =$get1345['detail']; 
			$route_name =$get1345['route_name']; 
			$active_day =$get1345['active_day']; 
			$d_time =$get1345['d_time']; 
			$sub_route_id =$get1345['sub_route_id']; 

		$trip_date_tomorrow = date('l', strtotime($trip_date));
		
		$currency_symbol =$get1345['currency_symbol'];
	
	$select=mysqli_query($db,"SELECT *  FROM  route_day where   day='$trip_date_tomorrow' and route_id ='$route_id' and status='active' ORDER BY day ");
$select_num=mysqli_num_rows($select);
if($select_num !=0){
   	WHILE($get=mysqli_fetch_array($select)){
    	   
			$route_id =$get['route_id'];
$id2 =$get['id'];
			$arrival =$get['arrival'];
			$departure =$get['departure'];
			$time =$get['12hours'];
			$trip_day =$get['day']; 
			$bus =$get['bus']; 
			$now_time = date('h:i A', time());
	

$select14=mysqli_query($db,"SELECT *  FROM  routes WHERE route_id='$route_id'  ");
$select_num14=mysqli_num_rows($select14);
if($select_num14 !=0){
   	WHILE($get134=mysqli_fetch_array($select14)){
    	    $id =$get134['id'];
			$start_route =$get134['start_route'];
			$end_route =$get134['end_route'];
			$route_id =$get134['route_id']; 
			$fare_price =$get134['fare_price'];
			$currency_symbol =$get134['currency_symbol'];
			$detail =$get134['detail']; 
			$route_name =$get134['route_name']; 

		

}}
		$select_state=mysqli_query($db,"SELECT seats,state   FROM  vehicle WHERE name='$bus'");
$select_num_state=mysqli_num_rows($select_state);
if($select_num_state !=0){
   	WHILE($get_state=mysqli_fetch_array($select_state)){
 


 $state= $get_state['state'];

 if($state=="vip"){



$select_fair=mysqli_query($db,"SELECT vip_price  FROM  sub_route WHERE sub_route_id='$sub_route_id'");
$select_num_fair=mysqli_num_rows($select_fair);
if($select_num_fair !=0){
   	WHILE($get_fair=mysqli_fetch_array($select_fair)){
 

 $vip_price= $get_fair['vip_price'];
$fare_price =$vip_price;

}}



	}}
}
						$select1730=mysqli_query($db,"SELECT *  FROM  vehicle Where name='$bus' ");
$select_num1730=mysqli_num_rows($select1730);
if($select_num1730 !=0){
	   	WHILE($get13730=mysqli_fetch_array($select1730)){
 
 $seats_bus= $get13730['seats'];



}
}	

$select173=mysqli_query($db,"SELECT *  FROM  manifest Where bus='$bus' and trip_date='$trip_date'");
$select_num173=mysqli_num_rows($select173);
if($select_num173 !=0){
	   	WHILE($get1373=mysqli_fetch_array($select173)){
 
 $seats= $get1373['seats'];


}
}else{
$seats= $seats_bus;	
}
	
?>
 <tr>                                                   
												   <td>
                                                   <span ><?php echo "".base64_decode($detail)." (".$route_name.")";?></span>
                                                    </td>   <td>
                                                   <?php echo $bus;?>
                                                    </td>
                                                    <td class="fw-medium">
                                                        <?php echo $departure;?>
                                                    </td>
                                                    <td>
                                                  <?php
												  echo $arrival;
												  ?>
                                                    </td>

                                                    <td>
                                                 <?php echo ''.number_format($fare_price).' '.$currency_symbol.''?>
                                                    </td>
                                                    <td>
                                                   <?php echo $seats;?>
                                                    </td>
                                                    <td>
 <button type="button" class="btn btn-outline-primary"id="book<?php echo $id2;?>">Book </button>                                                      </td>

                                                    <td>
                                                 <div class="square-switch">
                                                            <input type="checkbox" id="square-switch<?php echo $id2;?>" switch="bool" class="open_booking_switch"  />
                                                            <label for="square-switch<?php echo $id2;?>" data-on-label="Open "
                                                                data-off-label=" Lock" class="mb-0"></label>
                                                        </div>
                                                    </td>            <td>
                                                     <div class="dropdown">
                                                            <a href="#" class="dropdown-toggle card-drop"
                                                                data-bs-toggle="dropdown" aria-expanded="false">
                                                                <i
                                                                    class="mdi mdi-dots-horizontal font-size-18 text-muted"></i>
                                                            </a>
                                                            <ul class="dropdown-menu dropdown-menu-end">
                                                                <li><div style="cursor: pointer;" id="manifest<?php echo $id2;?>" class="dropdown-item" ><i
                                                                            class="mdi mdi-list-status font-size-16 text-success me-1"></i>
                                                                        Manifest</div></li>
                                                                <li><div style="cursor: pointer;"id="seat_arrangement<?php echo $id2;?>" class="dropdown-item"><i
                                                                            class="mdi mdi-clipboard-text font-size-16 text-info me-1"></i>
                                                                        Seat Chart Manifest</a></div><li><div style="cursor: pointer;"id="charg_summary<?php echo $id2;?>"href="#" class="dropdown-item"><i
                                                                            class="mdi mdi-clipboard-plus font-size-16 text-primary me-1"></i>
                                                                        Charge Summary</div></li>
                                                            </ul>
                                                        </div>
                                                    </td>
                                                    <td><?php echo $id2;?></td>
													</tr>



				<script>
													$(document).ready(function(){
														
															$("#manifest<?php echo $id2;?>").click(function(){
															$(".bs-example-modal-xl_manifest").modal("show");
														 $(".trip_date_text").text("<?php echo date("l jS \of F Y", strtotime($trip_date));?>");
                                                         $(".bus_name").text("<?php echo @$bus;?>");
														 				id = "<?php echo @$id2;?>";
															trip_date = "<?php echo $trip_date;?>";
															route_id = "<?php echo $route_id;?>";
																bus = "<?php echo $bus;?>";

															
												
														$("#manifest_place_holder").load("passenger_manifest_whole.php?id="+id+"&trip_date="+trip_date+"&route_id="+route_id+"&bus="+bus);

														 
														 
														});
														
														
														
														$("#book<?php echo $id2;?>").click(function(){
														$(".route_selection_spiner").show();
															id = "<?php echo $id2;?>";
															trip_date = "<?php echo $trip_date;?>";
															route_id = "<?php echo $route_id;?>";
															bus = "<?php echo $bus;?>";
															fare_price = "<?php echo $fare_price;?>";
															start_location = "<?php echo str_replace(" " , "_", $start_route);?>";
															ending_location = "<?php echo  str_replace(" " , "_", $end_route);?>";
															
                                                         $(".alert_class").show();
                                                         $(".route_loaded_date").text("<?php echo date("l jS \of F Y", strtotime($trip_date));?>");
                                                         $(".load_route_bus").text("<?php echo $bus;?>");
													
														 $.ajax({
				url:"load_rout_starting_p.php",
				method:"POST",
				data:{start_location:start_location,route_id:route_id},
				success:function(response){
		$('#trip_start').html(response);
				
	}
			
			});		

		
			$.ajax({
				url:"load_rout_starting_p.php",
				method:"POST",
				data:{ending_location:ending_location,route_id:route_id},
				success:function(response){
				
		$('#trip_end').html(response);
				
	}
			
			});
$("#load_seat_chart").load("load_seat_chart.php?id="+id+"&trip_date="+trip_date+"&route_id="+route_id+"&start_location="+start_location+"&ending_location="+ending_location+"&bus="+bus+"&fare_price="+fare_price);

														
														});
														
														$("#square-switch<?php echo $id2;?>").click(function(){
																	id = "<?php echo $id2;?>";
															trip_date = "<?php echo $trip_date;?>";
															route_id = "<?php echo $route_id;?>";
															bus = "<?php echo $bus;?>";
															fare_price = "<?php echo $fare_price;?>";
															start_location = "<?php echo str_replace(" " , "_", $start_route);?>";
															ending_location = "<?php echo  str_replace(" " , "_", $end_route);?>";
															
														$(".bs-example-modal-xl").modal("show");
												$("#load_booking_seat_chart").load("placeholder_seat.html");

														$("#load_booking_seat_chart").load("load_booking_seat_chart.php?id="+id+"&trip_date="+trip_date+"&route_id="+route_id+"&start_location="+start_location+"&ending_location="+ending_location+"&bus="+bus+"&fare_price="+fare_price);

														});
														
															$("#charg_summary<?php echo $id2;?>").click(function(){
																id = "<?php echo @$id2;?>";
															trip_date = "<?php echo $trip_date;?>";
															route_id = "<?php echo $route_id;?>";
																bus = "<?php echo $bus;?>";
															
														$(".exampleModalFullscreen").modal('show');
																		var placeholder = document.createElement("div");
placeholder.innerHTML = "<strong>Loading...</strong>";

var iframe = document.createElement("iframe");
iframe.style.display = "none";

var сontainer =  document.getElementById("container_print");

// Прикреплять к контейнеру нужно сразу, так как "appendChild" после события "load" вызовет повторную загрузку и рекурсивное событие load.
сontainer.appendChild(placeholder);
сontainer.appendChild(iframe);

iframe.src = "total_charge_sum.php?id="+id+"&trip_date="+trip_date+"&route_id="+route_id;
iframe.addEventListener("load", function(event) {
  console.log("DOM fully loaded and parsed", event.target.src);
  
  сontainer.removeChild(placeholder);
  event.target.style.display = "";
});	
														});
														
														
														
														
															$("#seat_arrangement<?php echo $id2;?>").click(function(){
																id = "<?php echo @$id2;?>";
															trip_date = "<?php echo $trip_date;?>";
															route_id = "<?php echo $route_id;?>";
																bus = "<?php echo $bus;?>";
															
														$(".exampleModalFullscreen_seat").modal('show');
																		var placeholder = document.createElement("div");
placeholder.innerHTML = "<strong>Loading...</strong>";

var iframe = document.createElement("iframe");
iframe.style.display = "none";

var сontainer =  document.getElementById("container_print_seat");

// Прикреплять к контейнеру нужно сразу, так как "appendChild" после события "load" вызовет повторную загрузку и рекурсивное событие load.
сontainer.appendChild(placeholder);
сontainer.appendChild(iframe);

iframe.src = "passenger_seat_chart.php?id="+id+"&trip_date="+trip_date+"&route_id="+route_id;
iframe.addEventListener("load", function(event) {
  console.log("DOM fully loaded and parsed", event.target.src);
  
  сontainer.removeChild(placeholder);
  event.target.style.display = "";
});	
														});	
														
														
														
														
													});
													
													</script>
<?php




			
}}
	
}

}
	

}
	
	


}
?>

					  </tbody>
					  </table>
					
		 <div class="modal fade bs-example-modal-xl" tabindex="-1" role="dialog"
                                            aria-labelledby="myExtraLargeModalLabel" aria-hidden="true">
                                            <div class="modal-dialog modal-xl">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title" id="myExtraLargeModalLabel">Lock seat </h5>
                                                        <button type="button" class="btn-close closebtnmodal" data-bs-dismiss="modal"
                                                            aria-label="Close"></button>
                                                    </div>
                                                    <div class="modal-body">
													<div class="row">
													<div class="col">
													<div id="load_booking_seat_chart"class="rotated"></div>
													</div>
													<div class="col">
													 <div class="square-switch">
                                                            <input type="checkbox" id="square-switch1" switch="bool" class="copy_switch" checked />
                                                            <label for="square-switch1" data-on-label="copy"
                                                                data-off-label="Off" class="mb-0"></label>
                                                        </div><br>
														<form method="post" id="insert_form_lock">
						<div class="table-repsonsive">
							<span id="error2"></span>
							<table class="table table-bordered" id="load_lock_table">
								<tr>
									<th>Reson</th>
								
									<th class="display_none">route_day</th>
									<th class="display_none">Route id</th>
									<th class="display_none">Trip date</th>
									<th class="display_none">Seat no</th>
								</tr>
							</table>
							<div align="center">
								<input type="submit" name="submit" id="submit_button" class="btn btn-primary" value="Lock seat" />
							</div>
						</div>
					</form>
													
													</div>
													</div>
													
                                                    
                                                    </div>
                                                </div><!-- /.modal-content -->
                                            </div><!-- /.modal-dialog -->
                                        </div>
													<script>
$(document).ready(function() {


		 $('#example').DataTable({
			 dom: 'Bfrtip',
			 "lengthMenu": [ 3 ],
			 order: [[8, 'desc']],
			  columnDefs: [
        {
            target: 9,
            visible: false,
            searchable: false
        }
    ]
	});
  
	$('.bs-example-modal-xl').modal({
    backdrop: 'static',
    keyboard: false  // to prevent closing with Esc button (if you want this too)
});
$(".closebtnmodal").click(function(){
$(".open_booking_switch").prop('checked', false);
$('#insert_form_lock').find('tr:gt(0)').remove();

});
});

</script>