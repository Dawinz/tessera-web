$(document).ready(function(){


$("#change_btn").click(function(){

var vehicle_name = $("#Vehicle_name_update").val();
var driver_name = $("#driver_name_update").val();
var conductor_name = $("#conductor_name_update").val();
var seat_no = $("#seat_no_update").val();
var detail = $("#detail_update").val();
var check = $("#check_update").val();
var id_no = $("#id_no").text();

if(vehicle_name=="") {
				 swal("", "Please Vehicle name!", "warning");

			 control.disabled="disabled";
}

if(driver_name=="") {
				 swal("", "Please Drivers name!", "warning");

			 control.disabled="disabled";
}

if(conductor_name=="") {
				 swal("", "Please Drivers name!", "warning");

			 control.disabled="disabled";
}

if(seat_no=="") {
				 swal("", "Please Seat no!", "warning");

			 control.disabled="disabled";
}

if(detail=="") {
				 swal("", "Please Detail !", "warning");

			 control.disabled="disabled";
}
$("#search").text("registering...");

 $.ajax({
				url:"update_vehicle.php",
				method:"POST",
				data:{id_no:id_no,vehicle_name:vehicle_name,driver_name:driver_name,conductor_name:conductor_name,seat_no:seat_no,detail:detail,check:check},
				success:function(response){
															$("#example").DataTable().ajax.reload();

if($.trim(response) == "succesfull"){
					
							  swal({
  
    text: "update Succefull.",
    timer: 1000,
    showConfirmButton: false
  });
  $("#search").text("Register");
						}else{
							swal("Found", "Vehicle found !", "warning");
						}
	}
			
			});

});

$("#check_update").click(function(){
if ($('#check_update').prop('checked')) {
    //blah blah
swal("Activated", "You have activate The vehicle it will seen on list of active vehicle!", "warning");

$("#check_update").val("on");
}else{
		
		$("#check_update").val("");
}
});

});