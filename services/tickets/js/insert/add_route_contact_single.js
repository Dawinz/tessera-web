$(document).ready(function(){
	
	
	   $("#rgform").on('submit', function(e){
        e.preventDefault();
		
	error="";
		$("select[name='route_type[]']").each(function(){

			if($(this).val() == 'Choose Route')
			{

				error += "<li>Please Fill Route  </li>";
				
			
				
			}

			

		});	
		
		$('.name').each(function(){

			if($(this).val() == '')
			{

				error += "<li>Please Fill Description </li>";
				
			
				
			}



		});		$('.contact').each(function(){

			if($(this).val() == '')
			{

				error += "<li>Please Fill Contact </li>";
				
			
				
			}



		});	
		
		if(error==""){
	 $.ajax({
            type: 'POST',
            url: 'insert/add_route_contact_single.php',
            data: new FormData(this),
           
            contentType: false,
            cache: false,
            processData:false,
            beforeSend: function(){
		$("#search").attr("disabled", true);
	  $("#search").text("Adding route..");
            
            },
            success: function(response){
				$("#example").DataTable().ajax.reload();
				     //$('#rgform').trigger("reset");

						$("#search").attr("disabled", false);
	  $("#search").text("Register");
	  Swal.fire({
  position: "top-end",
  icon: "success",
  title: "Your work has been saved",
  showConfirmButton: false,
  timer: 1500
});
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