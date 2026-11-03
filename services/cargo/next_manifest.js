$(document).ready(function(){
	
	
	$("#send").click(function(){
		$("#send").text("Posting...");
		var choosen = $('#choosen option:selected').text();
		   
		  $.ajax({
				url:"select_manifest.php",
				method:"POST",
				data:{choosen:choosen},
				success:function(response){
					window.location="vehicle_report_new.php?date="+choosen+"&date2="+choosen+"&date3="+response;
	}
			
			});

});});