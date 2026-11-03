$(document).ready(function(){
	$("#insert").click(function(){
var situation = $( "#situation option:selected" ).text();
var subject = $( "#subject" ).val();
  var detail = $("#summernote").summernote("code");	
 

  $.ajax({
				url:"insert_camera.php",
				method:"POST",
				data:{situation:situation,subject:subject,detail:detail},
				success:function(response){
				
					
alert(response);
				
	}
			
			});
	});
});