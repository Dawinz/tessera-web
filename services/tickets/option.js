$(document).ready(function(){
  

$('#status_em').change(function(){ 

	var status_em = $( "#status_em option:selected" ).text();
	var trip = $( "#trip option:selected" ).text();
   
  
   if(status_em=="To be Payed"){
	   
	   if(trip=="Tunduma to Lubumbashi"){
	  $('#loan_area').show();	   
	   }

	
   }else if(status_em=="paid"){
	      $(".swal-overlay").css("background-color", "rgba(2, 123, 5, 0.61)");
	  swal("Payed!", "You have choose Payed");  
	    $(".swal-overlay").css("background-color", "rgba(2, 123, 5, 0.61)");
   }else if(status_em=="Due payment"){
	   $('#due_modal').modal('show');

	      $(".swal-overlay").css("background-color", "rgba(238, 63, 94, 0.45)");
	    swal("Due Payment!", "...You Have Choose Due Payment!");
			      $(".swal-overlay").css("background-color", "rgba(238, 63, 94, 0.45)");
   }
});


});