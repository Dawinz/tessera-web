
 $(document).ready(function(){
			 $('#insert').click(function(){

var firstday = $('#date').val();
var lastday = $('#date2').val();
var trip = $( "#trip option:selected" ).text().replace(/ /g,"_");
var route = $( "#route option:selected" ).text().replace(/ /g,"_");

	 if (firstday==""){
		 alertify.error("fill Firstday");
alertify.set('notifier','position', 'top-right');
 $("#trip option:selected" ).effect( "shake" );	 } 
 if (lastday==""){
				 alertify.error("fill Lastday");
alertify.set('notifier','position', 'top-right');
 $("#trip option:selected" ).effect( "shake" );
		
	 } else if(trip == "CHOOSE USER"){
		 alertify.error("fill Username");
alertify.set('notifier','position', 'top-right');
 $("#trip option:selected" ).effect( "shake" );
	 }
$(".search_text").text("Searching...");
		$("#manifest_store_summary").load("manifest_store_summary.php?firstday="+firstday+"&lastday="+lastday+"&trip="+trip+"&route="+route+"");	
			
 });
 });