	$(document).ready(function(){

	
	
	
$("#route").change(function(){ 
	var route = $( "#route option:selected" ).text();

window.location="vehicle_view.php?route="+route;	
});
	
	});