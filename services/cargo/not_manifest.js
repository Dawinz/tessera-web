$(document).ready(function(){
$('#delete').click(function(){
	var id = $('#id').text();
	var counter = $('#counter').text();
$('#delete').text("sending....");
  $.ajax({
				url:"not_delete_insert_manifest.php",
				method:"POST",
				data:{id:id,counter:counter},
				success:function(response){
if($.trim(response) == "deleted"){
		window.location="vehicle_view.php";
}else if($.trim(response) == "found"){
 swal("Found!", "The Luggage is in the manifest you can not Delete!", "warning");
	$('#delete').text("Delete");
}else if($.trim(response) == "found_edit"){
 swal("Found Inside!", "The Luggage is Found for edit!", "warning");
	$('#delete').text("Delete");
}
				
	}
			
			});
});

});