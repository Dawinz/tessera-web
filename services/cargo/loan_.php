$(document).ready(function(){
$('#save').click(function(){
	var emp = $( "#emp option:selected" ).text();
	var loan = $( "#loan").val();
	var  return_ = $("#return").val();
	    var detail = $("#summernote").summernote("code");
var amount = $('#amount_').val();
  $.ajax({
				url:"../insert/loan.php",
				method:"POST",
				data:{emp:emp},
				success:function(response){
		
alert(reponse);			
	}
			
			});
});

});