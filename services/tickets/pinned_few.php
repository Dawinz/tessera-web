<?php
session_start();

?>
<!doctype html>
<html lang="en">



    

    <!-- <body data-layout="horizontal"> -->

        <!-- Begin page -->
  

            
	

            <!-- ========== Left Sidebar Start ========== -->

            <!-- Left Sidebar End -->

            

            <!-- ============================================================== -->
            <!-- Start right Content here -->
            <!-- ============================================================== -->
		
      <div class="main-content">

        

                        <!-- start page title -->
              
                        <!-- end page title -->
	<?php
			
			  $username=$_SESSION['username'];
			?>
                        <div class="row">
                            <div class="col-xl-12">
                                <div class="p-4">
                                    <div class="verti-timeline left-timeline">
                                  
								  <?php
								  include 'connect.php';
								  		$select1=mysqli_query($db,"SELECT *  FROM  pin WHERE username='".$_SESSION['username']."' ORDER BY id DESC LIMIT 5");
                                                                      $select_num1=mysqli_num_rows($select1);
                                                                   if($select_num1 !=0){
																	     	WHILE($get=mysqli_fetch_array($select1)){
    	                                                             $id =$get['id']; 
																	 $route_id =$get['route_id']; 
																	 $route_day_id =$get['route_day_id']; 
																	 $date =$get['date'];
																	 $day =$get['day'];
																	 $date_word = date(' l (F jS  Y)', strtotime($date));
																	 
																	 
																	 									  		
		include 'connect.php';
		$username=$_SESSION['username'];
		$select14=mysqli_query($db,"SELECT *  FROM  assigned_routes WHERE route_id='$route_id'  ");
$select_num14=mysqli_num_rows($select14);
if($select_num14 !=0){
   	WHILE($get134=mysqli_fetch_array($select14)){
    	   
			$start_route =$get134['start_route'];
			$end_route =$get134['end_route'];
		
			
			$detail =$get134['detail']; 
			
}}?>
																	 
																	 
															
																  <div class="timeline-item">
																  
                                            <div class="timeline-block">
                                                <div class="timeline-box card">
                                                    <div class="card-body">
													 <div class="modal-header">
                                                        <h5 class="modal-title" id="myModalLabel"></h5>
                                                        <span id="response<?php echo $id;?>"></span><button type="button" class="btn-close" id="delete<?php echo $id;?>">

                                                        </button>
<span style="display:none;"id="id<?php echo $id?>"><?php echo $id?></span>

														<script>

$(document).ready(function(){
$("#delete<?php echo $id;?>").click(function(){
	var id = $("#id<?php echo $id?>").text();

																 $.ajax({
				url:"delete_pinned.php",
				method:"POST",
				data:{id:id},
				success:function(response){
					$("#load_pinned").load("pinned_few.php");
					$("#load_pinned_number").load("load_pinned_number.php");
				$(".response<?php echo $id;?>").text("");		
if($.trim(response) == "succesfull"){
								  swal({
  
    text: "update Succefull.",
    timer: 1000,
    showConfirmButton: false
  });

}else{
	swal("", "already saved !", "warning");

}
	}
			
			});
	
});
});
</script>
                                                    </div>
													
                                                        <div class="timeline-date"><?php echo $date_word?></div>
                                                        <h5 class="mt-3 font-size-16"> <?php echo "$start_route to $end_route" ?></h5>
 <a href="seat_selection.php?id=<?php echo $route_day_id;?>&trip_date=<?php echo $date;?>&route_id=<?php echo $route_id;?>">
             
                                    <button type="button" class="btn btn-outline-primary">Book Seats</button></a>                                                              
														
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
										
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
                                    <p>No trip Pinned <span class="fw-semibold"></span></p>
                                
                                </div> 
                            </div>
																	   <?php
																	   
																   }
								  ?>
    
                                      
                                
                          
                                        <!-- end item -->
                             
                                        <!-- end item -->
                                
                                        <!-- end item -->
                                    </div>
                                </div>
                            </div>
                            <!-- end col -->
                        </div>
                        <!-- end row -->
                        
               
  
                <!-- End Page-content -->

                
      
            </div>
            <!-- end main content-->

        <!-- END layout-wrapper -->

        <!-- Right Sidebar -->

        <!-- /Right-bar -->

   <!-- end row -->

     <!-- end row -->

              <!-- end row -->
                    </div>
                    <!-- container-fluid -->
                </div>
                <!-- End Page-content -->


             
            </div>

        <!-- JAVASCRIPT -->
     

      </script>

</html>
