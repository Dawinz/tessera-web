$(function() {
	// Open Popup
	$('[popup-open]').on('click', function() {
		var popup_name = $(this).attr('popup-open');
		$('[popup-name="' + popup_name + '"]').fadeIn(300);
	});

	// Close Popup
	$('[popup-close]').on('click', function() {
		var popup_name = $(this).attr('popup-close');
		$('[popup-name="' + popup_name + '"]').fadeOut(300);
	});
	
	// Close Popup When Click Outside
	$('.popupu').on('click', function() {
		var popup_name = $(this).find('[popup-closet]').attr('popup-close');
		$('[popup-name="' + popup_name + '"]').fadeOut(300);
	}).children().click(function() {
		  return false;
		});
	 $('#status_em option').click(function(){
        alert($(this).val());
        });
});
$(document).ready(function(){
 $('#status_em').change(function(){
        var options = ($(this).val());
		if(options== "Due payment"){
	$('.popup').show();
		}
        });
		$('#savebtn').click(function(){
          
	 	var due_amount = $("#due_amount").val();
				var due_amount2 = $("#due_amount2").val();
			
	 
if(due_amount==""){
swal("Fill ", "Fields cannot be empty!", "warning");
control.disabled="disabled";

}if(due_amount2==""){
swal("Fill ", "Fields cannot be empty!", "warning");
control.disabled="disabled";

}
	  				var due_amount = parseInt($("#due_amount").val().replace(/,/g, ''));
				var due_amount2 = parseInt($("#due_amount2").val().replace(/,/g, ''));

	  if(parseInt(due_amount2) >= parseInt(due_amount)){
				 swal("Paid Large than total amount!", "Paid can't be equal or greater than Total amount payment!", "warning");

	  }else{
		  $('#amount').val(due_amount);
		  $('#answer').val(due_amount2);
		  	$('.popup').hide();

	  }

});
});