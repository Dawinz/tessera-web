$(document).ready(function(){

  $("#form_transit").on('submit', function(e){
        e.preventDefault();
		
		count = 1;
		
error="";

route_name_cont_this = $("#route_name_cont_this").val();

$("select[name='route_transit']").each(function(){
			if($(this).val() == 'Choose Route')
			{
				error += "<li>Please Choose Route  </li>";

			}
});


if(route_name_cont_this == $("select[name='route_transit']").val() ){
	error += "<li>Equal to Transit point  </li>";
}


if(error == '')
		{
			
			$.ajax({
            type: 'POST',
            url: 'insert/add_transit.php',
            data: new FormData(this),
           
            contentType: false,
            cache: false,
            processData:false,
            beforeSend: function(){
		$("#save_insert").attr("disabled", true);
	  $("#save_insert").text("Adding route..");
            
            },
            success: function(response){
				
				if($.trim(response)==""){
					  Swal.fire({
  position: "top-end",
  icon: "success",
  title: "Your work has been saved",
  showConfirmButton: false,
  timer: 1500
});	
	     $('#form_transit').trigger("reset");

				}else{
				Swal.fire("Something Went Wrong");	
				}
				
		$("#save_insert").attr("disabled", false);
	  $("#save_insert").text("Register");
	  $("#example").DataTable().ajax.reload();

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