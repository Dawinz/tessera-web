$(document).ready(function(){
		$('#savebtn').click(function(){
var password = $("#password").val();
var vehicle = $("#vehicle").text();
var date = $("#date").text();
$('#savebtn').text("opening....");
  $.ajax({
				url:"check_lock.php",
				method:"POST",
				data:{password:password,vehicle:vehicle,date:date},
				success:function(response){
if($.trim(response) == "found"){
	$("#mo_delete").show();	
	$("#mo_delete24").show();
	$('#savebtn').text("Open");

								  swal({
  
    text: "Now you can close the prompt and continue.",
    timer: 2000,
    showConfirmButton: false
  });
}else{
	$('#savebtn').text("Open");
				 swal("Passcode didn't match!", "Please Try Again!", "warning");

}
				
	}
			
			});
});
});