	<script>
		$(document).ready(function(){
 
					var count_exp = 0;
	
	function add_input_field_exp(count_exp)
	{
new_count_exp = count_exp+1;
		var html = '';

		html += '<tr>';

		html += '<td>'+new_count_exp +'</td>';
		html += '<td><select name="part_Category_exp[]" class="form-select part_Category_exp" id="part_Category_exp'+count_exp+'" style="" data-live-search="true"><option value="Choose Category">Choose Category</option><?php echo $particular_category_exp; ?></select></td>';
		html += '<td><select name="part_detail_exp[]" class="form-select  part_detail_exp" id="part_detail_exp'+count_exp+'" style="" data-live-search="true"><option value="Choose Particular">Choose Particular</option></select></td>';
		html += '<td><select name="part_Currency_exp[]" class="form-select  particular_currency_exp" id="particular_currency_exp" style="" data-live-search="true"><?php echo $particular_Currency;?></select></td>';

		html += '<td><input name="amount_exp[]" type="text" class="form-control value_name amount amount_exp" ></td>';
		html += '<td><input name="detail_exp[]" type="text" class="form-control value_name detail_exp" ></td>';

		var remove_button = '';

		if(count_exp > 0)
		{
			remove_button = '<button type="button" name="remove_exp" class="btn btn-danger btn-sm remove_exp"><i class="fas fa-minus"></i></button>';
		}

		html += '<td>'+remove_button+'</td></tr>';

		return html;

	}
		$('#item_table_exp').append(add_input_field_exp(0));	

$(document).on('click', '.add_exp', function(){
count_exp++;

		$('#item_table_exp').append(add_input_field_exp(count_exp));

		$('.selectpicker').selectpicker('refresh');
	});

		$(document).on('click', '.remove_exp', function(){

		$(this).closest('tr').remove();

	})	
	});
	</script>