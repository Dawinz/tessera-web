$(document).ready(function(){
current_route = $(".current_route").val();

	    $.ajax({
    url:"dependence/fetch_days.php",
    method:"POST",
    data:{current_route:current_route},
    success:function(data){
    $('.sdate').html(data);

    }
   })

});