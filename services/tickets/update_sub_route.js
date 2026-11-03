$(document).ready(function(){

$("#show_update").click(function(){
	 $("#route_add_area_update").toggle();
	 
	 $("#save_location").click(function(){
		var l_name =  $("#l_name_update").val();
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


if($.trim(response) == "succesfull"){
					$("#save_location_update").text("Save");
							  swal({
  
    text: "update Succefull.",
    timer: 1000,
    showConfirmButton: false
  });
  $('#starting_point_update').append('<option value="">'+l_name+'</option>');
  $('#end_point_update').append('<option value="">'+l_name+'</option>');
  $("#search_update").text("Register");
						}else{
							swal("Found", "Vehicle found !", "warning");
						}
						$("#example").DataTable().ajax.reload();
	}
			
			});

	 });
});
$("#save_update").click(function(){

var main_route = $("#main_route_update option:selected" ).text();
var starting_point = $("#starting_point_update option:selected" ).text();
var end_point = $("#end_point_update option:selected" ).text();;
var fair = $("#fair_update").val();
var initial = $("#initial_update").val();
var details = $("#details_update").val();
var a_aft = $("#a_aft_update").val();
var a_time = $("#a_time_update").val();
var d_ptime = $("#d_ptime_upadate").val();
var id_no = $("#id_no").text();
var currency = $("#currency_update option:selected" ).text();


if(main_route=="Choose Main Route") {
				 swal("", "Please Select Main Route !", "warning");

			 control.disabled="disabled";
}
if(starting_point=="Choose Starting Point") {
				 swal("", "Please Select start point !", "warning");

			 control.disabled="disabled";
}if(starting_point==end_point) {
				 swal("", "Dublicate !", "warning");

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
if(a_aft=="") {
				 swal("", "Please enter how day the bus can be booked after inital trip date!", "warning");

			 control.disabled="disabled";
}
if(a_time=="") {
				 swal("", "Please enter arrrival time!", "warning");

			 control.disabled="disabled";
}if(d_ptime=="") {
				 swal("", "Please enter Depature time!", "warning");

			 control.disabled="disabled";
}


$("#save_update").text("registering...");

 $.ajax({
				url:"update_sub_route.php",
				method:"POST",
				data:{id_no:id_no,main_route:main_route,starting_point:starting_point,end_point:end_point,fair:fair,initial:initial,details:details,a_aft:a_aft,a_time:a_time,d_ptime:d_ptime,currency:currency},
				success:function(response){
					
$("#example").DataTable().ajax.reload();
	if($.trim(response) == "succesfull"){

					$("#save_update").text("Register");
							  swal({
  
    text: "update Succefull.",
    timer: 1000,
    showConfirmButton: false
  });
  $("#save_update").text("Register");
						}else{
							swal("Found", "Vehicle found !", "warning");
						}
	}
			
			});
});



});