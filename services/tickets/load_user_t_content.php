<?php
require_once __DIR__ . '/includes/tessera_staff_boot.php';
$id = $_GET['id'];
include 'connect.php';
$select1=mysqli_query($db,"SELECT *  FROM  booking WHERE " . tessera_sql_tenant_eq() . " AND (id='$id')");
$select_num1=mysqli_num_rows($select1);
if($select_num1 !=0){
   	WHILE($get13=mysqli_fetch_array($select1)){
 
 $id= $get13['id']; 
 $seat_no= $get13['id']; 
 $firstname= $get13['firstname']; 
 $lastname= $get13['lastname'];
 $start_route= $get13['start_route'];
 $end_route= $get13['end_route']; 
 $seat_no= $get13['seat_no']; 
 $route_day_id= $get13['route_day_id'];
 $bus= $get13['bus'];
 $trip_date= $get13['trip_date'];
 $book_date= $get13['book_date'];
 $book_time= $get13['book_time'];


}}

?>
					 <div class="card border shadow-none">
                                                            <div class="card-body">
                                                                <div class="d-flex">
                                                                    <div class="flex-grow-1 me-2 flex-wrap">
                                                                        <h5 class="font-size-15 mb-1"><a href="projects-overview.html" class="text-dark"><?php echo "$firstname $lastname"?></a></h5>
                                                                        <p class="text-muted mb-0"><?php echo "From $start_route to $end_route";?> </p>
                                                                    </div>
                             
                                                                </div>
                                                            </div>
                                                            <!-- end card body -->
                                                            <div class="">
                                                                <div class="row g-0">
                                                                    <div class="col-xl-3 col-sm-6">
                                                                        <div class="border p-3 h-100">
                                                                            <div>
                                                                                <p class="text-muted font-size-13 mb-2">Seat info</p>
                                                                                <div class="badge badge-soft-danger font-size-14"><?php echo "$seat_no - $id";?></div>
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
                                                                                <h5 class="mb-2 font-size-14"><?php echo date('jS F Y', strtotime($trip_date));?></h5>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                    <div class="col-xl-3 col-sm-6">
                                                                    <div class="border p-3 h-100">
                                                                            <div>
                                                                                <p class="text-muted font-size-13 mb-2">Booked Date</p>
                                                                                <h5 class=" font-size-14 mb-0"><?php echo ''.date('jS F Y', strtotime($book_date)).'<br> '.$book_time.'';?></h5>
                                                                            </div>
                                                                        </div>
                                                                    </div><!-- end col -->
                                                                </div><!-- end row -->
                                                            </div>
                                                        </div>