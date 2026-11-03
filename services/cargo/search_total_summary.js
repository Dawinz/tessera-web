
 $(document).ready(function(){
			 $('#insert').click(function(){

var firstday = $('#date').val();
var lastday = $('#date2').val();

var route = $( "#route option:selected" ).text().replace(/ /g,"_");

	 if (firstday==""){
		 alertify.error("fill Firstday");
alertify.set('notifier','position', 'top-right');
 $("#trip option:selected" ).effect( "shake" );	 } 
 if (lastday==""){
				 alertify.error("fill Lastday");
alertify.set('notifier','position', 'top-right');
 $("#trip option:selected" ).effect( "shake" );
		
	 } 
$(".search_text").text("Searching...");
		window.location="total_charge_summary.php?firstday="+firstday+"&lastday="+lastday+"&route="+route+"";	

 });
 });