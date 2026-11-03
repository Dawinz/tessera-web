$(document).ready(function(){
	$("#send").click(function(){
		var location = $( "#location option:selected" ).text();
			var employee = $( "#employee option:selected" ).text();
				var status_ = $( "#status option:selected" ).text();
				var d = new Date();
				var monthyear = (d.getMonth()+1) + "/" + d.getFullYear();	
				var day =  d.getDate();
				$("#send").text("loading");
				  $.ajax({
				url:"insert_deployment.php",
				method:"POST",
				data:{location:location,employee:employee,status_:status_,monthyear:monthyear,day:day},
				success:function(response){
					alert(response);
									$("#send").text("save");

	}
			
			});
	});
});