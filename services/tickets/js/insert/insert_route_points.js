$(document).ready(function(){
	
	
	   $("#insert_route").on('submit', function(e){
        e.preventDefault();
		
		count = 1;
		
error="";
$("select[name='point[]']").each(function(){
			if($(this).val() == 'Point Type')
			{
				error += "<li>Choose Route Point </li>";

			}
});

$("select[name='route_name[]']").each(function(){
			if($(this).val() == 'Choose Route')
			{
				error += "<li>Choose Route Name </li>";

			}
});

$('#point_name').each(function(){
			if($(this).val() == '')
			{
				error += "<li>Enter Point Name </li>";

			}
});

$('#depture').each(function(){
			if($(this).val() == '')
			{
				error += "<li>Enter Depture Time </li>";

			}
});

$('#depture').each(function(){
			if($(this).val() == '')
			{
				error += "<li>Enter Depture Time </li>";

			}
});
$('#arrival').each(function(){
			if($(this).val() == '')
			{
				error += "<li>Enter Arrival Time </li>";

			}
});

if(error ==''){
	
  $.ajax({
            type: 'POST',
            url: 'insert/insert_route_points.php',
            data: new FormData(this),
           
            contentType: false,
            cache: false,
            processData:false,
            beforeSend: function(){
		$("#search_time").attr("disabled", true);
	  $("#search_time").text("Adding route..");
            
            },
            success: function(response){
			

 $.ajax({
				url:"search/search_traveling_time.php",
				method:"POST",
				data:{},
				success:function(response){
$("#load_travel").html(response);
	$("#search_time").attr("disabled", false);
	 
	}
			
			});


		 }
        });	
	
		
	
}else{
	
Swal.fire({
  title: "Missing Informations ?",
  html: "<span style='color:white;'>Please Fill all information</span>",
  icon: "warning"
});
}

});});