$(document).ready(function(){


$("#change_date").click(function(){
var vehicle = $("#vehicle_name_update_date option:selected" ).text();
var bus_route = $("#bus_route").text();
var trip_date_change = $("#trip_date_change").text();
var idRoute = $("#idRoute").text();
$("#ti_response2").text("changing....");


 $.ajax({
				url:"update_route_date.php",
				method:"POST",
				data:{vehicle:vehicle,bus_route:bus_route,trip_date_change:trip_date_change,idRoute:idRoute},
				success:function(response){
			$("#ti_response2").text("");
			$('#myModal2').modal('hide');
			$("#bus_name_to_change").text(vehicle);

	

	}
	
			
			});

});


});