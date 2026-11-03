<?php
	 	 $starting_location = $_GET['starting_location'];
$ending_location = $_GET['ending_location'];
$start_location = preg_replace('/_/',' ', $starting_location);
$ending_location = preg_replace('/_/',' ', $ending_location);
	  
$date= $_GET['starting_date'];

	  include 'connect.php';
	  




	




$select145=mysqli_query($db,"SELECT *  FROM  sub_route  WHERE start_route='$start_location' and end_route='$ending_location' ");
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

		$trip_date = date('l', strtotime($date));
		
		$currency_symbol =$get1345['currency_symbol'];


										
		include 'connect.php';
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
				$select173=mysqli_query($db,"SELECT *  FROM  manifest Where bus='$bus' and trip_date='$date'");
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
												   <td>vghfhf
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
                                                    <td><?php echo $id2;?></td>
													</tr>

												
											

<?php
}}}}else{
	?>
		<tr>
	<td></td>
	<td><img src="images/location2.png"></td>
	
	<td><span >Sorry no trip found from </span><span ><span style="color:white;"><?php echo $start_location;?></span>  on date  <span style="color:white;"><?php echo date('l jS\ M-Y', strtotime($date))?></td>
	<td></td>
	<td>   </td><td></td><td></td><td></td><td></td><td></td>
	</tr>
	
	<?php
	
}?>      


                                      
                                                <!-- end /tr -->
                                               
                                                <!-- end /tr -->

