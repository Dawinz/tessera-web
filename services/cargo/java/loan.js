$(document).ready(function(){
$('#save').click(function(){
	var emp = $( "#emp option:selected" ).text();
	var loan = $( "#loan").val();
	var  return_ = $("#return").val();
	    var detail = $("#summernote").summernote("code");
var amount = $('#amount_').val();

  $.ajax({
				url:"./insert/loan.php",
				method:"POST",
				data:{loan:loan,emp:emp,return_:return_,detail:detail,amount:amount},
				success:function(response){
					
					
alert(response);
				
	}
			
			});
});

});