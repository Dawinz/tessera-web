$(document).ready(function(){
$('#delete').click(function(){
	var id = $('#id').text();
	var password = $('#password').val();
$('#delete').text("Locking....");
  $.ajax({
				url:"lock_update.php",
				method:"POST",
				data:{id:id,password:password},
				success:function(response){

if($.trim(response) == "deleted"){
		window.location="vehicle_view.php";
}else{
	$('#delete').text("delete");
		 swal("Error!", "Something went Wrong!", "warning");
}	}
			
			});
});

});