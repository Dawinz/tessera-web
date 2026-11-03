$(document).ready(function(){
$('#insert').click(function(){

	var subject = $( "#subject").val();
	var d = new Date();
      var monthyear =d.getDate() + "/" +(d.getMonth()+1)  + "/"+ d.getFullYear();	
	    var detail = $($("#summernote").summernote("code")).text();
$('#insert').text("saving");

  $.ajax({
				url:"./insert/sms.php",
				method:"POST",
				data:{subject:subject,monthyear:monthyear,detail:detail},
				success:function(response){
					
					alert(response);
$('#insert').text("save");
				
	}
			
			});
});

});