$(document).ready(function(){
$('#insert').click(function(){
	var situation= $( "#situation option:selected" ).text();
	var subject = $( "#subject").val();
	var d = new Date();
      var monthyear =d.getDate() + "/" +(d.getMonth()+1)  + "/"+ d.getFullYear();	
	    var detail = $("#summernote").summernote("code");
$('#insert').text("saving");

  $.ajax({
				url:"./insert/camera.php",
				method:"POST",
				data:{situation:situation,subject:subject,monthyear:monthyear,detail:detail},
				success:function(response){
					
					
$('#insert').text("save");
				
	}
			
			});
});

});