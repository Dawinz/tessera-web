$(document).ready(function(){
$('#check').click(function(){
		var no = $('#no').val();	
		var date2 = $('#date2').val();
		var id2 = $('#id').text();
		
		$('#check').text("inseting");
		  $.ajax({
				url:"update_tempo.php",
				method:"POST",
				data:{no:no,id2:id2,date2:date2},
				success:function(response){
			alert(response);
	}
			
			});
});
});