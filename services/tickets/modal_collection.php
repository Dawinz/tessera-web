
 <div class="modal fade bs-example-modal-xl2" tabindex="-1" role="dialog"
                                            aria-labelledby="myExtraLargeModalLabel" aria-hidden="true">
                                            <div class="modal-dialog modal-xl">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title" id="myExtraLargeModalLabel">Transfer <span id="t_seat_name"></span> - <span id="t_no_seat"></span></h5>
                                                        <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                            aria-label="Close"></button>
                                                    </div>
                                                    <div class="modal-body">
                                                        
														<table>
						  <tr>
						  <td class="table_padding" >



<select class=" td_with picker_select form-select action" id="trip_start_modal"style="width:300px;" >

  <option>loading.....</option>
 
</select>
						 </td> 
<td class="table_padding" >
					   
<select class=" td_with picker_select form-select " id="select_bus_modal" >

   <option>loading.....</option>
</select>
</td><td class="table_padding" >
 <input class="form-control dateselect" type="text"  id="starting_date_select" style="caret-color: transparent;margin-bottom:4px;cursor: pointer;"placeholder="mm/dd/yyyy" value ="<?php echo $tomorrowmo;?>" onkeydown="return false">

</td><td class="table_padding" >
<button type="button" class="btn btn-soft-light" id="search_trip"><span class="loading_tck">Search<span></button>
</td>

 

						  </tr>
						  </table>
					  <div class="alert alert-danger alert-top-border alert-dismissible fade show" role="alert">
                                                <i class="uil uil-exclamation-octagon font-size-16 text-danger me-2"></i>
                                                Seat number <span class="seat_name_class"></span> has been selected ,Just click an empty seat to Complete transfer 
                                                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                                            </div>

													<div class="row">
													<div class="col-sm-4">
													
				
						  
                                                <div class="rotated" id="rotate_div">
											
												
												<div id="load_class">
												  <div class="placeholder-glow">
                                                        <span class="placeholder col-12"style="width:350px;height:600px;"></span>
                                                    </div>
												
												</div>
												</div>
												</div>
												<div class="col-sm-8">
