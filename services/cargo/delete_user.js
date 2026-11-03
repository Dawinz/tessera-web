$(document).ready(function(){
$('#delete').click(function(){
	var id = $('#id').text();
$('#delete').text("deleting....");
  $.ajax({
				url:"delete_user.php",
				method:"POST",
				data:{id:id},
				success:function(response){
if($.trim(response) == "deleted"){
		window.location="employees.php";
}else{
	alert("errror");
}
				
	}
			
			});
});

});