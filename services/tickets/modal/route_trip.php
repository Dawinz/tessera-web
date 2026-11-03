   <div id="myModal" class="modal fade" tabindex="-1" role="dialog"
                                            aria-labelledby="myModalLabel" aria-hidden="true">
                                            <div class="modal-dialog modal-xl">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title" id="myModalLabel">Register Trip</h5>
                                                        <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                            aria-label="Close"></button>
                                                    </div>
                                                    <div class="modal-body">
  <form class="needs-validation" name="event-form" id="insert_trip_all" novalidate>
       <table class="table " id="register_table">
								<tr>
									<th>Trip Day</th>
									<th>Route Name</th>
									<th>Bus</th>
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
			var count = 0;
	
	function add_input_field(count)
	{

		var html = '';

		html += '<tr>';

		html += '<td ><select  style="width:300px;" class="form-select day_of_week selectpicker day_of_week" data-live-search="true" id="day_of_week" name="day_of_week[]">	<option value="Choose Trip Days">Choose Trip Days</option><option>Monday</option><option>Tuesday</option><option>Wednesday</option> <option>Thursday</option><option>Friday</option> <option>Saturday</option>  <option>Sunday</option></select></td>';
		html += '<td ><select  style="width:300px;" margin-top:5px;" class="form-select selectpicker" name="route_name[]"id="route_name"aria-label="Floating label select example"><option value="Choose Route Name">Choose Route Name</option><?php echo $route_name;?></select></td>';
		html += '<td ><select  style="width:200px;"margin-top:5px;" class="form-select selectpicker" name="vehicle_name[]"id="vehicle_name"aria-label="Floating label select example"><option value="Choose Vehicle Name">Choose Vehicle Name</option><?php echo $vehicle;?></select></td>';
		html += '<td><input name="detail[]" type="text" id="days"class="form-control detial" placeholder="Detail"></td>';

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

		     $('.selectpicker').select2({
        dropdownParent: $('#myModal')
    });
	});
				$(document).on('click', '.remove_route', function(){

		$(this).closest('tr').remove();

	});		


	
						</script>
