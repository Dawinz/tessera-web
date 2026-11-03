$(document).ready(function(){
route_name = $("#route_name_update").text();
id = $("#id_update").text();
date = $("#date_update").text();
	
	
$("#delete_all").click(function(){
selected_checked = $("#selected_checked").text();
$("#delete_text").text("Deleting...");
		$(".check_buttons").show("fast" );

 $.ajax({
    url:"delete/delete_mult_jobcard.php",
    method:"POST",
    data:{selected_checked:selected_checked},
    success:function(response){
		$("#delete_text").text("Delete");

 
	   alert(response);

	function myFunction(id,route_name,date) {
				
                        $.ajax(
                            {
                                url: 'search/job_card_details.php',
                                method: 'POST',
                                data: {
                                   id:id,
								   route_name:route_name,
								   date:date
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
					myFunction(id,route_name,date);

}


}); 

});
});