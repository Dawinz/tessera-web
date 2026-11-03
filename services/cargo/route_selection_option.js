	$(document).ready(function(){

	
	
	
$("#route").change(function(){ 
	var route = $( "#route option:selected" ).text();
var route2 = encodeURIComponent(window.btoa(route));

window.location="home.php?route="+route2;	
});
	
	});