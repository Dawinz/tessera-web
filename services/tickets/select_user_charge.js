$(document).ready(function(){


$("#search").click(function(){
$(".searching_word").text("redirecting");
var start_date = $("#start_date").val();
var end_date = $("#end_date").val();



if( start_date == "") {
swal("", "Choose  Staring point!", "warning");
control.disabled="disabled";	
}
if( end_date == "") {
swal("", "Choose  ending point!", "warning");
control.disabled="disabled";	
}
url = "charge_summary.php?&start_date="+start_date+"&end_date="+end_date+"";
 window.location = url;
			
});
});