$(document).ready(function(){
$("#delete_all").click(function(){
selected_checked = $("#selected_checked").text();
checked_bus = $("#checked_bus").text();
checd_trip_date = $("#checd_trip_date").text();
checd_route = $("#checd_route").text();
rpoint = $("#rpoint").text();
row_num_route = $("#row_num_route").text();
current_route_c = $("#current_route_c").text();
end_point_c = $("#end_point_c").text();
$("#delete_text").text("deleting....");

 $.ajax({
    url:"delete/delete_mult.php",
    method:"POST",
    data:{checd_route:checd_route,end_point_c:end_point_c,current_route_c:current_route_c,row_num_route:row_num_route,rpoint:rpoint,selected_checked:selected_checked,checd_trip_date:checd_trip_date,checked_bus:checked_bus},
    success:function(response){
		$("#delete_text").text("Delete");

       
url = $.trim(response);
$("#load_vehicle_account").load("load/vehicle_account.php?"+url);

	   alert(response);

}


}); 


});
});