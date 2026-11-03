	$(document).ready(function(){

	
	
	
$("#route").change(function(){ 
var route = $( "#route option:selected" );
window.location="vehicle_view.php?route="+route;	

});
	
	});