$(document).ready(function() {
	   $("#signup").on('submit', function(e){
        e.preventDefault();
		
				count = 1;
		
error="";
	

choose_route = $("#choose_route option:selected").text();
	if((choose_route=="Choose Route") || (choose_route=="Routes not found") || (choose_route=="Routs no found")){
error += "<li>Choose Route name.</li>";
	}
	
	Authorizer = $("#Authorizer").val();
	if(Authorizer=="") {
error += "<li>Authorizer.</li>";
	}

	amount = $("#amount").val();
	if(amount=="") {
error += "<li>amount.</li>";
	}
	
	
		
				if(error == '')
		{
		
		    $.ajax({
            type: 'POST',
            url: 'insert/insert_cash.php',
            data: new FormData(this),
           
            contentType: false,
            cache: false,
            processData:false,
            beforeSend: function(){
				
							    $("#save").attr("disabled", true);
	  $("#save").text("Add Cash..");
            
            },
            success: function(response){
					$("#save").attr("disabled", false);
			  $("#save").text("Save Changes");
			  if (window.TesseraStates) {
				TesseraStates.toastOk('Cash saved', (typeof response === 'string' && response.trim()) ? response.trim() : 'Opening cash entry recorded.');
			  } else {
				alert(response);
			  }
			  if ($.fn.DataTable && $.fn.DataTable.isDataTable('#example1')) {
				$('#example1').DataTable().ajax.reload(null, false);
			  }
            },
			error: function () {
				$("#save").attr("disabled", false);
				$("#save").text("Save Changes");
				if (window.TesseraStates) {
					TesseraStates.toastErr('Payment failed', 'Could not save this cash entry.');
				}
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