$(document).ready(function(){
	$("#send").click(function(){
			var code = $("#code").text();
			
			$("#send").text("loading...");
  $.ajax({
				url:"save_final_update.php",
				method:"POST",
				data:{code:code},
				success:function(response){
window.top.close();	}
			
			});
});});