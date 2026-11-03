$(document).ready(function() {
	   $("#signup").on('submit', function(e){
        e.preventDefault();
		
				count = 1;
		
error="";
	


choose_route = $("#choose_route option:selected").text();
	if((choose_route=="Choose Route") || (choose_route=="Routes not found") || (choose_route=="Routs no found")){
error += "<li>Choose Route name.</li>";
	}
	
trip_date = $("#trip_date option:selected").text();
	if(trip_date=="Select Trip Date") {
error += "<li>Choose Trip date.</li>";
	}
	vehicle2 = $("#vehicle2 option:selected").text();
	if(vehicle2=="Select Vehicle") {
error += "<li>Choose Vehicle.</li>";
	}	
	vehicle_cag = $("#vehicle_cag option:selected").text();
	if(vehicle_cag=="Select Type") {
error += "<li>Choose Vehicle Type.</li>";
	}
	
		
				if(error == '')
		{
		
		    $.ajax({
            type: 'POST',
            url: 'insert/manifest.php',
            data: new FormData(this),
           
            contentType: false,
            cache: false,
            processData:false,
            beforeSend: function(){
							    $("#save").attr("disabled", true);
	  $("#save").text("Adding Manifest..");
            
            },
            success: function(response){
					$("#save").attr("disabled", false);
new_response=$.trim(response);
if(new_response=="found"){
if (window.TesseraStates) {
	TesseraStates.toastErr('Already on manifest', 'This vehicle is already added.');
} else {
$.notify("Vehicle is already added", {
  className:'error',
  clickToHide: false,
  autoHide: true,
  globalPosition: 'left top'
});
}
}else{
if (window.TesseraStates) {
	TesseraStates.toastOk('Manifest updated', 'Vehicle added to the manifest list.');
} else {
$.notify("Vehicle is added to manifest list", {
  className:'success',
  clickToHide: false,
  autoHide: true,
  globalPosition: 'left top'
});
}
}

		$("#example1").DataTable().ajax.reload();
    $("#save").attr("disabled", false);
	  $("#save").text("Save Changes");
 
            }
        });
			
		}else{
			if (window.TesseraStates) {
				TesseraStates.toastWarn('Missing information', $('<div>').html(error).text());
			} else {
									Swal.fire({
  title: "Missing Info",
  html: error,
  icon: "warning"
});
			}
		}
		
	});
});