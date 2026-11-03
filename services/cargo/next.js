$(document).ready(function(){
	
	
	$("#send").click(function(){
		$("#send").text("Posting...");
		var choosen = $('#choosen option:selected').text();
		   
		
		if(choosen=="Choose Vehicle"){
			swal("Fill Vehicle!", "Please Fill Vehicle!", "warning");
			$("#send").text("Post");
			 			 control.disabled="disabled";
		}
		
			window.location="check_luggages.php?id="+choosen;


});});