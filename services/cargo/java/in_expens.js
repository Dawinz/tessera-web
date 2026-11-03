$(document).ready(function(){
$('#save').click(function(){
	var account = $( "#account option:selected" ).text();
	var amount = $( "#amount").val();

	    var detail = $("#summernote").summernote("code");
var d = new Date();
      var monthyear =(d.getMonth()+1) + "/" + d.getDate() + "/"+ d.getFullYear();	

  $.ajax({
				url:"./insert/expens.php",
				method:"POST",
				data:{account:account,amount:amount,detail:detail,monthyear:monthyear},
				success:function(response){
					
					
alert(response);
				
	}
			
			});
});

});