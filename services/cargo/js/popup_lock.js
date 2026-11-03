$(function() {
	// Open Popup
	$('[popup-open]').on('click', function() {
		var popup_name = $(this).attr('popup-open');
		$('[popup-name="' + popup_name + '"]').fadeIn(300);
	});

	// Close Popup
	$('[popup-close]').on('click', function() {
	window.location="vehicle_view.php";

	});
	
	// Close Popup When Click Outside
	$('.popupu').on('click', function() {
									window.location="vehicle_view.php";

	}).children().click(function() {
		  return false;
		});
	 $('#status_em option').click(function(){
        alert($(this).val());
        });
});
$(document).ready(function(){
$('.popup').show().fadeIn(300);
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
	$('#savebtn').text("open");
$('.popup').show().fadeOut(300);
}else{
	$('#savebtn').text("Open");
				 swal("Passcode didn't match!", "Please Try Again!", "warning");

}
				
	}
			
			});
});
});