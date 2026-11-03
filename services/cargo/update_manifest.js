$(document).ready(function(){
	
	
	$("#send").click(function(){
			$("#send").text("Posting...");
		    var name = $('#name').val();
			var driver = $("#driver").val();
			var conductor = $("#conductor").val();
			var time2 = $("#time2").val();
			var id = $("#id_view").text();


			
  $.ajax({
				url:"update_vehicle.php",
				method:"POST",
				data:{name:name,driver:driver,conductor:conductor,id:id,time2:time2},
				success:function(response){
						$("#send").text("Send");

								window.location="vehicle_view.php";

}
			
			});
			
			});
			$("#savebtn2").click(function(){
				var id = $("#id_view").text();
				$("#savebtn2").text("Deletting...");
				  $.ajax({
				url:"delete_vehicle_name.php",
				method:"POST",
				data:{id:id},
				success:function(response){
						
					if($.trim(response) == "found"){
							$("#savebtn2").text("Delete");
						 swal("Found!", "Data Found inside!", "warning");	
                         control.disabled="disabled";
						}else{
							
																 if (navigator.onLine) {
	
	var id = $("#id_view").text();
	  $.ajax({
				url:"https://srclassiccoach.co.tz/delete_vehicle_backup.php",
				method:"POST",
				contentType: "application/x-www-form-urlencoded",
				data:{id:id},
				success:function(response){
}
			
			});
			

			
			}
							
							window.location="vehicle_view.php";
						}

}
			
			});
			});
		
});

