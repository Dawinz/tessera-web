$(document).ready(function(){

$("#search").click(function(){

var vehicle_name = $("#Vehicle_name").val();
var driver_name = $("#driver_name").val();
var conductor_name = $("#conductor_name").val();
var seat_no = $("#seat_no").val();
var seat_layout = $("#seat_layout").val() || "";
var detail = $("#detail").val() || "";
var check = $("#check").prop("checked") ? "on" : "";

if(vehicle_name=="") {
	swal("", "Please Vehicle name!", "warning");
	return;
}

if(driver_name=="") {
	swal("", "Please Drivers name!", "warning");
	return;
}

if(conductor_name=="") {
	swal("", "Please Conductor's name!", "warning");
	return;
}

if(seat_no=="") {
	swal("", "Please Seat no!", "warning");
	return;
}

$("#search").text("registering...");
$("#search").attr("disabled", true);
$.ajax({
	url:"save_vehicle.php",
	method:"POST",
	data:{
		vehicle_name:vehicle_name,
		driver_name:driver_name,
		conductor_name:conductor_name,
		seat_no:seat_no,
		seat_layout:seat_layout,
		detail:detail,
		check:check
	},
	success:function(response){
		var msg = $.trim(response);
		$("#search").attr("disabled", false);
		$("#search").text("Register");

		if(msg == "succesfull"){
			swal({
				text: "Vehicle registered.",
				timer: 1200,
				showConfirmButton: false
			});
			$("#rgform").trigger("reset");
			$("#check").prop("checked", true);
			$("#myModal").modal("hide");
			if ($.fn.DataTable.isDataTable("#example")) {
				$("#example").DataTable().ajax.reload(null, false);
			}
		} else if (msg == "found") {
			swal("Found", "Vehicle already exists!", "warning");
		} else if (msg == "invalid") {
			swal("", "Please fill all required fields.", "warning");
		} else {
			swal("", "Could not register vehicle. Please try again.", "error");
		}
	},
	error:function(){
		$("#search").attr("disabled", false);
		$("#search").text("Register");
		swal("", "Could not register vehicle. Please try again.", "error");
	}
});

});

$("#check").on("change", function(){
	if ($(this).prop("checked")) {
		swal("Activated", "This vehicle will appear as active.", "warning");
	}
});

});
