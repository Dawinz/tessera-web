$(document).ready(function(){

	$(".mark_jobcard").click(function(){
		date = $("#date_update").text();
		route_name = $("#route_name_update").text();
 $.ajax({
    url:"update/update_seen_jobcard.php",
    method:"POST",
    data:{date:date,route_name:route_name},
	   beforeSend: function(){
		
		my_seen = $(".eyeclass").attr('id');
if(my_seen=="change_color_grey"){
	$(".eyeclass").removeAttr('id');
	$('.eyeclass').attr('id', 'change_color_red');
}else{
$(".eyeclass").removeAttr('id');
$('.eyeclass').attr('id', 'change_color_grey');

$(".results_eye").text("changing..");
}
		
            },
    success:function(data){
		
$(".results_eye").text(data);
	}
   })


	});
	
	
});