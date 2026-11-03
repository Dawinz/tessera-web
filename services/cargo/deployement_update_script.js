$(document).ready(function(){
	$("#send").click(function(){
		var location = $( "#location option:selected" ).text();
			var employee = $( "#employee option:selected" ).text();
				var status_ = $( "#status option:selected" ).text();
				var d = new Date();
			
				  $.ajax({
				url:"insert_deployment.php",
				method:"POST",
				data:{location:location,employee:employee,status_:status_,monthyear:monthyear,day:day},
				success:function(response){
					alert(response);
				
	}
			
			});
	});
		$("#delete").click(function(){
			var emp_id = $('.emp_id').text();
			date = $('.date').text();
			id = $('.id').text();
			status = $('.status').text();
			  $.ajax({
				url:"delete_deploy.php",
				method:"POST",
				data:{emp_id:emp_id,date:date,id:id,status:status},
				success:function(response){
					alert(response);
				
	}
			
			});
	});
});