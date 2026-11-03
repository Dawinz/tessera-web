$(document).ready(function() {
	
			var count = 0;
	
	function add_input_field(count)
	{

		var html = '';

		html += '<tr>';
		html += '<td><select name="category[]" id="category" class="form-select  category" style="" data-live-search="true"><option value="Choose Account">Choose Account</option><option value="Income">Income</option><option value="Expenses">Expenses</option></select></td>';
		html += '<td><select name="group[]" id="group" class="form-select  group" style="" data-live-search="true"><option class="group">Choose Group</option><option>Vehicle</option><option>Garage</option><option>Office</option></select></td>';

		html += '<td><input class="form-control" type="text" id="particular" name="particular[]"></td>';

		var remove_button = '';

		if(count > 0)
		{
			remove_button = '<button type="button" name="remove" class="btn btn-danger btn-sm remove"><i class="fas fa-minus"></i></button>';
		}

		html += '<td>'+remove_button+'</td></tr>';

		return html;

	}
	
	   $("#signup").on('submit', function(e){
        e.preventDefault();
	count = 1;
error="";

	particular = $("#particular").val();
	if(particular==""){
error += "<li>Fill Particular category.</li>";
	}
		category = $("#category option:selected").text();
	if(category=="Choose Account"){
					error += "<li>Fill category.</li>";

	}
		group = $("#group option:selected").text();
	if(group=="Choose Group"){
					error += "<li>Fill group.</li>";

	}
					if(error == '')
		{
			
	
	  $.ajax({
            type: 'POST',
            url: 'insert/particular_category.php',
            data: new FormData(this),
           
            contentType: false,
            cache: false,
            processData:false,
            beforeSend: function(){
		$("#save").attr("disabled", true);
	  $("#save").text("Saving..");
            
            },
            success: function(response){
							$("#example1").DataTable().ajax.reload();

$("#save").notify(
 "Saved Successfull",
 "success",
  { position:"right" }
);
	
									    $("#save").attr("disabled", false);
										$("#save").text("Save Changes");
										
										$('#item_table').find('tr:gt(0)').remove();


						$('#item_table').append(add_input_field(0));
					
            }
        });			
			
		}else{
Swal.fire({
  title: "warning",
  html: error,
  icon: "warning"
});		}
	});
});