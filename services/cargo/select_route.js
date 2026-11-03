	$(document).ready(function(){

	
	
	
$("#route").change(function(){ 
var route = $( "#route option:selected" ).text().replace(/ /g,"_");
			
	$("#load_store_table").load("load_store_table.php?route="+route);
	$(".spinner-border").show();
});
	
	});