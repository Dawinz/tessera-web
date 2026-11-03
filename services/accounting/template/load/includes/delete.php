		<script>
			$("#delete<?php echo $id;?>").click(function(){
       var id= "<?php echo $id;?>";
	   Swal.fire({
  title: "Are you sure?",
  text: "You won't be able to revert this!",
  icon: "warning",
  showCancelButton: true,
  confirmButtonColor: "#3085d6",
  cancelButtonColor: "#d33",
  confirmButtonText: "Yes, delete it!"
}).then((result) => {
  if (result.isConfirmed) {
	    $.ajax({
    url:"./delete/delete_entry.php",
    method:"POST",
    data:{id:id},
	   beforeSend: function(){
		   $("#delete<?php echo $id;?>").text("Deleting...");
            },
    success:function(data){
		alert(data);
$("#load_vehicle_account").load("load/vehicle_account.php?point=<?php echo str_replace(" " , "_",  $point);?>&route_name=<?php echo str_replace(" " , "_",  $route_name);?>&trip_date=<?php echo $trip_date;?>&bus=<?php echo $bus;?>&main_route=<?php echo str_replace(" " , "_",  $main_route);?>&current_route=<?php echo str_replace(" " , "_",  $current_route_currency);?>&row_num=<?php $row_num_route;?>&end_point=<?php echo $end_point;?>");

    }
   })
  }
});

			});
			</script>