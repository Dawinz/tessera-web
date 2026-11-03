$(document).ready(function(){
$("#update_transfer").click(function(){
checd_trip_date = $( "#trip_dates option:selected" ).text();
checked_bus = $( "#transfer_bus option:selected" ).text();
first_bus = $("#checked_bus").text();
first_trip_date = $("#checd_trip_date").text();
checd_route = $("#checd_route").text();
selected_checked = $("#selected_checked").text();
rpoint = $("#rpoint").text();
row_num_route = $("#row_num_route").text();
current_route_c = $("#current_route_c").text();
end_point_c = $("#end_point_c").text();
if((checd_trip_date =="loading...") || (checd_trip_date =="Choose Date") ){
	 
	Swal.fire({
  title: "Date",
  text: "Select trip date",
  icon: "warning"
}); 

control.disabled="disabled";
}
if(checked_bus =="Choose Bus Name"){
		Swal.fire({
  title: "Vehicle",
  text: "Select Bus Name",
  icon: "warning"
}); 
	 control.disabled="disabled";
}
$("#updated_tran_text").text("Tranfering....");
 $.ajax({
    url:"update/transfer.php",
    method:"POST",
    data:{first_bus:first_bus,first_trip_date:first_trip_date,checd_route:checd_route,end_point_c:end_point_c,current_route_c:current_route_c,row_num_route:row_num_route,rpoint:rpoint,selected_checked:selected_checked,checd_trip_date:checd_trip_date,checked_bus:checked_bus,checd_route:checd_route},
    success:function(response){
		$("#transfer_option").modal("hide");
		$("#updated_tran_text").text("Transfer");

      url = $.trim(response);
$("#load_vehicle_account").load("load/vehicle_account.php?"+url);

	   alert(response);

}


}); 

});
});

