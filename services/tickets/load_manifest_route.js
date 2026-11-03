$(document).ready(function(){
$('#route').on('change', function (e) {
	$(".spinner-grow").show();
	var route = $( "#route option:selected" ).text().replace(/ /g,"_");
	
	$("#load_manifest_table").load("load_manifest_table.php?route="+route);

		});
});