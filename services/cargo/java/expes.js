$(document).ready(function(){
$('#insert').click(function(){
	
	var account = $( "#account").val();
	var  detail = $("#detail").val();
	   


  $.ajax({
				url:"./insert/account.php",
				method:"POST",
				data:{account:account,detail:detail},
				success:function(response){
					
					
alert(response);
				
	}
			
			});
});

});