$(document).ready(function(){
	
	
	
	   $("#form_country_code").on('submit', function(e){
        e.preventDefault();
		
		count = 1;
		
error="";
$('.code_name').each(function(){

			if($(this).val() == '')
			{

				error += "<li>Please Fill Country Code</li>";
				
			
				
			}

			

		});	

				

	if(error == '')
		{
			  $.ajax({
            type: 'POST',
            url: 'insert/add_contact_code.php',
            data: new FormData(this),
           
            contentType: false,
            cache: false,
            processData:false,
            beforeSend: function(){
		$("#save_contact").attr("disabled", true);
	  $("#save_contact").text("Adding route..");
            
            },
            success: function(response){
				$("#save_contact").attr("disabled", false);
	  $("#save_contact").text("Register");
	     //$('#form_country_code').trigger("reset");
		 $('.code_name').val("");
if($.trim(response)=="found"){
	Swal.fire("Country code found");

}else{
	
	Swal.fire({
  position: "top-end",
  icon: "success",
  title: "Your work has been saved",
  showConfirmButton: false,
  timer: 1500
});
$route_id = $("#code_route_name_id").val();
	$.ajax({
				url:"search/country_code.php",
				method:"POST",
				data:{route_id:route_id},
				success:function(response){
$("#coutry_code_table").html(response);
}
			
			});
}

		 }
        });
		}else{
				Swal.fire({
  title: "Missing Info",
  html: "<span style='color:white;'>"+error+"</span>",
  icon: "warning"
});		
		}
});
});