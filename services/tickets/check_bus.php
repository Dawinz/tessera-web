<?php
$bus = $_GET['bus'];
$bus_prev = $_GET['bus_prev'];
include 'connect.php';

$select1=mysqli_query($db,"SELECT *  FROM  manifest WHERE bus='$bus_prev' order by id desc limit 5 ");
$select_num1=mysqli_num_rows($select1);
if($select_num1 !=0){
   	WHILE($get13=mysqli_fetch_array($select1)){
 $id =$get13['id']; 
 $trip_date =$get13['trip_date']; 
 $start_location =$get13['start_location'];
 $end_location =$get13['end_location']; 

?>
   <li class="list-group-item py-3 px-0">
                                                                <div class="d-flex align-items-center">
                                                                    <div class="flex-shrink-0 me-3">
                                                                        <div>
			<input  type="checkbox" class="form-check-input" id="change<?php echo $id;?>">

                                                                        </div><span id="title_name<?php echo $id;?>"></span>
                                                                    </div>
                                                                    <div class="flex-grow-1 overflow-hidden">
                                                                        <h5 class="font-size-14 mb-1 text-truncate"><a href="#" class="text-dark"><?php echo "$start_location to $end_location";?></a></h5>
                                                                        <ul class="list-inline font-size-13 text-muted">
                                                                            <li class="list-inline-item">
                                                                                <a href="#" class="text-muted">
                                                                                    <i class="uil uil-user me-1"></i> <?php echo $trip_date;?>
                                                                                </a>
                                                                            </li>
                                                                            <li class="list-inline-item">
                                                                                <i class="uil uil-calender me-1"></i><?php echo "$bus_prev";?>
                                                                            </li>
                                                                        </ul>
                                                                    </div>
                                                                 
                                                                </div>
                                                            </li>
													  <script>
        $(document).ready(function(){
$("#change<?php echo $id;?>").click(function(){
	var trip_date ="<?php echo $trip_date;?>";
	var bus ="<?php echo $bus;?>";
	var bus_prev ="<?php echo $bus_prev;?>";
	$("#title_name<?php echo $id;?>").text("changing...");
	$.ajax({
				url:"update_manifest_vehicle.php",
				method:"POST",
				data:{trip_date:trip_date,bus:bus,bus_prev:bus_prev},
				success:function(response){
					$("#title_name<?php echo $id;?>").text("");
							  swal({
  
    text: "update Succefull.",
    timer: 1000,
    showConfirmButton: false
  });

	}
			
			});});


        });
        </script>
<?php
}}



?>