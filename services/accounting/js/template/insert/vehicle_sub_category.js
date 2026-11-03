$(document).ready(function(){

$("#insert_form").on('submit', function(e){
        e.preventDefault();
	
		count = 1;
		
error="";


$("select[name='vehicles[]']").each(function(){
	if($(this).val() == 'Choose Vehicle')
			{
				
								error += "<li>Choose Category</li>";

			}
							
});
            if(error==""){
  $.ajax({
            type: 'POST',
            url: 'insert/particular_vehicle.php',
            data: new FormData(this),
           
            contentType: false,
            cache: false,
            processData:false,
            beforeSend: function(){
		$("#save").attr("disabled", true);
	  $("#save").text("Saving..");
            
            },
            success: function(response){
				//alert(response);
	
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
                  alert(error)
            }


});
});