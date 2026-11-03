$(document).ready(function() {
	id="tets";
  setTimeout(function() {
  
   $.ajax({
				url:"delete_booked.php",
				method:"POST",
				data:{id:id},
				success:function(response){
					
	
	}
			
			});
  
  
  }, 5000);
});