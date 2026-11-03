$(document).ready(function(){


 $("#signup").on('submit', function(e){
        e.preventDefault();
		
			count = 1;
error="";

	insert_date = $("#insert_date").val();
	if(insert_date=="" ){
error += "<li>Fill date .</li>";
	}
	
	
						if(error == '')
		{
	    $.ajax({
            type: 'POST',
              url: 'update/update_jobcard_first.php',
            data: new FormData(this),
           
            contentType: false,
            cache: false,
            processData:false,
            beforeSend: function(){
							    $("#update").attr("disabled", true);
	  $("#uptext").text("updating...");
            
            },
            success: function(response){
				alert(response);
		 							    $("#update").attr("disabled", false);
	  $("#uptext").text("Save Changes");

            }
        });	
		}else{
alert(error);
		}
	
	
});

});