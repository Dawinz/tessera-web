$(document).ready(function(){
	function myFunction(month,year,route) {
				
                        $.ajax(
                            {
                                url: 'search/job_card_details.php',
                                method: 'POST',
                                data: {
								   month:month,
								   year:year,
								   route:route
                                },
								  beforeSend: function(){

            
            },
                                success: function (data) {
									

					$("#load_vehicle_account").html(data);

                                },
                                dataType: 'text'
                            }
                        );
                    
			}
		 $('#current_route').change(function(){
route = $(this).val();
year = $("#currency_year").val();
month = $("#month").text();


					myFunction(month,year,route) ;
		
 });
$('#currency_year').change(function(){
year = $(this).val();
route = $("#current_route").val();
month = $("#month").text();

myFunction(month,year,route) ;
		
 });

$(".tab-link").click(function(){
month = $(this).text();
year = $("#currency_year").val();
route = $("#current_route").val();


myFunction(month,year,route) ;
	
	
});

});