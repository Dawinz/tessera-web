	$(document).ready(function(){

	
	
	
$("#route").change(function(){ 
var route = $( "#route option:selected" ).text().replace(/ /g,"_");
			
	$("#load_all_luggages_table").load("load_all_charges_table_user.php?route="+route);
	$(".spinner-border").show();
});
	
	});