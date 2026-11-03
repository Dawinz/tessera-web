	$(document).ready(function(){

	
	
	
$("#route").change(function(){ 
	var route = $( "#route option:selected" ).text();

window.location="user_store_charge.php?route="+route;	
});
	
	});