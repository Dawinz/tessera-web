$(document).ready(function(){


$("#search").click(function(){
var users = $( ".users option:selected" ).text();
var start_date = $("#start_date").val();
var end_date = $("#end_date").val();
if(users == "Choose users"){
swal("", "Choose User!", "warning");
control.disabled="disabled";
}
if( start_date == "") {
swal("", "Choose  Staring point!", "warning");
control.disabled="disabled";	
}
if( end_date == "") {
swal("", "Choose  ending point!", "warning");
control.disabled="disabled";	
}
url = "my_user_daily_charge.php?start_date="+start_date+"&end_date="+end_date+"&user="+users+"";
 window.location = url;
			
});
});