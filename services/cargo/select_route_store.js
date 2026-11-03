	$(document).ready(function(){

	
	
	
$("#route").change(function(){ 
var route = $( "#route option:selected" ).text().replace(/ /g,"_");
window.location="store_inventory.php?route="+route;

});
	
	});