<div class="row">
<div class="col">
<select class="form-select" id="trip_start_tranit" name="trip_start_tranit" ><option>loading....</option></select>
</div>
<div class="col">
<select class="form-select" id="trip_end_tranit" name="trip_end_tranit" ><option>loading....</option></select>
</div>
</div>
								
												</div>
													</div>
                                                    </div>
													
													<div id="ticket_no_original"class="display_none"></div>
                                                </div><!-- /.modal-content -->
                                            </div><!-- /.modal-dialog -->
                                        </div>
										 <div id="myModal_edit" class="modal fade" tabindex="-1" role="dialog"
                                            aria-labelledby="myModalLabel" aria-hidden="true">
                                            <div class="modal-dialog">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title" id="myModalLabel">Seat no</h5>
                                                        <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                            aria-label="Close">

                                                        </button>
                                                    </div>
                                                    <div class="modal-body">
                                                     <div id="first_model_area">
													
													</div>
                                            <div id="second_model_area"> 
													 </div>
                                         

                                                    </div>
                                           

                                                </div><!-- /.modal-content -->
                                            </div><!-- /.modal-dialog -->
                                        </div>	

										<div id="myModal_delete" class="modal fade" tabindex="-1" role="dialog"
                                            aria-labelledby="myModalLabel" aria-hidden="true">
                                            <div class="modal-dialog">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title" id="myModalLabel">Seat no <span id="seat_no_resonse"></span></h5>
                                                        <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                            aria-label="Close">

                                                        </button>
                                                    </div>
                                                    <div class="modal-body">
                                                         <div class="form-floating">
                                            <textarea class="form-control" placeholder="Leave a comment here" id="delete_resonse"
                                                style="height: 100px"></textarea>
                                            <label for="lock_reason">State Your Reasons here..</label>
                                        </div>
                                                    </div>
                                            <div class="modal-footer">
                                                        <button type="button" class="btn btn-light"
                                                            data-bs-dismiss="modal">Close</button>
                                                        <button type="button" id="delete_ticket"class="btn btn-primary "><span id="delete_text">Save changes</span></button>
                                                    </div>

                                                </div><!-- /.modal-content -->
                                            </div><!-- /.modal-dialog -->
                                        </div>
										
										
										    <div class="modal fade bs-example-modal-xl_view" tabindex="-1" role="dialog"
                                            aria-labelledby="myExtraLargeModalLabel" aria-hidden="true">
                                            <div class="modal-dialog modal-xl">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title" id="myExtraLargeModalLabel">View ticket info</h5>
                                                        <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                            aria-label="Close"></button>
                                                    </div>
                                                    <div class="modal-body">
                                                       <div id="Load_ticket_info">
													
													</div>
                                                    </div>
                                                </div><!-- /.modal-content -->
                                            </div><!-- /.modal-dialog -->
                                        </div>
										
										
										      <div class="modal fade bs-example-modal-xl_manifest" tabindex="-1" role="dialog"
                                            aria-labelledby="myExtraLargeModalLabel" aria-hidden="true">
                                            <div class="modal-dialog modal-xl">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title" id="myExtraLargeModalLabel"><span class="bus_name"></span> - <span class="trip_date_text"></span></h5>
                                                        <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                            aria-label="Close"></button>
                                                    </div>
                                                    <div class="modal-body">
                                                    
													   <div id="carouselExampleIndicators_manifest" class="carousel slide" data-bs-ride="false" >
                              
                                    <div class="carousel-inner" role="listbox">
                                        <div class="carousel-item active">
                                        <div id="manifest_place_holder">
													      <div class="placeholder-glow" >
                                                        <span class="placeholder col-12"style="height:300px"></span>
                                                    </div>
													   </div>
                                        </div>
                                        <div class="carousel-item">
	<button type="button" class="btn btn-soft-primary" id="modal_prev_manifest"><i class="uil uil-arrow-left"></i>Back</button><br>
<div class="response_m_d"></div>
                                                <!------ Manfest transfer start------->
					<table>
					  <tr>
						  <td class="table_padding" >



<select class=" td_with picker_select form-select action" id="trip_start_modal_manifest"style="width:300px;" >

  <option>loading.....</option>
 
</select>
						 </td> 
<td class="table_padding" >
					   
<select class=" td_with picker_select form-select " id="select_bus_modal_manifest" >

   <option>loading.....</option>
</select>
</td><td class="table_padding" >
 <input class="form-control dateselect" type="text"  id="starting_date_select_manifest" style="caret-color: transparent;margin-bottom:4px;cursor: pointer;"placeholder="mm/dd/yyyy" value ="<?php echo $tomorrowmo;?>" onkeydown="return false">

</td><td class="table_padding" >
<button type="button" class="btn btn-soft-light" id="search_trip_manifest_new"><span class="loading_tck">Search<span></button>
</td>

 

						  </tr>
						  </table>
						    <div class="alert alert-danger alert-top-border alert-dismissible fade show" role="alert">
                                                
                                             <span class="holder" ><span class="seat_name_class_manifset"></span></span> 
                                                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                                            </div>
						  
						  	<div class="row">
							

													<div class="col-sm-4">
											
												<div id="load_class_manifest">
												  <div class="placeholder-glow">
                                                        <span class="placeholder col-12"style="width:350px;height:600px;"></span>
                                                    </div>
												
												</div>
												
												</div>
												<div class="col-sm-8">
						<div class="row">		
						<div class="col">	
<select class="form-select" id="trip_start_tranit_manifest" name="trip_start_tranit_manifest" >
<option>loading......</option>
  
 
</select>
		</div>				
		<div class="col">			   
