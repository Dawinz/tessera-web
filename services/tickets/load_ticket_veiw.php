<?php
$id= $_GET['id_update'];

?>

     <?php
	 $route_id = $_GET['route_update'];

?>       
<?php
include 'connect.php';
require_once __DIR__ . '/includes/tessera_tenant.php';
$select1=mysqli_query($db,"SELECT *  FROM  booking WHERE id='$id' AND " . tessera_sql_tenant_eq() . " ");
$select_num1=mysqli_num_rows($select1);
if($select_num1 !=0){
   	WHILE($get13=mysqli_fetch_array($select1)){
 $seat_no =$get13['seat_no']; 
 $cost =$get13['cost']; 
 $id= $get13['id']; 
 $trip_date= $get13['trip_date']; 
 $status= $get13['status'];
 $firstname_name= $get13['firstname'];
 $lastname= $get13['lastname'];
 $phone_no= $get13['phone_no'];
$validation= $get13['validation'];
$transit= $get13['transit'];
$gender= $get13['gender'];
$age_group= $get13['age_group'];
$reason= $get13['reason'];
 $start_location= $get13['start_location']; 
 $ending_location= $get13['ending_location'];
 $route_id = $get13['route_id'];
 $sub_route_id = $get13['sub_route_id'];
 $route_day = $get13['route_day_id'];
 $bus = $get13['bus'];
 $reason = $get13['reason'];
 $booker = $get13['booker'];
 $start_route = $get13['start_route'];
 $end_route = $get13['end_route'];
 $book_date = $get13['book_date'];
 $book_time = $get13['book_time'];
 $amount_paid = $get13['amount_paid'];
 $amount_left = $get13['amount_left'];
 $amount_status = $get13['amount_status'];
}}
$select2=mysqli_query($db,"SELECT *  FROM  users  where username='$booker'");
$select_num2=mysqli_num_rows($select2);
if($select_num2 !=0){
   	WHILE($get12=mysqli_fetch_array($select2)){
    	    $firstname =$get12['firstname'];  
			$surname =$get12['surname'];
			$firstStringCharacter = substr($surname, 0, 1);
			$fullname= "$firstname $surname";
			$level =$get12['role']; 
$image = $get12['image']; 

		
	}
}

$select_route_day=mysqli_query($db,"SELECT *  FROM  route_day WHERE route_id='$route_id' ");
$select_num_route_day=mysqli_num_rows($select_route_day);
if($select_num_route_day !=0){
   	WHILE($get_route_day=mysqli_fetch_array($select_route_day)){
    	
			$arrival =$get_route_day['arrival'];
			$departure =$get_route_day['departure'];
			
		
	
} 
}
$select14=mysqli_query($db,"SELECT *  FROM  routes WHERE route_id='$route_id' ");
$select_num14=mysqli_num_rows($select14);
if($select_num14 !=0){
   	WHILE($get134=mysqli_fetch_array($select14)){
    	
			$start_route =$get134['start_route'];
			$end_route =$get134['end_route'];
		
		$fare_price =$get134['fare_price']; 
		$main_route_no =$get134['route_id']; 
		$detail =base64_decode($get134['detail']); 
	
} 
}

