$(document).ready(function(){
	
	
	   $("#rgform").on('submit', function(e){
        e.preventDefault();
		
		count = 1;
		
error="";

$('.route_name_cont').each(function(){

			if($(this).val() == '')
			{

				error += "<li>Please Fill Route  </li>";
				
			
				
			}

			

		});	
		$('.contact_name').each(function(){

			if($(this).val() == '')
			{

				error += "<li>Please Fill Contact </li>";
				
			
				
			}



		});	
				

	if(error == '')
		{
  $.ajax({
            type: 'POST',
            url: 'insert/add_route_contact.php',
            data: new FormData(this),
           
            contentType: false,
            cache: false,
            processData:false,
            beforeSend: function(){
		$("#save").attr("disabled", true);
	  $("#save").text("Adding route..");
            
            },
            success: function(response){
			
				$("#save").attr("disabled", false);
	  $("#save").text("Register");
				response = $.trim(response);
	     $('#rgform').trigger("reset");
	if(response=="found"){
  Swal.fire({
  position: "top-end",
  icon: "waring",
  title: "Found",
  showConfirmButton: false,
  timer: 1500
});
	}else{
		  Swal.fire({
  position: "top-end",
  icon: "success",
  title: "Your work has been saved",
  showConfirmButton: false,
  timer: 1500
});


route_cnt_this = $("#route_name_cont").val();
	$.ajax({
				url:"search/contact_table.php",
				method:"POST",
				data:{route_cnt_this:route_cnt_this},
				success:function(response){
$("#contact_table").html(response);
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