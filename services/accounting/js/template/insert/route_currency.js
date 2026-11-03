$(document).ready(function(){
	$(document).on('click', '.remove', function(){

		$(this).closest('tr').remove();

	});

$("#add_currency").click(function(event){
	


		event.preventDefault();

		
		
		var error = '';
			count = 1;

		$("select[name='route_name[]']").each(function(){

			if($(this).val() == '')
			{

				error += "<li>Add Currency on "+count+" Row</li>";

			}

			count = count + 1;

		});


	if(error == '')
		{
var form_data = $("#insert_form").serialize();
	$.ajax({

				url:"insert/insert_route_currency.php",

				method:"POST",

				data:form_data,

				beforeSend:function()
	    		{

	    			$('#add_currency').attr('disabled', 'disabled');
		  $("#add_currency").text("Saving...");

	    		},

				success:function(data)
				{
alert(data);
$("#add_currency").notify(
 "Saved Successfull",
 "success",
  { position:"right" }
);
		  $("#add_currency").text("Save changes");
									    $("#add_currency").attr("disabled", false);

$("#example1").DataTable().ajax.reload();

				}
			})

		}else{
			
		}


	});
});