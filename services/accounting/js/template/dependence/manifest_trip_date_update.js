$(document).ready(function(){
	
	 $('#trip_date_update').change(function(){
  var query = $(this).val();

 if(query == "Choose Route"){
	 
 }else{
	 choose_route = $("#choose_route_update option:selected").text();
	    $.ajax({
    url:"dependence/fetch_manifest_vehicle.php",
    method:"POST",
    data:{query:query,choose_route:choose_route},
    success:function(data){
  $("#vehicle_update").html(data);
  

    }
   })
 
 
 }
});
});