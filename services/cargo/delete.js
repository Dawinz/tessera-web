$(document).ready(function(){
$('#delete').click(function(){
	var id = $('#id').text();
	var code = $('#code').text();
$('#delete').text("deleting....");
  $.ajax({
				url:"delete_luggage.php",
				method:"POST",
				data:{id:id,code:code},
				success:function(response){
if($.trim(response) == "deleted"){
		window.location="employee_update.php";
}else{
	$('#delete').text("delete");
	alert("Can't be deleted found in the Manifest");
}
				
	}
			
			});
});

});