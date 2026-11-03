$(document).ready(function(){

	$(".mark_manifest").click(function(){
		bus = $("#checked_bus").text();
	trip_date = $("#checd_trip_date").text();
	start_point = $("#rpoint").text();
	

	

	 $.ajax({
    url:"update/update_seen.php",
    method:"POST",
    data:{bus:bus,trip_date:trip_date,start_point:start_point},
	   beforeSend: function(){
		
		my_seen = $(".eyeclass").attr('id');
if(my_seen=="change_color_grey"){
	$(".eyeclass").removeAttr('id');
	$('.eyeclass').attr('id', 'change_color_red');
}else{
$(".eyeclass").removeAttr('id');
$('.eyeclass').attr('id', 'change_color_grey');

}
		
            },
    success:function(data){
alert(data);

    }
   })
	
	
	});
	
	
});