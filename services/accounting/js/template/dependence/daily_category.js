$(document).ready(function(){

	 $('#group_cat').change(function(){
  var query = $(this).val();
  $(".catcl").text("Loading...");
 if(query == "Choose Group"){
	 
 }else{
	
	    $.ajax({
    url:"dependence/fetch_daily.php",
    method:"POST",
    data:{query:query},
    success:function(data){
    $('#expenses').html(data);

    }
   });
 
 
 }
 
 if((query=="Lorry (Vehicle)") || (query=="Bus(Vehicle)")) {
  $(".vehicled_text").text("Loading...");
	 	     $.ajax({
    url:"dependence/fetch_vehicles.php",
    method:"POST",
    data:{query:query},
    success:function(data){
    $('#vehicle').html(data);

    }
   });
 }else{

 }
 
});



});