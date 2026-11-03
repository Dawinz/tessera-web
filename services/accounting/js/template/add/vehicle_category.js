       $(document).ready(function(){
			
			
			var count = 0;
	
	function add_input_field(count)
	{

		var html = '';

		html += '<tr>';
		html += '<td><input class="form-control categroy" type="text" id="particular" name="categroy[]"></td>';
		html += '<td><input class="form-control detail" type="text" id="detail" name="detail[]"></td>';

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
		  
	
		  