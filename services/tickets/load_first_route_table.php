<?php
require_once __DIR__ . '/includes/tessera_staff_boot.php';
if(isset($_GET['date'])){
@$date = $_GET['date'];
$date_now = date("m/d/Y", strtotime($date));
  $tomorrow2 =  date("m/d/Y", strtotime($date_now."+1 days"));
  $today_date_word = date('l', strtotime($tomorrow2));


}else{
date_default_timezone_set('Africa/Dar_es_Salaam');
$date_today = date('m/d/Y', time());
$today_date_word = date('l', strtotime($date_today));

$tomorrowmo =  date("m/d/Y", strtotime($date_today."+1 days"));
}
date_default_timezone_set('Africa/Dar_es_Salaam');
				  $tomorrow = date("m/d/Y", strtotime("+1 day")); 
				  $day_after = date("m/d/Y", strtotime("+2 day")); 
				  $day_2 = date("m/d/Y", strtotime("+3 day"));
				  $day = date("m/d/Y");
				  $tommorw_day = date('l', strtotime($tomorrow));
				  $today_day = date('l', strtotime($day));
				  $tommorw = date('F l Y', strtotime($tomorrow));
				  $day_aft = date('F l Y', strtotime($day_after));
				  $day_23 = date('F l Y', strtotime($day_2));
				  $trip_date_tomorrow = date('l', strtotime($tomorrow));
?>
		
                                           
												  		<?php
														include 'connect.php';
	$select=mysqli_query($db,"SELECT *  FROM  route_day where   day='$trip_date_tomorrow'   and status='active' ORDER BY day ");
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

			if($trip_day == $today_day){
				$trip_date = $day;
			
			}	else{
				$trip_date = $tomorrow;
				
			}
			
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
 <button type="button" class="btn btn-outline-primary">Book </button>                                                      </td>

                                                    <td>
                                                 <div class="square-switch">
                                                            <input type="checkbox" id="square-switch3" switch="bool"  />
                                                            <label for="square-switch3" data-on-label="Open "
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
                                                                <li><a href="#" class="dropdown-item"><i
                                                                            class="mdi mdi-pencil font-size-16 text-success me-1"></i>
                                                                        Manifest</a></li>
                                                                <li><a href="#" class="dropdown-item"><i
                                                                            class="mdi mdi-trash-can font-size-16 text-danger me-1"></i>
                                                                        Seat Chart Manifest</a></li><li><a href="#" class="dropdown-item"><i
                                                                            class="mdi mdi-trash-can font-size-16 text-danger me-1"></i>
                                                                        Edit Bus</a></li><li><a href="#" class="dropdown-item"><i
                                                                            class="mdi mdi-trash-can font-size-16 text-danger me-1"></i>
                                                                        Charge Summary</a></li>
                                                            </ul>
                                                        </div>
                                                    </td>
                                                    
													</tr>
													
<?php }} ?>
													
                                                
                                                <!-- end /tr -->
                                               
                                                <!-- end /tr -->