?>

     
            <div class="card border shadow-none">
                                                            <div class="card-body">
                                                                <div class="d-flex">
                                                                    <div class="flex-grow-1 me-2 flex-wrap">
                                                                        <h5 class="font-size-15 mb-1"><span class="text-dark"><?php echo "$firstname_name $lastname";?></span></h5>
                                                                        <p class="text-muted mb-0"><?php echo $detail;?></p>
                                                                    </div>
                            
                                                                </div>
                                                            </div>
                                                            <!-- end card body -->
                                                            <div class="">
                                                                <div class="row g-0">
                                                                    <div class="col-xl-3 col-sm-6">
                                                                        <div class="border p-3 h-100">
                                                                            <div>
                                                                                <p class="text-muted font-size-13 mb-2">Ticket number</p>
        <h5 class="font-size-14 mb-0"><?php echo "$id";?></h5>                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                    <div class="col-xl-3 col-sm-6">
                                                                        <div class="border p-3 h-100">
                                                                            <div>
                                                                                <p class="text-muted font-size-13 mb-2">Seat number</p>
                                                                                <h5 class="font-size-14 mb-0"><?php echo $seat_no;?></h5>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                    <div class="col-xl-3 col-sm-6">
                                                                        <div class="border p-3 h-100">
                                                                            <div>
                                                                                <p class="text-muted font-size-13 mb-2">Phone Number</p>
                                                                                <h5 class="font-size-14 mb-0"><?php echo $phone_no;?></h5>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                    <div class="col-xl-3 col-sm-6">
                                                                        <div class="border p-3 h-100">
                                                                       <div>
                                                                                <p class="text-muted font-size-13 mb-2">Age Group</p>
                                                                                <h5 class="font-size-14 mb-0"><?php echo $age_group;?></h5>
                                                                            </div>
                                                                        </div>
                                                                    </div><!-- end col -->
                                                                </div><!-- end row -->
                                                            </div>           <div class="">
                                                                <div class="row g-0">
                                                                    <div class="col-xl-3 col-sm-6">
                                                                        <div class="border p-3 h-100">
                                                                            <div>
                                                                                <p class="text-muted font-size-13 mb-2">Verification</p>
                                                                          <h5 class="font-size-14 mb-0"><?php if($validation==""){echo "Nill";}else{echo $validation;}?></h5>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                    <div class="col-xl-3 col-sm-6">
                                                                        <div class="border p-3 h-100">
                                                                            <div>
                                                                                <p class="text-muted font-size-13 mb-2">Transit</p>
                                                                          <h5 class="font-size-14 mb-0"><?php if($transit==""){echo "Nill";}else{echo $transit;}?></h5>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                    <div class="col-xl-3 col-sm-6">
                                                                        <div class="border p-3 h-100">
                                                                            <div>
                                                                                <p class="text-muted font-size-13 mb-2">FOC</p>
                                                                          <h5 class="font-size-14 mb-0"><?php if($reason==""){echo "Nill";}else{echo "Foc";}?></h5>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                    <div class="col-xl-3 col-sm-6">
                                                                        <div class="border p-3 h-100">
                                                                     <div>
                                                                                <p class="text-muted font-size-13 mb-2">Booked By</p>
                                                                          <h5 class="font-size-14 mb-0"><?php echo "$booker ($fullname)";?></h5>
                                                                            </div>
                                                                        </div>
                                                                    </div><!-- end col -->
                                                                </div><!-- end row -->
                                                            </div>
                                                        </div>   <div class="card border shadow-none">
                                                            <div class="card-body">
                                                                <div class="d-flex">
                                                                    <div class="flex-grow-1 me-2 flex-wrap">
                                                                        <h5 class="font-size-15 mb-1"><span class="text-dark">Trip Info</span></h5>
                                                                       
                                                                    </div>
                            
                                                                </div>
                                                            </div>
                                                            <!-- end card body -->
                                                            <div class="">
                                                                <div class="row g-0">
                                                                    <div class="col-xl-3 col-sm-6">
                                                                        <div class="border p-3 h-100">
                                                                            <div>
                                                                                <p class="text-muted font-size-13 mb-2">Start Route</p>
        <h5 class="font-size-14 mb-0"><?php echo "$start_route";?></h5>                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                    <div class="col-xl-3 col-sm-6">
                                                                        <div class="border p-3 h-100">
                                                                            <div>
                                                                                <p class="text-muted font-size-13 mb-2">End route</p>
                                                                                <h5 class="font-size-14 mb-0"><?php echo $end_route;?></h5>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                    <div class="col-xl-3 col-sm-6">
                                                                        <div class="border p-3 h-100">
                                                                            <div>
                                                                                <p class="text-muted font-size-13 mb-2">Board Point</p>
                                                                                <h5 class="font-size-14 mb-0"><?php echo $start_location;?></h5>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                    <div class="col-xl-3 col-sm-6">
                                                                        <div class="border p-3 h-100">
                                                                       <div>
                                                                                <p class="text-muted font-size-13 mb-2">End Point</p>
                                                                                <h5 class="font-size-14 mb-0"><?php echo $ending_location;?></h5>
                                                                            </div>
                                                                        </div>
                                                                    </div><!-- end col -->
                                                                </div><!-- end row -->
                                                            </div>           <div class="">
                                                                <div class="row g-0">
                                                                    <div class="col-xl-3 col-sm-6">
                                                                        <div class="border p-3 h-100">
                                                                            <div>
                                                                                <p class="text-muted font-size-13 mb-2">Deperture</p>
                                                                          <h5 class="font-size-14 mb-0"><?php echo $departure;?></h5>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                    <div class="col-xl-3 col-sm-6">
                                                                        <div class="border p-3 h-100">
                                                                            <div>
                                                                                <p class="text-muted font-size-13 mb-2">Arrival</p>
                                                                          <h5 class="font-size-14 mb-0"><?php echo $arrival;?></h5>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                    <div class="col-xl-3 col-sm-6">
                                                                        <div class="border p-3 h-100">
                                                                            <div>
                                                                                <p class="text-muted font-size-13 mb-2">Bus</p>
                                                                          <h5 class="font-size-14 mb-0"><?php echo $bus;?></h5>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                    <div class="col-xl-3 col-sm-6">
                                                                        <div class="border p-3 h-100">
                                                                     <div>
                                                                                <p class="text-muted font-size-13 mb-2">Trip date</p>
                                                                          <h5 class="font-size-14 mb-0"><?php echo $trip_date;?></h5>
                                                                            </div>
                                                                        </div>
                                                                    </div><!-- end col -->
                                                                </div><!-- end row -->
                                                            </div>
                                                        </div><div class="card border shadow-none">
                                                            <div class="card-body">
                                                                <div class="d-flex">
                                                                    <div class="flex-grow-1 me-2 flex-wrap">
                                                                        <h5 class="font-size-15 mb-1"><span class="text-dark">Payment Info</span></h5>
                                                                       
                                                                    </div>
                            
                                                                </div>
                                                            </div>
                                                            <!-- end card body -->
                                                            <div class="">
                                                                <div class="row g-0">
                                                                    <div class="col-xl-3 col-sm-6">
                                                                        <div class="border p-3 h-100">
                                                                            <div>
                                                                                <p class="text-muted font-size-13 mb-2">Booked Date</p>
        <h5 class="font-size-14 mb-0"><?php echo "$book_date at $book_time";?></h5>                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                    <div class="col-xl-3 col-sm-6">
                                                                        <div class="border p-3 h-100">
                                                                            <div>
                                                                                <p class="text-muted font-size-13 mb-2">Payment</p>
                                                                                <h5 class="font-size-14 mb-0">Cash</h5>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                    <div class="col-xl-3 col-sm-6">
                                                                        <div class="border p-3 h-100">
                                                                            <div>
                                                                                <p class="text-muted font-size-13 mb-2">Payment status</p>
                                                                                <h5 class="font-size-14 mb-0">
																				<?php if($status=="booked"){
																					echo "Reserved";
																					}else if($status=="complete"){ 
																					if($amount_status =="yes"){
																						echo "<span style='color:#F34E4E;'>No Complete  - (".number_format($amount_left,2).") left</span>";
																					}else{
																						echo "Complete";
																					}
																					}else if($status=="half_complete"){ 
																					if($amount_status =="yes"){
																						echo "<span style='color:#F34E4E;'>No Complete  - (".number_format($amount_left,2).") left</span>";
																					}else{
																						echo "Complete";
																					}
																					}?></h5>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                    <div class="col-xl-3 col-sm-6">
                                                                        <div class="border p-3 h-100">
                                                                       <div>
                                                                                <p class="text-muted font-size-13 mb-2">Ammount</p>
                                                                                <h5 class="font-size-14 mb-0"><?php echo number_format($cost,2);?></h5>
                                                                            </div>
                                                                        </div>
                                                                    </div><!-- end col -->
                                                                </div><!-- end row -->
                                                            </div>           <div class="">
                                                          <!-- end row -->
                                                            </div>
                                                        </div>