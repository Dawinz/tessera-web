$(document).ready(function(){
$('#savebtn2_update').click(function(){
	
name = $( "#vehicle_update option:selected" ).text();
route_update = $( "#route_update option:selected" ).text();
driver = $("#driver_update").val();
conductor = $("#conductor_update").val();
time = $("#time2_update").val();

var id = $("#id_update").text();
if(name=="Enter Vehicle Name"){
	 swal("Fill Vehicle's name!", "Please Enter Vehicle Detail!", "warning");	
 control.disabled="disabled";
}else if(driver==""){
	 swal("Fill Driver's name!", "Please Enter Driver name!", "warning");	
 control.disabled="disabled";
} else if(conductor == ""){
	 swal("Fill Conductors name!", "Please Enter Conductors name!", "warning");	
 control.disabled="disabled";
} else if(time==""){
	 swal("Fill time!", "Please Enter Date!", "warning");	
 control.disabled="disabled";
}
$('#savebtn2_update').text('loading');
 $("#savebtn2_update").attr("disabled", true);
  $.ajax({
		url:"update_vehicle.php",
				method:"POST",
				data:{name:name,driver:driver,conductor:conductor,id:id,time:time,route_update:route_update},
				success:function(response){
					alert(response);
					if($.trim(response)=="found"){
						swal("Found!", "Found change the vehicle name!", "warning");
					
					}else if($.trim(response)=="closed"){
				swal("Closed!", "Manifest is closed!", "warning");
					}else{
					//	window.location="vehicle_view.php";

					}
					$("#savebtn2_update").attr("disabled", false);
					$('#savebtn2_update').text('Register Manifest');
	}
			
			});
})

$("#open_update").click(function(){
		  $( "#show_update" ).toggle( "milliseconds", function() {
    
  });	

$("#save_update").click(function(){
var name = $("#name_update").val();
if(name==""){
	 swal("Fill Vehicle's name!", "Please Enter Vehicle Detail!", "warning");	
 control.disabled="disabled";
}
$("#save_update").text("Adding...");
  $.ajax({
				url:"insert_vehicle_name.php",
				method:"POST",
				data:{name:name},
				success:function(response){
				$('#picker').append('<option value="' + name + '">' + name + '</option>'); 
				if($.trim(response) == "found"){
				$("#save_update").text("Add");
							swal("Found", "Vehicle name found!", "warning");

			}else{
			window.location="vehicle_view.php";	
			}

					
	}
			
			});

});

  
  });
});