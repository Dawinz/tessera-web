$(document).ready(function(){
			
$('#savebtn2').click(function(){
vehicle = $( "#vehicle option:selected" ).text();
route = $( "#route_insert option:selected" ).text();
driver = $("#driver").val();
conductor = $("#conductor").val();
time = $("#time2").val();
if(vehicle=="Enter Vehicle Name"){
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
$('#savebtn2').text('loading');
 $("#savebtn2").attr("disabled", true);
  $.ajax({
				url:"insert_vehicle.php",
				method:"POST",
				data:{vehicle:vehicle,driver:driver,conductor:conductor,time:time,route:route},
				success:function(response){
				
				 $("#savebtn2").attr("disabled", false);
				 $('#savebtn2').text('Register Manifest');

					if($.trim(response)=="found"){
						swal("Found!", "Found change the vehicle name!", "warning");
					
					}else{
                         $("#savebtn2").attr("disabled", false);
					     $('#savebtn2').text('Register Manifest');
					vehicle="";
					start_date="";
					end_date="";
					route_name_select=$("#all_routes").val();		
	
				$('#empTable').DataTable().ajax.reload();						
					}
	}
			
			});
})

$("#open").click(function(){
		  $( "#show" ).toggle( "milliseconds", function() {
    
  });	



  
  });

});