		 $('#insert').click(function(){

var firstday = $('#date').val();
var lastday = $('#date2').val();
var username = $( "#trip option:selected" ).text().replace(/ /g,"_");
var route = $( "#route option:selected" ).text().replace(/ /g,"_");

	 if (firstday==""){
		 alertify.error("fill Firstday");
alertify.set('notifier','position', 'top-right');
 $("#trip option:selected" ).effect( "shake" );	
 			 control.disabled="disabled";

 } 
 if (lastday==""){
				 alertify.error("fill Lastday");
alertify.set('notifier','position', 'top-right');
 $("#trip option:selected" ).effect( "shake" );
 			 control.disabled="disabled";

		
	 } else if(username == "CHOOSE_USER"){
		 alertify.error("fill Username");
alertify.set('notifier','position', 'top-right');
 $("#trip option:selected" ).effect( "shake" );
 			 control.disabled="disabled";

	 }
$(".search_text").text("Searching...");
window.location="dummy_all.php?firstday="+firstday+"&lastday="+lastday+"&username="+username+"&route="+route+"";	
			
 });