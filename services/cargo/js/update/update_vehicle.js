$(document).ready(function(){


$("#save_update").click(function(){
var id_no = $("#id_no").val();

var vehicle = $("#vehicle_name_update").val();
var driver = $("#driver_name_update").val();
var conductor = $("#conductor_name_update").val();

if(vehicle=="") {
				 swal("", "Please enter vehicle name", "warning");

			 control.disabled="disabled";
}
if(driver=="") {
				 swal("", "Please enter Drivers name", "warning");

			 control.disabled="disabled";
}
if(conductor=="") {
				 swal("", "Please enter Conductors name", "warning");

			 control.disabled="disabled";
}

$("#save_update").text("updating...");
  $.ajax({
				url:"update/vehicle.php",
				method:"POST",
				data:{vehicle:vehicle,driver:driver,conductor:conductor,id_no:id_no},
				success:function(response){
				$("#save_update").text("Change");

                            		$("#example").DataTable().ajax.reload();
                                     swal("update!", "you have update", "success");

	}
			
			});



});




});