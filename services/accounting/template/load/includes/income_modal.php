	<script>
		$(document).ready(function(){
 
						var count = 0;
	
	function add_input_field(count)
	{
new_count = count+1;
		var html = '';

		html += '<tr>';

		html += '<td>'+new_count +'</td>';
		html += '<td><select name="part_Category[]" class="form-select part_Category" id="part_Category'+count+'" style="" data-live-search="true"><option value="Choose Category">Choose Category</option><?php echo $particular_category; ?></select></td>';
		html += '<td><select name="part_detail[]" class="form-select  part_detail" id="part_detail'+count+'" style="" data-live-search="true"><option value="Choose Particular">Choose Particular</option></select></td>';
		html += '<td><select name="part_Currency[]" class="form-select  particular_currency" id="particular_currency" style="" data-live-search="true"><?php echo $particular_Currency;?></select></td>';

		html += '<td><input name="amount[]" type="text" class="form-control value_name amount amount_income" ></td>';
		html += '<td><input name="detail[]" type="text" class="form-control value_name detail" ></td>';

		var remove_button = '';

		if(count > 0)
		{
			remove_button = '<button type="button" name="remove" class="btn btn-danger btn-sm remove_exp"><i class="fas fa-minus"></i></button>';
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

	})	
	
	
	
	});
	</script>