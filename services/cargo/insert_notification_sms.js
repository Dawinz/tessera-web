$(document).ready(function(){
	$('#save').click(function(){

		var sms_text = $('.sms_text').text();
			$('.response').text("sending...");
		  $.ajax({
				url:"insert_notification_sms.php",
				method:"POST",
				data:{sms_text:sms_text},
				success:function(response){
				$('.response').text("yes");

	
}
			
			});
		
	});	});