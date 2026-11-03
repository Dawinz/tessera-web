$(document).ready(function(){
$('#delete').click(function(){
	var id = $('#id').text();
	var code = $('#code').text();
	var noid = $('#noid').text();
$('#delete').text("deleting....");
  $.ajax({
				url:"delete_manifest.php",
				method:"POST",
				data:{id:id,code:code,noid:noid},
				success:function(response){
window.location="employee_update.php";
				
	}
			
			});
});

});