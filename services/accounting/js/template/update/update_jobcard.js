$(document).ready(function(){
	
//route_name = $("#route_name_update").text();
id = $("#id_update").text();
//date = $("#date").text();
year = $(".currency_year").val();
route = $("#current_route").val();
month = $("#month").text();

 $("#update_currency").on('submit', function(e){
        e.preventDefault();
		
			count = 1;
error="";

	Vehicle_update = $("#Vehicle_update option:selected").text();
	if(Vehicle_update=="" ){
error += "<li>Fill vehicle .</li>";
	}

	item_update = $("#item_update option:selected").text();
	if(item_update=="" ){
error += "<li>Fill Item .</li>";
	}
	
	if($("#qty_update").val() == '')
			{

				error += "<li>Fill quantity.</li>";


			}
	if($("#amount_update").val() == '')
			{

				error += "<li>Fill Ammount.</li>";


			}



						if(error == '')
		{
	    $.ajax({
            type: 'POST',
            url: 'update/update_jobcard.php',
            data: new FormData(this),
           
            contentType: false,
            cache: false,
            processData:false,
            beforeSend: function(){
							    $("#update").attr("disabled", true);
	  $("#uptext").text("updating...");
            
            },
            success: function(response){
				alert(response);
		  $("#uptext").text("Save Changes");

		function myFunction(month,route,year) {
				
                        $.ajax(
                            {
                                url: 'search/job_card_details.php',
                                method: 'POST',
                                data: {
                                   id:id,
								   route:route,
								   month:month,
								   year:year
                                },
								  beforeSend: function(){

            
            },
                                success: function (data) {
									
									  $("#placeholder_veh").removeClass("placeholder");

					$("#load_vehicle_account").html(data);

                                },
                                dataType: 'text'
                            }
                        );
                    
			}
					myFunction(month,route,year);
            }
        });	
		}else{
alert(error);
		}

	
});

});