	$(document).ready(function(){

	
	
	
$("#route").change(function(){ 
var route = $( "#route option:selected" ).text();
window.location="luggage_check_out.php?route="+route;	

});
	
	});