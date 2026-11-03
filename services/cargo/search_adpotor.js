		 $('#date').change(function(){

var firstday = $('#date').val();



$(".search_text").text("Searching...");
window.location="adaptor.php?firstday="+firstday;	
			
 });