<select class="form-select" id="trip_end_tranit_manifest" name="trip_end_tranit_manifest" >

   
</select>
</div>
</div><br>

											    <div id="load_user_t_content_manifest"style=" " >
												    <div class="alert alert-warning border-0 d-flex align-items-center" role="alert">
                                        <i class="uil uil-exclamation-triangle font-size-16 text-warning me-2"></i>
                                        <div class="flex-grow-1 text-truncate">
                                        No any seat transfered
                                        </div>
                                    
                                    </div>
												  
												</div>
												</div>
													</div>
						  
						  
						  
                                        </div>
                                     
                                    </div>
                                 
                                </div>  
                                                    </div>
                                                </div><!-- /.modal-content -->
                                            </div><!-- /.modal-dialog -->
                                        </div>
										
										
											 <div id="myModal_resverd" class="modal fade" tabindex="-1" role="dialog"
                                            aria-labelledby="myModalLabel" aria-hidden="true">
                                            <div class="modal-dialog">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title" id="myModalLabel">Confirm Reservation</h5>
                                                        <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                            aria-label="Close">

                                                        </button>
                                                    </div>
                                                    <div class="modal-body">
                                                 <div class="alert alert-warning alert-top-border alert-dismissible fade show" role="alert">
                                               
                                             <span class="seat_name_class_list"></span> - You are about to complete Reservation
                                                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                                            </div>
                                          
                                         <button type="button" class="btn btn-soft-success" id="complete_reservation">Complete</button>

                                                    </div>
                                           

                                                </div><!-- /.modal-content -->
                                            </div><!-- /.modal-dialog -->
                                        </div>	

										<div id="myModal_delete_manifest" class="modal fade" tabindex="-1" role="dialog"
                                            aria-labelledby="myModalLabel" aria-hidden="true">
                                            <div class="modal-dialog">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title" id="myModalLabel">Delete selected Manifest</h5>
                                                        <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                            aria-label="Close">

                                                        </button>
                                                    </div>
                                                    <div class="modal-body">
                                                 <div class="alert alert-danger alert-top-border alert-dismissible fade show" role="alert">
                                               
                                              You are about to delete  <span class="seat_name_class_list"></span>
                                                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                                            </div>
                                          
										     <div class="form-floating">
                                            <textarea class="form-control resoons_to_delete" placeholder="Leave a comment here" id="floatingTextarea2"
                                                style="height: 100px"></textarea>
                                            <label for="floatingTextarea2">Resoans</label>
                                        </div><br>
										  
                                         <button type="button" class="btn btn-soft-success" id="cancel_reservation"><span id="delete_text">Complete</span></button>

                                                    </div>
                                           

                                                </div><!-- /.modal-content -->
                                            </div><!-- /.modal-dialog -->
                                        </div>
										<div class="modal fade exampleModalFullscreen_seat" tabindex="-1"
                                            aria-labelledby="exampleModalFullscreenLabel" aria-hidden="true">
                                            <div class="modal-dialog modal-fullscreen">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title" id="exampleModalFullscreenLabel">Print reciepts</h5>
                                                        <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                            aria-label="Close"></button>
                                                    </div>
                                                    <div class="modal-body">
                                                        
<div id="container_print_seat"style="background-color:white;"></div>                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-secondary"
                                                            data-bs-dismiss="modal">Close</button>
                                                   
                                                    </div>
                                                </div><!-- /.modal-content -->
                                            </div><!-- /.modal-dialog -->
                                        </div>
										
											<script src="tra_modal_select.js"></script>
											<script src="tra_modal_route.js"></script>
											<script src="tra_modal_select_manifest.js"></script>
											<script src="select_transfer_route.js"></script>
											<script src="select_transfer_route_manifest.js"></script>
											<script src="select_transfer_route_manifest_new.js"></script>
											<script src="delete_ticket.js"></script>
<script>
$(function () {
  if (!$.fn.modal) {
    return;
  }
  $('.bs-example-modal-xl_manifest').modal({
    backdrop: 'static',
    keyboard: false
  });
  $("#carouselExampleIndicators_manifest").carousel({
    interval: false
  });
});
</script>

