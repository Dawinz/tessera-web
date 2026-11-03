$(document).ready(function(){
	 $("#update_form").on('submit', function(e){
        e.preventDefault();
		
			count = 1;
error="";
	part_id = $(".part_id").val();

			if($(".part_detail_update").val() == 'Choose Particular')
			{

				error += "<li>Fill particular name.</li>";


			}	
			if($(".amount_income_update").val() == '')
			{

				error += "<li>Value is empty.</li>";


			}
				if(error == '')
		{
		    $.ajax({
            type: 'POST',
            url: 'update/vehicle_account.php',
            data: new FormData(this),
           
            contentType: false,
            cache: false,
            processData:false,
            beforeSend: function(){
							    $("#update").attr("disabled", true);
	  $("#update").text("updating...");
                        	$("#load_vehicle_account").html("<p class='placeholder-glow' ><span class='placeholder col-12' style='height:550px'></span></p>"); 

            },
            success: function(response){
				//alert(response);
myFunction(month,year,route,trip_date,bus,point,start,route_name,currency_value,currency,currency_sign,level) ;

            }
        });	
		}else{

		}
});


});