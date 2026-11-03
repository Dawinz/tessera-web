$(document).ready(function() {
	   $("#signup").on('submit', function(e){
        e.preventDefault();
		
		count = 1;
error="";
	
			if($("#name").val() == '')
			{

				error += "<li>Fill name.</li>";
$("#name").addClass( "error_class" );
$("#name").effect( "shake", {times:4}, 1000 );

			}else{
$("#name").removeClass( "error_class" );
		
			}
			
			if($("#value").val() == '')
			{

				error += "<li>Fill value.</li>";
$("#value").addClass( "error_class" );
$("#value").effect( "shake", {times:4}, 1000 );
			}else{
$("#value").removeClass( "error_class" );
			}
			
			if($("#symbol").val() == '')
			{

				error += "<li>Fill symbol.</li>";
				$("#symbol").addClass( "error_class" );
$("#symbol").effect( "shake", {times:4}, 1000 );


			}else{
				$("#symbol").removeClass( "error_class" );

			}

			if(error == '')
		{

		
		    $.ajax({
            type: 'POST',
            url: 'insert/insert_currency.php',
            data: new FormData(this),
           
            contentType: false,
            cache: false,
            processData:false,
            beforeSend: function(){
							    $("#save").attr("disabled", true);
	  $("#save").text("Add Currency..");
            
            },
            success: function(response){
				alert(response);
				if(($.trim(response)) =="found"){
				$("#save").notify(
 "Currency found",
 "error",
  { position:"right" }
);
	
	$("#save").attr("disabled", false);

	}else{
$("#save").notify(
 "Saved Successfull",
 "success",
  { position:"right" }
);
		  $("#save").text("Save changes");
									    $("#save").attr("disabled", false);
$('#signup').trigger("reset");
$("#example1").DataTable().ajax.reload();

				}
 
            }
        });
			
		}else{
			
				Swal.fire({
  title: "Missing Info",
  html: error,
  icon: "warning"
});		
		
		
		}
	
	});
});