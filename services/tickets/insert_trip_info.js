$(document).ready(function(){


$("#search").click(function(){
var route_name = $("#route_name option:selected" ).text();
var day_of_week = $("#day_of_week option:selected" ).text();
var vehicle_name = $("#vehicle_name option:selected" ).text();
var d_ptime = $("#d_ptime").val();
var a_time = $("#a_time").val();
var detail = $("#detail").val();
var check = $("#check").val();


if(route_name=="Choose Route Name") {
Swal.fire("Please Route Name!");
			 control.disabled="disabled";
}

if(day_of_week=="Choose Trip Days") {
Swal.fire("Trip date!");
			 control.disabled="disabled";
			 
}

if(vehicle_name=="Choose Vehicle Name") {
				 Swal.fire("Vehicle Name!");

			 control.disabled="disabled";
}






$("#search").text("registering...");
 $("#search").attr("disabled", true);
$.ajax({
				url:"insert_trip_date_info.php",
				method:"POST",
				data:{route_name:route_name,day_of_week:day_of_week,vehicle_name:vehicle_name,d_ptime:d_ptime,a_time:a_time,detail:detail,check:check},
				success:function(response){
					alert(response)
					 $("#rgform").trigger("reset");
  $("#rgform").trigger("reset");
$("#form-event").trigger("reset");
$("#form-event").trigger("reset");
					 $("#search").attr("disabled", false);

if($.trim(response) == "succesfull"){
					
Swal.fire({
  title: "Insert Succeful",

   icon: "success"
});

  $("#search").text("Register");

						}else{
							 Swal.fire("Found!");
							 $("#search").text("Register");
						}
													$("#example").DataTable().ajax.reload();

						}

			});
});

$("#check").click(function(){
if ($('#check').prop('checked')) {
    //blah blah
swal("Activated", "You have activate The vehicle it will seen on list of active vehicle!", "warning");

$("#check").val("on");
}else{
		
		$("#check").val("");
}
});

});