$(document).ready(function(){
$("#search").click(function(){
var starting = $( ".action option:selected" ).text();
var ending = $( "#state option:selected" ).text();
var date = $("#date2").val();

if(starting=="Choose starting Point"){
	 alertify.error("Choose starting point ");
alertify.set('notifier','position', 'top-right');
control.disabled="disabled";

}if(ending==""){
	 alertify.error("Choose Ending Point ");
alertify.set('notifier','position', 'top-right');
control.disabled="disabled";

}
if(ending=="Choose Ending Point"){
	 alertify.error("Choose Ending Point ");
alertify.set('notifier','position', 'top-right');
control.disabled="disabled";

}if(date==""){
	 alertify.error("Choose Date ");
alertify.set('notifier','position', 'top-right');
control.disabled="disabled";

}

	   $('.modal').modal('show');
var starting = $( ".action  option:selected" ).text().replace(/ /g,"_");
var ending = $( "#state option:selected" ).text().replace(/ /g,"_");
$("#load_trip_vehicles").load("vehicle_transfer_list.php?starting="+starting+"&ending="+ending+"&date="+date+"");


});
});