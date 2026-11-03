$(document).ready(function() {
	
	
	   $("#signup").on('submit', function(e){
        e.preventDefault();
	count = 1;
error="";
categroy = $(".categroy").val();
	if(categroy==""){
error += "<li>Fill  category.</li>";
	}

   			if(error == '')
		{
              $.ajax({
            type: 'POST',
            url: 'insert/vehicle_category.php',
            data: new FormData(this),
           
            contentType: false,
            cache: false,
            processData:false,
            beforeSend: function(){
		$("#save").attr("disabled", true);
	  $("#save").text("Saving..");
            
            },
            success: function(response){
                $("#save").attr("disabled", false);
	  $("#save").text("Saving");
                alert(response);
         
							$("#example1").DataTable().ajax.reload();

$("#save").notify(
 "Saved Successfull",
 "success",
  { position:"right" }
);
	
									    $("#save").attr("disabled", false);
										$("#save").text("Save Changes");
										
										$('#item_table').find('tr:gt(0)').remove();


						$('#item_table').append(add_input_field(0));
                
					
            }
        });	
        }else{

        }
    
	});
});