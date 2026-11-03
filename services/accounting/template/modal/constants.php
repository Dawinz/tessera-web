<style>
.modal-dialog {
    max-width: 100%;
    margin: 0;
    top: 0;
    bottom: 0;
    left: 0;
    right: 0;
    height: 100vh;
    display: flex;
}
</style>
  <div id="modaldemo1" class="modal " tabindex="-1"  >
      <div class="modal-dialog modal-xl" role="document"  >
        <div class="modal-content modal-content-demo center"  >
          <div class="modal-header">
            <h6 class="modal-title">Add Route</h6>
            <button type="button" class="close " data-bs-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
	<form id="signup" enctype="multipart/form-data">

          <div class="modal-body">

	<table class="table table-bordered" id="item_table">
								<tr>
									<th>Catgory</th>
									<th>Name</th>
									<th>Route</th>
									<th>Route Category</th>
									<th>Currency</th>	
									
									
									
									
									<th>Affect</th>
																	
									<th>Vehicle</th>
																		<th>Value</th>

									<th><button type="button" name="add" class="btn btn-success btn-sm add "><i class="fas fa-plus"></i></button></th>
								</tr>
							</table>

		  </div>
          <div class="modal-footer">
            <button type="submit"class="btn btn-indigo" id="save">Save changes</button>
            <button type="button" class="btn btn-outline-light" data-bs-dismiss="modal">Close</button>
          </div>
		  </form>
        </div>
      </div><!-- modal-dialog -->
    </div>
	   <script>
        $(document).ready(function(){
			
			
			var count = 0;
	
	function add_input_field(count)
	{

		var html = '';

		html += '<tr>';

		html += '<td><select name="part_Category[]" class="form-select part_Category" id="part_Category'+count+'" style="" data-live-search="true"><option value="Choose Category">Choose Category</option><?php echo $name_category; ?></select></td>';
		html += '<td><select name="name[]" class="form-select part_detail'+count+'" id="particular_name" style="" data-live-search="true"><option value="Choose particular">Choose particular</option></select></td>';
		html += '<td><select name="route_name[]" class="form-select  " id="choose_route" style="" data-live-search="true"><option value="Choose route name"> Choose route name</option><?php echo $route_full_name; ?></select></td>';
		html += '<td><select class="form-control select2" id="route_category" style="width: 100%;" name="route_category[]"><option value="Route Category">Route Category</option><option value="Main">Main Route</option><option value="Sub Route">Sub Route</option></select></td>';
		html += '<td><select name="currency[]" class="form-select  " id="currency_v" style="" data-live-search="true"><option value="Choose Currency">Choose Currency</option><?php echo $name_account; ?></select></td>';
		html += '<td><select name="deduction[]" class="form-select  " id="affect" style="" data-live-search="true"><option value="Choose Affect Area">Choose Affect Area</option><option value="To be Paid">To be Paid</option><option value="Paid">Paid</option></select></td>';
		html += '<td><select name="vehilce_category[]" class="form-select  " id="vehilce_category" style="" data-live-search="true"><option value="Choose Vehicle">Choose Vehicle</option><option>Bus</option><option>Truck</option></select></td>';
		html += '<td><input name="value[]" type="text" class="form-control value_name amount" ></td>';

		var remove_button = '';

		if(count > 0)
		{
			remove_button = '<button type="button" name="remove" class="btn btn-danger btn-sm remove"><i class="fas fa-minus"></i></button>';
		}

		html += '<td>'+remove_button+'</td></tr>';

		return html;

	}
		$('#item_table').append(add_input_field(0));	
			
			
	$(document).on('click', '.add', function(){

		count++;

		$('#item_table').append(add_input_field(count));

		$('.selectpicker').selectpicker('refresh');

	});
			$(document).on('click', '.remove', function(){

		$(this).closest('tr').remove();

	});	
			
          $('.select2').select2({
            
            searchInputPlaceholder: 'Search',
			dropdownParent: $("#modaldemo1")
          });
          });
		  
			$(document).on('change','.part_Category', function(){
my_id = $(this).attr('id')
my_id = my_id.replace(/\D/g,'');
part_category = $("#part_Category"+my_id+" option:selected" ).text();
    $.ajax({
    url:"./dependence/fetch_income.php",
    method:"POST",
    data:{part_category:part_category},
	   beforeSend: function(){
	$(".part_detail"+my_id+" option:selected" ).text("loading...");
            
            },
    success:function(data){
$('.part_detail'+my_id+'').html(data);
    }
   })
});
	

    </script>