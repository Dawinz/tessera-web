$(document).ready(function(){

$("#show").click(function(){
	 $("#route_add_area").toggle();
	 
	 $("#save_location").click(function(){
		var l_name =  $("#l_name").val();
		if(l_name=="") {
				 swal("", "Please Enter location Name!", "warning");

			 control.disabled="disabled";
}
$("#save_location").text("saving...");
 $.ajax({
				url:"save_location.php",
				method:"POST",
				data:{l_name:l_name},
				success:function(response){
 $("#l_name").val("");
$("#example").DataTable().ajax.reload();
if($.trim(response) == "succesfull"){
					$("#save_location").text("Save");
							  swal({
  
    text: "update Succefull.",
    timer: 1000,
    showConfirmButton: false
  });
  $('#starting_point').append('<option value="">'+l_name+'</option>');
  $('#end_point').append('<option value="">'+l_name+'</option>');
  $("#search").text("Register");
						}else{
							swal("Found", "Vehicle found !", "warning");
						}
	}
			
			});

	 });
});
$("#search").click(function(){

var starting_point = $("#starting_point option:selected" ).text();
var end_point = $("#end_point option:selected" ).text();
var currency = $("#currency option:selected" ).text();
var initial = $("#initial").val();
var r_initial = $("#r_initial").val();
var contact_1 = $("#contact_1").val();
var contact_2 = $("#contact_2").val();
var address_1 = $("#address_1").val();
var address_2 = $("#address_2").val();
var terms_1 = $("#terms_1").val();
var terms_2 = $("#terms_2").val();



if(starting_point=="Choose Starting Point") {
				 swal("", "Please Select start point !", "warning");

			 control.disabled="disabled";
}

if(end_point=="Choose Ending Point") {
				 swal("", "Please select end point !", "warning");

			 control.disabled="disabled";
}

if(currency=="Choose Currency") {
				 swal("", "Please Select Currency!", "warning");

			 control.disabled="disabled";
}



if(initial=="") {
				 swal("", "Please enter initial!", "warning");

			 control.disabled="disabled";
}
if(r_initial=="") {
				 swal("", "Please enter initial!", "warning");

			 control.disabled="disabled";
}if(contact_1=="") {
				 swal("", "Please enter Contact one!", "warning");

			 control.disabled="disabled";
}if(address_1=="") {
				 swal("", "Please enter Address one!", "warning");

			 control.disabled="disabled";
}
if(address_2=="") {
				 swal("", "Please enter Address two!", "warning");

			 control.disabled="disabled";
}if(terms_1=="") {
				 swal("", "Please enter Terms one!", "warning");

			 control.disabled="disabled";
}
if(terms_2=="") {
				 swal("", "Please enter Terms two!", "warning");

			 control.disabled="disabled";
}

$("#search").text("registering...");
 $("#search").attr("disabled", true);

 $.ajax({
				url:"save_route.php",
				method:"POST",
				data:{starting_point:starting_point,end_point:end_point,initial:initial,currency:currency,r_initial:r_initial,contact_1:contact_1,contact_2:contact_2,address_1:address_1,address_2:address_2,terms_1:terms_1,terms_2:terms_2},
				success:function(response){
					
$("#initial").val("");

					$("#search").text("Register");

					$("#search").attr("disabled", false);
					if($.trim(response) == "succesfull"){
					$("#search").text("Register");
					 

							  swal({
  
    text: "update Succefull.",
    timer: 1000,
    showConfirmButton: false
  });
  $("#search").text("Register");
						}else if ($.trim(response) == "change"){
							swal("Change Recipt", "change recipt initial found inside the database !", "warning");
						}else{
							
swal("Found", "Vehicle found !", "warning");

						}
													$("#example").DataTable().ajax.reload();

	}
			
			});

});



});