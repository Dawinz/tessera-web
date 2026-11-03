<?php
session_start();
$username=$_SESSION['username'];
$starting=$_GET['starting'];
$starting = preg_replace('/_/',' ', $starting);
$ending=$_GET['ending'];
 $ending = preg_replace('/_/',' ', $ending);
$date=$_GET['date'];
@$date = $_GET['date'];
$date_now = date("m/d/Y", strtotime($date));
$date_now2 = date("F.d.Y", strtotime($date));
$current_date = date("l jS \of F Y", strtotime($date_now));
								


?>

		<?php
		include 'connect.php';
		 
$username=$_SESSION['username'];
		$select14=mysqli_query($db,"SELECT *  FROM  assigned_routes WHERE start_route='$starting' AND end_route='$ending' AND username='".$_SESSION['username']."'");
$select_num14=mysqli_num_rows($select14);
if($select_num14 !=0){
   	WHILE($get134=mysqli_fetch_array($select14)){
    	    $id =$get134['id'];
			$start_route =$get134['start_route'];
			$end_route =$get134['end_route'];
			$route_id =$get134['route_id']; 
			$active =$get134['active']; 
			$active_tab =$get134['active_tab']; 
			$fare_price =$get134['fare_price']; 
			$detail =$get134['detail']; 

		
		
}}else{
	
}
		?>


	     					 <?php
										
$trip_date = date('l', strtotime($date));

		$select=mysqli_query($db,"SELECT *  FROM  route_day where   day='$trip_date'  and route_id ='$route_id' and status='active'  ORDER BY day ");
$select_num=mysqli_num_rows($select);
if($select_num !=0){
   	WHILE($get=mysqli_fetch_array($select)){
    	    $id2 =$get['id'];
			$arrival =$get['arrival'];
			$departure =$get['departure'];
			$time =$get['12hours'];
			$trip_day =$get['day']; 
			$bus =$get['bus']; 
			$route_id =$get['route_id']; 
			
			$now_time = date('h:i A', time());

									$select1730=mysqli_query($db,"SELECT *  FROM  vehicle Where name='$bus' ");
$select_num1730=mysqli_num_rows($select1730);
if($select_num1730 !=0){
	   	WHILE($get13730=mysqli_fetch_array($select1730)){
 
 $seats_bus= $get13730['seats'];



}
}
$select173=mysqli_query($db,"SELECT *  FROM  manifest Where bus='$bus' and trip_date='$date_now'");
$select_num173=mysqli_num_rows($select173);
if($select_num173 !=0){
	   	WHILE($get1373=mysqli_fetch_array($select173)){
 
 $seats= $get1373['seats'];


}
}else{
$seats= $seats_bus;	
}	

?>
               <div class="">
			           <div class="card-body">
                                                                <div class="d-flex">
                                                                    <div class="flex-grow-1 me-2 flex-wrap">
                                                                        <h5 class="font-size-15 mb-1"><a href="projects-overview.html" class="text-dark"><?php echo "$date_now2 ($trip_day)";?> -  <?php echo number_format($fare_price,2)?> Tsh</a></h5>
                                                                        <p class="text-muted mb-0">
													
														  <p class="text-muted">
														<?php echo $bus;?> - <?php echo base64_decode($detail);?>
														  </p>
																		</p>
                                                                    </div>
                                                           
                                                                </div>
                                                            </div>
                                                                <div class="row g-0">
                                                                    <div class="col-xl-3 col-sm-6">
                                                                        <div class="border p-3 h-100">
                                                                            <div>
                                                                                <p class="text-muted font-size-13 mb-2">Departure time</p>
                                                                                <div class="badge badge-soft-primary font-size-12"><?php echo $departure;?> </div>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                    <div class="col-xl-3 col-sm-6">
                                                                        <div class="border p-3 h-100">
                                                                            <div>
                                                                                <p class="text-muted font-size-13 mb-2">Arrival time</p>
                                                                                <h5 class="font-size-14 mb-0"><?php echo $arrival;?></h5>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                    <div class="col-xl-3 col-sm-6">
                                                                         <div class="border p-3 h-100">
                                                                            <div>
                                                                                <p class="text-muted font-size-13 mb-2">Available seats</p>
                                                                                					   <div class="flex-grow-1"style="width:200px;">
                                                          <div class="badge bg-warning font-size-12"><?php  if($seats == $seats_bus){
															  echo $seats_bus;
															  }else{
																  echo $seats_bus-$seats;
																  }?> seats available</div>
                                                            <div class="progress animated-progess custom-progress">
                                                                <div class="progress-bar" role="progressbar" style="width:	<?php
																$seats_bus;
																$seats_remain= $seats_bus- $seats;
																$new_width = ($seats_remain / 100) * $seats_bus;
																$percent = 100-round($new_width,2);
																echo "$percent%";?>" aria-valuenow="<?php echo "$percent%";?>" aria-valuemin="<?php echo "$percent%";?>" aria-valuemax="100"></div>
                                                            </div>
                                                        </div>
													
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                    <div class="col-xl-3 col-sm-6">
                                                                        <div class="border p-3 h-100">
                                                                         <div>
             
                                    <button type="button" class="btn btn-outline-primary"id="choose<?php echo $id2;?>">Choose Vehicle</button>
                                                                            </div>
                                                                        </div>
                                                                    </div><!-- end col -->
                                                                </div><!-- end row -->
                                                            </div>
															
																						<script>
														$(document).ready(function(){
															var id="<?php echo $id2;?>";
															trip_date = "<?php echo $date_now?>";
															route_id = "<?php echo $route_id?>";
														
												$("#choose<?php echo $id2;?>").click(function(){
													trip_date_new_one=$("#trip_date_new_one").text("<?php echo $date_now?>");	
														route_day_id_new_one=$("#route_day_id_new_one").text(id);	
	                                                    route_id_new_one=$("#route_id_new_one").text("<?php echo $route_id?>");
$("#load_new_seats").load("load_new_seats.php?route_day_id="+id+"&route_id="+route_id+"&trip_date="+trip_date+"");
$("#load_new_seats_chart").load("load_new_seats_chart.php?route_day_id="+id+"&route_id="+route_id+"&trip_date="+trip_date+"");
$("#passenger_list").text("<?php echo $current_date;?> - Seat chart");
$("#passenger_chart").text("<?php echo $current_date;?> - Seat chart");
												});					
														});
											
														</script>
																<?php
	}
	
}else{
	?>
	
	      <div class="text-center py-5">
                            <div class="mb-4 mb-md-5">
                                    <div class="avatar-lg mx-auto">
                                        <div class="avatar-title bg-light text-primary display-5 rounded-circle">
                                            <i class="uil uil-annoyed-alt "></i>
                                        </div>
                                    </div>
                                </div>
                                <div class="text-muted">
                                    <h4 class="">Sorry No Trip found!</h4>
                                    <p>No trip found at <span class="fw-semibold">at this time</span></p>
                                
                                </div> 
                            </div>
	<?php
}
?>
      