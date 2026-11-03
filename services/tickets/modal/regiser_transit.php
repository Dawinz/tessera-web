<div id="route_transit" class="modal fade bd-example-modal-lg" tabindex="-1" role="dialog"
                                            aria-labelledby="myModalLabel" aria-hidden="true">
                                            <div class="modal-dialog modal-lg">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title" id="myModalLabel"><span id="route_cnt_this">Register Contact</span></h5>
                                                        <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                            aria-label="Close">

                                                        </button>
                                                    </div>
                                                    <div class="modal-body">
                                                     
                                                      <form class="needs-validation" name="form_transit" id="form_transit" novalidate>
                                                    <div class="row">
                                                <input type="hidden" name ="route_name_cont_this" id="route_name_cont_this">
                                                <input type="hidden" name ="route_name_id_this" id="route_name_id_this">
								<select class="form-select route_transit" name="route_transit" ><option>Choose Route</option><?php echo  $route_transit;?></select>
															
														
                                                    </div>
                                               
                                               

                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-light"
                                                            data-bs-dismiss="modal">Close</button>
                                                        <button type="submit" class="btn btn-primary" id="save_insert">Register</button>
                                                    </div>
 </form>
                                                </div><!-- /.modal-content -->
                                            </div><!-- /.modal-dialog -->
                                        </div>
										
										
							