
<div id="modaldemo1" class="modal " tabindex="-1">
      <div class="modal-dialog modal-lg" role="document" >
        <div class="modal-content modal-content-demo">
          <div class="modal-header">
            <h6 class="modal-title">Add Vehicle</h6>
            <button type="button" class="close " data-bs-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
 <form id="insert_form" enctype="multipart/form-data">

          <div class="modal-body">
            
 <div class="row row-sm">
            <div class="col-lg">
              <br>

	<table class="table table-bordered" id="item_table">
								<tr>
									
																		<th>Category</th>

									<th>Vehicle</th>
									
									<th><button type="button" name="add" class="btn btn-success btn-sm add "><i class="fas fa-plus"></i></button></th>
								</tr>
							</table>
			  </div><!-- col --> 
	
 
			
			
			<!-- col -->
          <!-- col -->
          </div>         

 
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


		html += '<td><select name="category_name[]" id="category_name" class="form-select  category_name" style="" data-live-search="true"><option class="">Choose Category</option><?php echo $category_name; ?></select></td>';
		html += '<td><select name="vehicles[]" id="vehicles" class="form-select  vehicles" style="" data-live-search="true"><option class="">Choose Vehicle</option><?php echo $name; ?></select></td>';

		var remove_button = '';

		if(count > 0)
		{
			remove_button = '<button type="button" name="remove" class="btn btn-danger btn-sm remove"><i class="fas fa-minus"></i></button>';
		}

		html += '<td>'+remove_button+'</td></tr>';

		return html;

	}
		$('#item_table').append(add_input_field(0));	
			
			
	$(document).on('click', '.add_new', function(){

		count++;

		$('#item_table').append(add_input_field(count));

		$('.selectpicker').selectpicker('refresh');

	});
			$(document).on('click', '.remove', function(){

		$(this).closest('tr').remove();

	});	
			
      
          });
		  
	
		  
	
    </script>