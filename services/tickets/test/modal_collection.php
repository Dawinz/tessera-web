
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



<select class=" td_with picker_select form-select action" id="trip_start_modal" >

  <option>loading.....</option>
 
</select>
						 </td>  <td class="table_padding" >
					   
<select class="td_with picker_select form-select action2" id="trip_end_modal" >

   <option>loading.....</option>
</select>
</td><td class="table_padding" >
					   
<select class=" td_with picker_select form-select " id="select_bus_modal" >

   <option>loading.....</option>
</select>
</td><td class="table_padding" >
 <input class="form-control dateselect" type="text"  id="starting_date_select" style="caret-color: transparent;margin-bottom:4px;cursor: pointer;"placeholder="mm/dd/yyyy" value ="<?php echo $tomorrowmo;?>" onkeydown="return false">

</td><td class="table_padding" >
<button type="button" class="btn btn-soft-light" id="search_trip">Search</button>
</td>

 

						  </tr>
						  </table>
					  <div class="alert alert-danger alert-top-border alert-dismissible fade show" role="alert">
                                                <i class="uil uil-exclamation-octagon font-size-16 text-danger me-2"></i>
                                                Seat number <span class="seat_name_class"></span> has been selected ,Just click an empty seat to Complete transfer 
                                                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                                            </div>

													<div class="row">
													<div class="col">
													
				
						  
                                                <div class="rotated" id="rotate_div">
												
												
												<div id="load_class">
												  <div class="placeholder-glow">
                                                        <span class="placeholder col-12"style="width:400px;height:600px;"></span>
                                                    </div>
												
												</div>
												</div>
												</div>
												<div class="col">
											    <div class="load_t_content">
												  <div class="placeholder-glow">
                                                        <span class="placeholder col-12"style="width:600px;height:200px;"></span>
                                                    </div>
												</div>
												</div>
													</div>
                                                    </div>
													
													<div id="ticket_no_original"class="display_none"></div>
                                                </div><!-- /.modal-content -->
                                            </div><!-- /.modal-dialog -->
                                        </div>
										
											<script src="tra_modal_select.js"></script>
											<script src="select_transfer_route.js"></script>


