$(document).ready(function(){
	 $('#choose_route').change(function(){
  var query = $(this).val();
 if(query == "Choose Route"){
	 
 }else{

	 
	    $.ajax({
    url:"dependence/fetch_dependency.php",
    method:"POST",
    data:{query:query},
    success:function(data){
    $('#Main_route').html(data);

    }
   })
 
 
 }
});
});