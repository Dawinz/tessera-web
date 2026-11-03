$(document).ready(function(){


$("#save_changes").click(function(){
var route_name = $("#route_name_update option:selected" ).text();
var day_of_week = $("#day_of_week_update option:selected" ).text();
var vehicle_name = $("#vehicle_name_update option:selected" ).text();
var d_ptime = $("#d_ptime_update").val();
var a_time = $("#a_time_update").val();
var detail = $("#detail_update").val();

var id_no = $("#id_no").text();
var bus_prev = $("#bus_prev").text();


if(route_name=="Choose Route Name") {
				 swal("", "Please Route Name!", "warning");

			 control.disabled="disabled";
}

if(day_of_week=="Choose Trip Days") {
				 swal("", "Please Trip Day !", "warning");

			 control.disabled="disabled";
}

if(vehicle_name=="Choose Vehicle Name") {
				 swal("", "Please Vehicle name!", "warning");

			 control.disabled="disabled";
}




$("#save_changes").text("registering...");
 $.ajax({
				url:"update_trip_date.php",
				method:"POST",
				data:{id_no:id_no,route_name:route_name,day_of_week:day_of_week,vehicle_name:vehicle_name,d_ptime:d_ptime,a_time:a_time,detail:detail},
				
				
				success:function(response){
					
					$("#save_changes").text("Registering");
$("#example").DataTable().ajax.reload();
if($.trim(response) == "update"){
$('.bs-example-modal-sm').modal('show');
$("#check_bus").text("loading");
$("#check_bus").load("check_bus.php?bus_prev="+bus_prev+"&bus="+vehicle_name);

}else{
								  swal({
  
    text: "update Succefull.",
    timer: 1000,
    showConfirmButton: false
  });

}
$("#example").DataTable().ajax.reload();

	}
			
			});

});

$("#check_update").click(function(){
if ($('#check_update').prop('checked')) {
    //blah blah
swal("Activated", "You have activate The vehicle it will seen on list of active vehicle!", "warning");

$("#check_update").val("on");
}else{
		
		$("#check").val("");
}
});

});