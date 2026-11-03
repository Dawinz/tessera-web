$(document).ready(function() {
	
$("#particular_form").on('submit', function(e){
        e.preventDefault();
	
		count = 1;
		
error="";
	
$("select[name='category[]']").each(function(){
	if($(this).val() == 'Choose Particular')
			{
				
								error += "<li>Choose Category</li>";

			}
							
});
$(".particulars").each(function(){
	if($(this).val() == '')
			{
				
								error += "<li>Enter Particular name</li>";

			}
							
});

		if(error == '')
		{
			
		
	  $.ajax({
            type: 'POST',
            url: 'insert/particular_detail_sub.php',
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

$("#save").attr("disabled", false);
$("#save").text("Save Changes");
new_response = $.trim(response);
if(new_response==""){
	$("#save").notify(
 "Saved Successfull",
 "success",
  { position:"right" }
);
	
}else{
		$("#save").notify(
 "Found",
 "warning",
  { position:"right" }
);
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