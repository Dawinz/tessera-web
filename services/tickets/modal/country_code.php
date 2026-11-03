<div id="route_country_code" class="modal fade bd-example-modal-lg" tabindex="-1" role="dialog"
                                            aria-labelledby="myModalLabel" aria-hidden="true">
                                            <div class="modal-dialog modal-lg">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title" id="myModalLabel"><span id="route_cnt_code">Register Country code</span></h5>
                                                        <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                            aria-label="Close">

                                                        </button>
                                                    </div>
                                                    <div class="modal-body">
                                                     
                                                      <form class="needs-validation" name="form_country_code" id="form_country_code" novalidate>
                                                    <div class="row">
                                              
                                                <input type="hidden" name ="route_name_id" id="code_route_name_id">
															<table class="table " id="register_table_code">
								<tr>
									<th>Code</th>
														

									<th><button type="button" name="add" class="btn btn-success btn-sm add_code "><i class="fas fa-plus"></i></button></th>
								</tr>
							</table>
							  <div class="modal-footer">
                                                        <button type="button" class="btn btn-light"
                                                            data-bs-dismiss="modal">Close</button>
                                                        <button type="submit" class="btn btn-primary" id="save_contact">Register</button>
                                                    </div>
										<div id="coutry_code_table"style="margin:10px;">
										    <div class="placeholder-glow" >
                                                        <span class="placeholder col-12"style="height:300px"></span>
                                                    </div>
										</div>					
														
                                                    </div>
                                               
                                               

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

		html += '<td> <input name="code_name[]" type="text" id="code_name"class="form-control  code_name " placeholder="+255"></td>';
		

	
		var remove_button = '';

		if(count > 0)
		{
			remove_button = '<button type="button" name="remove" class="btn btn-danger btn-sm remove2"><i class="fas fa-minus"></i></button>';
		}

		html += '<td>'+remove_button+'</td></tr>';

		return html;

	}
		$('#register_table_code').append(add_input_field_contact(0));	


$(document).on('click', '.add_code', function(){

		count++;

		$('#register_table_code').append(add_input_field_contact(count));


	});
		
			$(document).on('click', '.remove2', function(){

		$(this).closest('tr').remove();

	});			
			
								
								</script>		