$(document).ready(function(){


$("#search").click(function(){
var start_location = $( ".action option:selected" ).text();
var ending_location = $( "#state option:selected" ).text();
var date = $("#example-date-input").val();

if( start_location == "Choose starting Point"){
swal("", "Choose starting Point!", "warning");
control.disabled="disabled";
}
if(( ending_location == "Choose Ending Point") || ( ending_location == "")) {
swal("", "Choose  ending point!", "warning");
control.disabled="disabled";	
}
if( date == ""){
	swal("", "Select Date!", "warning");
	control.disabled="disabled";	

}

url = "trip_list.php?start_location="+start_location+"&ending_location="+ending_location+"&date="+date+"";
 window.location = url;
});
});