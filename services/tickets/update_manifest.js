$(document).ready(function(){


$("#save_changes").click(function(){
var vehicle = $("#vehicle option:selected" ).text();

var drivers_name = $("#drivers_name").val();
var conductor_update = $("#conductor_update").val();
var id_no = $("#id_no").text();
var route_day_id = $("#route_day_id").text();
var trip_date = $("#trip_date").text();
var bus = $("#bus").text();




if(drivers_name=="") {
				 if (window.TesseraStates) {
					TesseraStates.toastWarn('Driver required', 'Enter the driver name.');
				 } else {
					swal("", "Please Enter Drivers Name !", "warning");
				 }
			 control.disabled="disabled";
}
if(conductor_update=="") {
				 if (window.TesseraStates) {
					TesseraStates.toastWarn('Conductor required', 'Enter the conductor name.');
				 } else {
					swal("", "Please Enter Conductors !", "warning");
				 }
			 control.disabled="disabled";
}


$("#save_changes").text("registering...");
swal({
  title: "Are you sure?",
  text: "Once Change, it will affect all tickets on the manifest,and will change the vehicle name in route lists!",
  icon: "warning",
  buttons: true,
  dangerMode: true,
})
.then((willDelete) => {
  if (willDelete) {
 $.ajax({
				url:"update_manifest.php",
				method:"POST",
				data:{id_no:id_no,bus:bus,trip_date:trip_date,route_day_id:route_day_id,vehicle:vehicle,drivers_name:drivers_name,conductor_update:conductor_update,id_no:id_no},
				success:function(response){
					$("#example").DataTable().ajax.reload();
					if (window.TesseraStates) {
						TesseraStates.toastOk('Manifest updated', 'Vehicle and crew changes were saved.');
					} else {
															  swal({
    title: "Change",
    text: "Successfull Changed.",
    timer: 1000,
    showConfirmButton: false
  });
					}
  
  $("#save_changes").text("Change");
	},
				error: function () {
					$("#save_changes").text("Change");
					if (window.TesseraStates) {
						TesseraStates.toastErr('Update failed', 'Could not update this manifest.');
					}
				}
			
			});
  } else {
 
  }
});

});



});