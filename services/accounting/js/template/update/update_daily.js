$(document).ready(function(){
		month = $("#month").text();
	year = $(".currency_year").val();
	route = $(".current_route").val();
	function myFunction(month,year,route) {
				
                        $.ajax(
                            {
                                url: 'search/daily.php',
                                method: 'POST',
                                data: {
								   month:month,
								   year:year,
								   route:route
                                },
								  beforeSend: function(){

            
            },
                                success: function (data) {
									

					$("#load_daily_account").html(data);

                                },
                                dataType: 'text'
                            }
                        );
                    
			}
 $("#update_daily").on('submit', function(e){
        e.preventDefault();
		
			count = 1;
error="";

expenses = $("#expenses_update option:selected").text();
	if(expenses=="Select Particular" ){
error += "<li>Fill Particulars .</li>";
	}
amount_update = $("#amount_update").val();
	if(amount_update=="" ){
error += "<li>Fill Ammount .</li>";
	}

					if(error == '')
		{
	    $.ajax({
            type: 'POST',
            url: 'update/update_daily.php',
            data: new FormData(this),
           
            contentType: false,
            cache: false,
            processData:false,
            beforeSend: function(){
							    $("#save").attr("disabled", true);
	  $("#text_change").text("updating...");
            
            },
            success: function(response){
								alert(response);

myFunction(month,year,route);
            }
        });		
		}else{
			
			
			
			
		}

});});