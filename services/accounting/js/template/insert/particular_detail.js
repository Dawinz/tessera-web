$(document).ready(function() {
	
$("#insert_form").on('submit', function(e){
        e.preventDefault();
	
		count = 1;
		
error="";
	
category = $(".category2 option:selected").text();
	if(category=="Choose Category"){
error += "<li>Choose Particular Category.</li>";
	}
	affect = $(".affect option:selected").text();
	if(affect=="Select Affect"){
error += "<li>Choose Affect.</li>";
	}
	
particular = $("#particular_name2").val();


	if(particular==""){
error += "<li>Fill Particular .</li>";
	}


				if(error == '')
		{
			
	
	  $.ajax({
            type: 'POST',
            url: 'insert/particular_detail.php',
            data: new FormData(this),
           
            contentType: false,
            cache: false,
            processData:false,
            beforeSend: function(){
		$("#save_part").attr("disabled", true);
	  $("#save_part").text("Add Currency..");
            
            },
            success: function(response){
		//	alert(response);
		if(($.trim(response)) =="found"){
				$("#save_part").notify(
 "Currency found",
 "error",
  { position:"right" }
);
	
									    $("#save_part").attr("disabled", false);

	}else{
$("#save_part").notify(
 "saved Successfull",
 "success",
  { position:"right" }
);
		  $("#save_part").text("save changes");
									    $("#save_part").attr("disabled", false);
//$('#signup').trigger("reset");
$("#example1").DataTable().ajax.reload();
$(".part_name").val("");
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
});});