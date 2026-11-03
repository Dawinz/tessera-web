$(document).ready(function(){
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
            	$("#load_daily_account").html('<div class="text-center py-5"><div class="spinner-border text-primary" role="status"><span class="visually-hidden">Loading</span></div></div>');
            },
                                success: function (data) {
					if (window.srMountAjaxHtml) {
						window.srMountAjaxHtml('load_daily_account', data);
					} else {
						$("#load_daily_account").html(data);
					}
                                },
                                dataType: 'text'
                            }
                        );
                    
			}

$(".tab-link").click(function(e){
e.preventDefault();
$(".tab-link").removeClass("active");
$(this).addClass("active");
month = $.trim($(this).text());
$("#month").text(month);
year = $("#currency_year").val();
route = $("#current_route").val();

myFunction(month,year,route) ;
});

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

});
