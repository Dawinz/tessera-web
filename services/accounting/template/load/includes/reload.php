<script>
$(document).ready(function(){
	$("#reload").click(function(){

num_row = "<?php echo $row_num_route;?>";

bus_name = "<?php echo $bus;?>";
trip_date = "<?php echo $trip_date;?>";
current_route = "<?php echo $current_route;?>";
route_name = "<?php echo $route_name;?>";
start_point = "<?php echo $point;?>";
end_point = "<?php echo $end_point;?>";
function insert_function(bus_name,trip_date,current_route,route_name,start_point,end_point) {
  $("#reload_text").text("reloading....");
  $.ajax({
				url:"./insert/includes/manifest_update.php",
				method:"POST",
				data:{bus_name:bus_name,trip_date:trip_date,current_route:current_route,route_name:route_name,start_point:start_point,end_point:end_point},
				success:function(response){
					alert(response);
					  $("#reload_text").text("Reload");
		$("#reload").notify(
 "Reload Successfull",
 "success",
  { position:"right" }
);
			}

			
			});
}
	
Swal.fire({
  title: "Are you sure?",
  text: "It will reload  paid,to be paid,passenger and cargo entries!",
  icon: "warning",
  showCancelButton: true,
  confirmButtonColor: "#3085d6",
  cancelButtonColor: "#d33",
  confirmButtonText: "Yes, reload it!"
}).then((result) => {
  if (result.isConfirmed) {
insert_function(bus_name,trip_date,current_route,route_name,start_point,end_point);
  }
});	

});
});
</script>