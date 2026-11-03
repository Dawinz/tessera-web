	$(document).ready(function(){
		

	
$('#insert_form').on('submit', function(event){

		event.preventDefault();

		var error = '';

		count = 1;

		$('.firstname').each(function(){

			if($(this).val() == '')
			{
				error += "<li>Please Enter firstname at "+count+" Row</li>";
          $(this).css('border','1px solid #043A5A');	

			}

			count = count + 1;

		});

		count = 1;

		$('.phone_no').each(function(){

			if($(this).val() == '')
			{

				error += "<li>Please Enter Phone number at "+count+" Row</li>";


			}

			count = count + 1;

		});




		if(error == '')
		{
$('#modal_check').modal('show');


		}
		else
		{
			$('#error').html('<div class="alert alert-danger"><ul>'+error+'</ul></div>');
		}

		var form_data = $(this).serialize();

		if(error == '')
		{

			$.ajax({

				url:"insert_multiple_passengers.php",

				method:"POST",

				data:form_data,

				beforeSend:function()
	    		{

	    			$('#submit_button').attr('disabled', 'disabled');

	    		},

				success:function(data)
				{

			alert(data);
				}
			})

		}
		else
		{
			$('#error').html('<div class="alert alert-danger"><ul>'+error+'</ul></div>');
		}



	});
	

});
	