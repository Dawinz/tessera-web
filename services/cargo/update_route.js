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
var initial = $("#initial_update").val();
var r_initial_update = $("#r_initial_update").val();
var contact_1_update = $("#contact_1_update").val();
var contact_2_update = $("#contact_2_update").val();
var address_1_update = $("#address_1_update").val();
var address_2_update = $("#address_2_update").val();
var terms_1_update = $("#terms_1_update").val();
var terms_2_update = $("#terms_2_update").val();


if(starting_point=="Choose Starting Point") {
				 swal("", "Please Select start point !", "warning");

			 control.disabled="disabled";
}

if(end_point=="Choose Ending Point") {
				 swal("", "Please select end point !", "warning");

			 control.disabled="disabled";
}


if(initial=="") {
				 swal("", "Please enter initial!", "warning");

			 control.disabled="disabled";
}if(r_initial_update=="") {
				 swal("", "Please enter Cargo initial!", "warning");

			 control.disabled="disabled";
}


$("#save_update").text("registering...");
 $.ajax({
				url:"update_route.php",
				method:"POST",
				data:{id_no:id_no,starting_point:starting_point,end_point:end_point,initial:initial,currency:currency,r_initial_update:r_initial_update,contact_1_update:contact_1_update,contact_2_update:contact_2_update,address_1_update:address_1_update,address_2_update:address_2_update,terms_1_update:terms_1_update,terms_2_update:terms_2_update},
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
						}else if($.trim(response) == "Mfound"){
							swal("Found", "Vehicle found !", "warning");
						}else if($.trim(response) == "fparcel"){
							swal("Found", "parcel found !", "warning");
						}
													$("#example").DataTable().ajax.reload();
$("#save_update").text("Change");
	}
			
			});

});



});