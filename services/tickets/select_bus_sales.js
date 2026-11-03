$(document).ready(function(){


$("#search").click(function(){
var bus = $( ".action option:selected" ).text();
var start_date = $("#start_date").val();
var end_date = $("#end_date").val();

if( bus == "Choose Bus Name"){
swal("", "Choose Bus Name!", "warning");
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
url = "bus_sales_report.php?bus="+bus+"&start_date="+start_date+"&end_date="+end_date+"";
 window.location = url;
});
});