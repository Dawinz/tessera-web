$(document).ready(function(){
	 $("#update_form_particular").on('submit', function(e){
        e.preventDefault();
		
			count = 1;
error="";
	
	

			if($("#particular_update").val() == '')
			{

				error += "<li>Fill particular name.</li>";


			}
			
			
						if(error == '')
		{
	    $.ajax({
            type: 'POST',
            url: 'update/particular_detail.php',
            data: new FormData(this),
           
            contentType: false,
            cache: false,
            processData:false,
            beforeSend: function(){
							    $("#update").attr("disabled", true);
	  $("#update").text("updating...");
            
            },
            success: function(response){
				alert(response);
									$("#example1").DataTable().ajax.reload();

				$("#update").notify(
 "Successfull",
 "success",
  { position:"right" }
);
    $("#update").attr("disabled", false);
	  $("#update").text("Update Changes");
			
            }
        });	
		}else{
alert(error);
		}
			
});


});