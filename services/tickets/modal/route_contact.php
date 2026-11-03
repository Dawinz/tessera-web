<div id="route_contact" class="modal fade bd-example-modal-lg" tabindex="-1" role="dialog"
                                            aria-labelledby="myModalLabel" aria-hidden="true">
                                            <div class="modal-dialog modal-lg">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title" id="myModalLabel"><span id="route_cnt">Register Contact</span></h5>
                                                        <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                            aria-label="Close">

                                                        </button>
                                                    </div>
                                                    <div class="modal-body">
                                                     
                                                    <div class="row">
                                               <form id="rgform">
                                                <input type="hidden" name ="route_name_cont_prev" id="route_name_cont">
                                                <input type="hidden" name ="route_name_id" id="route_name_id">
															<table class="table " id="register_table_contact">
								<tr>
									<th>Name</th>
									<th>Contact</th>
														

									<th><button type="button" name="add" class="btn btn-success btn-sm add_contact "><i class="fas fa-plus"></i></button></th>
								</tr>
							</table>
													<div id="contact_table"></div>		
														
                                                    </div>
                                               
                                               

                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-light"
                                                            data-bs-dismiss="modal">Close</button>
                                                        <button type="submit" class="btn btn-primary" id="save">Register</button>
                                                    </div>
</form> 
                                                </div><!-- /.modal-content -->
                                            </div><!-- /.modal-dialog -->
                                        </div>
										
										
								<script>
								
																			
										
										
			var count = 0;
	
	function add_input_field_contact(count)
	{

		var html = '';

		html += '<tr>';

		html += '<td> <input name="route_name_cont[]" type="text" id="detail"class="form-control  route_name_cont" placeholder="Name"></td>';
		html += '<td> <input name="contact_name[]" type="text" id="detail"class="form-control  contact_name " placeholder="Contact"></td>';
		

	
		var remove_button = '';

		if(count > 0)
		{
			remove_button = '<button type="button" name="remove" class="btn btn-danger btn-sm remove"><i class="fas fa-minus"></i></button>';
		}

		html += '<td>'+remove_button+'</td></tr>';

		return html;

	}
		$('#register_table_contact').append(add_input_field_contact(0));	


$(document).on('click', '.add_contact', function(){

		count++;

		$('#register_table_contact').append(add_input_field_contact(count));

		$('.selectpicker').selectpicker('refresh');

	});
		
			
			
								
								</script>		