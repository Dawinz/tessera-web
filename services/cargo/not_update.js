$(document).ready(function(){
$('#delete').click(function(){
	var id = $('#id').text();
	var note = $('#note').val();
		var counter = $('#counter').text();
$('#delete').text("sending....");

  $.ajax({
				url:"not_update_insert.php",
				method:"POST",
				data:{id:id,counter:counter,note:note},
				success:function(response){
if($.trim(response) == "deleted"){
		window.location="edit_luggage_all.php";
}else if($.trim(response) == "found"){
 swal("Found!", "The Luggage is in the manifest you can not edit!", "warning");
	$('#delete').text("Update");
}else if($.trim(response) == "found_edit"){
 swal("sent for edit!", "The Luggage is sent for edit you can not send it again !", "warning");
	$('#delete').text("Update");
}
				
	}
			
			});
});

});