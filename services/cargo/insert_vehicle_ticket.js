$(document).ready(function(){
$('#check').click(function(){
var date = $( "#country option:selected" ).text();
var vehicle = $( "#state option:selected" ).text();
route = $( "#route option:selected" ).text();
if(date=="Choose Manifest Date"){
	 swal("Fill Manifest Date!", "Please Enter Manifest date!", "warning");	
 control.disabled="disabled";
}
if(vehicle=="Choose Manifest Date"){
	 swal("Fill Vehilcle !", "Please Enter Manifest vehicle!", "warning");	
 control.disabled="disabled";
}if(vehicle=="Select Vehicles"){
	 swal("Fill Vehilcle !", "Please Enter Manifest vehicle!", "warning");	
 control.disabled="disabled";
}
 $("#check").attr("disabled", true);
$("#resp").text("saving...");

 $.ajax({
				url:"insert_vehicle_ticket.php",
				method:"POST",
				data:{vehicle:vehicle,date:date,route:route},
				success:function(response){
				$("#resp").text("");
		if($.trim(response)=="found"){
						swal("Found!", "Found change the vehicle name!", "warning");
					$("#check").attr("disabled", false);
			
					}else{
						$("#empTable").DataTable().ajax.reload();
						$("#check").attr("disabled", false);                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                    
					}
	}
			
			});


});



});