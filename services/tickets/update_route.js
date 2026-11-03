$(document).ready(function(){

$("#show_update").click(function(){
	
	 $("#route_add_area_update").toggle();
	 
	 $("#save_location_update").click(function(){
		var l_name =  $("#l_name_update").val();
		if(l_name=="") {
				 swal("", "Please Enter location Name!", "warning");

			 control.disabled="disabled";
}
$("#save_location_update").text("saving...");
 $.ajax({
				url:"save_location_update.php",
				method:"POST",
				data:{l_name:l_name},
				success:function(response){
$("#example").DataTable().ajax.reload();

if($.trim(response) == "succesfull"){
					$("#save_location_update").text("Save");
							  swal({
  
    text: "update Succefull.",
    timer: 1000,
    showConfirmButton: false
  });
  $('#starting_point_update').append('<option value="">'+l_name+'</option>');
  $('#end_point_update').append('<option value="">'+l_name+'</option>');

						}else{
							swal("Found", "Vehicle found !", "warning");
						}
	}
			
			});

	 });
});
$("#save_update").click(function(){
var id_no = $("#id_no").text();
var starting_point = $("#starting_point_update option:selected" ).text();
var end_point = $("#end_point_update option:selected" ).text();
var currency = $("#currency_update option:selected" ).text();
var fair = $("#fair_update").val();
var initial = $("#initial_update").val();
var details = $("#details_update").val();
var active_day = $("#active_day_update").val();
var contact1_update = $("#contact1_update").val();
var contact2_update = $("#contact2_update").val();


if(starting_point=="Choose Starting Point") {
				 swal("", "Please Select start point !", "warning");

			 control.disabled="disabled";
}

if(end_point=="Choose Ending Point") {
				 swal("", "Please select end point !", "warning");

			 control.disabled="disabled";
}

if(fair=="") {
				 swal("", "Please Enter amount", "warning");

			 control.disabled="disabled";
}

if(initial=="") {
				 swal("", "Please enter initial!", "warning");

			 control.disabled="disabled";
}


$("#save_update").text("registering...");
 $.ajax({
				url:"update_route.php",
				method:"POST",
				data:{id_no:id_no,starting_point:starting_point,end_point:end_point,fair:fair,initial:initial,details:details,currency:currency,active_day:active_day,contact1_update:contact1_update,contact2_update:contact2_update},
				success:function(response){
$("#example").DataTable().ajax.reload();

					if($.trim(response) == "succesfull"){
					$("#search").text("Register");
							  swal({
  
    text: "Succefull.",
    timer: 1000,
    showConfirmButton: false
  });
  $("#save_update").text("Change");
						}else{
							swal("Found", "Vehicle found !", "warning");
						}
													$("#example").DataTable().ajax.reload();

	}
			
			});

});



});