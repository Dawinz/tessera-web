$(document).ready(function(){
$('#delete').click(function(){
	var id = $('#id').text();
$('#delete').text("deleting....");
  $.ajax({
				url:"delete_vehicle_view.php",
				method:"POST",
				data:{id:id},
				success:function(response){
if($.trim(response) == "deleted"){
		window.location="vehicle_view.php";
}else{
	swal("Error", "You can't delete data found", "warning");
}
				
	}
			
			});
});

});