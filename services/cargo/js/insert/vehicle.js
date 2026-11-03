$(document).ready(function(){


$("#search").click(function(){
var vehicle = $("#vehicle").val();
var driver = $("#driver").val();
var conductor = $("#conductor").val();

if(vehicle=="") {
				 swal("", "Please enter vehicle!", "warning");

			 control.disabled="disabled";
}
if(driver=="") {
				 swal("", "Please enter Driver's name!", "warning");

			 control.disabled="disabled";
}
if(conductor=="") {
				 swal("", "Please enter conductor's name!", "warning");

			 control.disabled="disabled";
}

$("#search").text("saving...");
 $.ajax({
				url:"insert/vehicle.php",
				method:"POST",
				data:{vehicle:vehicle,driver:driver,conductor:conductor},
				success:function(response){
                    $("#search").text("Register")
				var msg = $.trim(response);
				if(msg === "" || msg === "succesfull" || msg === "successful"){
               swal("Vehicle added!", "you have add vehicle", "success");
               		$("#example").DataTable().ajax.reload();

                }else if(msg === "found"){
                  swal("Found!", "vehicle found", "warning");  
                }else{
                  swal("Could not save", msg || "Unknown error", "error");
                }

	}
			
			});


});



});