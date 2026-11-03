		                           <div id="myModal" class="modal fade bs-example-modal-xl" tabindex="-1" role="dialog"
                                            aria-labelledby="myModalLabel" aria-hidden="true">
                                            <div class="modal-dialog modal-fullscreen">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title" id="myModalLabel">Register Route</h5>
                                                        <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                            aria-label="Close">

                                                        </button>
                                                    </div>
                                                    <div class="modal-body">
										    <div class="btn-group me-2 mb-2 mb-sm-0">
   <div class="mb-4">
                                    <button type="button" id="show" class="btn btn-soft-success"><i class="uil uil-plus "></i>Add route location</button>

                                            </div>
                                            </div>   
                                           <span id="route_add_area" style="display:none;">
											<div class="btn-group me-2 mb-2 mb-sm-0">
   <div class="mb-4">

   <input class="form-control" type="text" value="" id="l_name">
                                            </div>
                                            </div>   
											<div class="btn-group me-2 mb-2 mb-sm-0">
   <div class="mb-4">
  
                                    <button type="button" id="save_location" class="btn btn-soft-success">Save</button>

                                            </div>
                                            </div>   
											</span>
                                                                                <form class="needs-validation" name="event-form" id="insert_route" novalidate>
                           
       <table class="table " id="register_table">
								<tr>
									<th>Route type</th>
									<th>Starting point</th>
									<th>Ending point</th>
									<th>Currency</th>
									<th>Fair</th>
									<th>initial name</th>	
									<th>Active Days</th>
									<th>Detail</th>
									<th><button type="button" name="add" class="btn btn-success btn-sm add "><i class="fas fa-plus"></i></button></th>
								</tr>
							</table>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-light"
                                                            data-bs-dismiss="modal">Close</button>
                                                        <button type="submit" class="btn btn-primary" id="search">Register</button>
                                                    </div>
</form>

                                                    </div>
                                                </div><!-- /.modal-content -->
                                            </div><!-- /.modal-dialog -->
                                        </div>
														<script>
$(document).ready(function () {
			var count = 0;
	
	function add_input_field(count)
	{

		var html = '';

		html += '<tr>';

		html += '<td ><div class=""><div class=""><select  style="width:150px;" class="form-select route_type selectpicker" id="route_type'+count+'" name="route_type[]"id="route_type"aria-label="Floating label select example"><option value="Choose Type">Choose Type</option><option value="Main Route">Main Route</option><option value="Sub Route">Sub Route</option></select></div><div class=" show_route'+count+'" style="display:none;"><select style="width:150px; margin-top:5px;" class="form-select" name="route_name[]"id="route_name'+count+'"aria-label="Floating label select example"><option value="Route name">Route name</option><?php echo $route_name;?></select></div></div></td>';
		html += '<td> <select class="form-select selectpicker" style="width:150px;" name="starting_point[]"id="starting_point"aria-label="Floating label select example"><option value="Choose Starting Point">Starting Point</option><?php echo $start_route2; ?></select></td>';
		html += '<td> <select class="form-select selectpicker" style="width:150px;"name="end_point[]"aria-label="Floating label select example"><option value="End Point">Choose End Point</option><?php echo $start_route2; ?></select></td>';
		html += '<td> <select class="form-select selectpicker" style="width:150px;" name ="Currnecy[]"id="Currnecy"aria-label="Floating label select example"><option value="Choose Currnecy">Choose Currnecy</option><?php echo $curerncy; ?></select></td>';
		html += '<td><input name="price[]" type="text" name="price[]"id="qty"class="form-control  amount price" placeholder="Fair"></td>';
		html += '<td><input name="Initial[]" type="text" id="Initial"class="form-control   Initial" placeholder="Initial"></td>';
		html += '<td><input name="days[]" type="text" id="days"class="form-control days" placeholder="Days"></td>';
		html += '<td><input name="detail[]" type="text" id="detail"class="form-control   qty" placeholder="Detail"></td>';

	
		var remove_button = '';

		if(count > 0)
		{
			remove_button = '<button type="button" name="remove" class="btn btn-danger btn-sm remove_route"><i class="fas fa-minus"></i></button>';
		}

		html += '<td>'+remove_button+'</td></tr>';

		return html;

	}
		$('#register_table').append(add_input_field(0));	
		if ($.fn.select2) {
			$('#register_table .selectpicker').select2({ dropdownParent: $('#myModal') });
		}


$(document).on('click', '.add', function(){

		count++;

		$('#register_table').append(add_input_field(count));

		$('#register_table .selectpicker').select2({
			dropdownParent: $('#myModal')
		});

	});
				$(document).on('click', '.remove_route', function(){

		$(this).closest('tr').remove();

	});		

	$(document).on('change', '.route_type', function(){

	my_class = $(this).attr("id").replace(/[^0-9]/gi,'');
route_type = $(this).val();
if(route_type =="Main Route"){
$(".show_route"+my_class+"").hide();
}else{
	$(".show_route"+my_class+"").show();
	
}
	});
});
						</script>