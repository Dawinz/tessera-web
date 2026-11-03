<button id="insert"type="button" style="height:40px;"class="btn btn-soft-danger"><i  class="uil uil-ban"></i></button> 
<div id ="create">create</div>
		<script src="js/vendor/jquery-1.12.4.min.js"></script>
		<script>
		var create = $("#create").text();
		$(document).ready(function(){
			$("#insert").click(function(){
				
	
	 $.ajax({
				url:"qrcode/index.php",
				method:"POST",
				data:{create:create,},
				success:function(response){
				alert(response);
		

		 	
			
	}
			
			});
					});
		});
		</script>
