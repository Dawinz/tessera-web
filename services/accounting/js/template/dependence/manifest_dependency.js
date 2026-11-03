$(document).ready(function(){

	 $('#choose_route').change(function(){
  var query = $(this).val();
 if(query == "Choose Route"){
	 
 }else{
	 route_name = query;
	    $.ajax({
    url:"dependence/fetch_manifest_trip_date.php",
    method:"POST",
    data:{route_name:route_name},
    success:function(data){
    $('#trip_date').html(data);

    }
   })
 
 
 }
});
});