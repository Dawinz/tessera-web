<br>
<?php
include '../connect/connect.php';
$route_name = $_POST['route'];
$current_route = $_POST['route_name'];
$bus = $_POST['bus'];
$trip_date = $_POST['trip_date'];



$select_route_name_current=mysqli_query($db,"SELECT main_route,id,file_name,route_name,ogname,point FROM  attachment  where  main_route='$route_name' and main_route='$current_route' and bus='$bus' and trip_date='$trip_date' ");
$select_num_route_name_current=mysqli_num_rows($select_route_name_current);
if($select_num_route_name_current !=0){
   	WHILE($get_route_name_current=mysqli_fetch_array($select_route_name_current)){
    	    $file_name =$get_route_name_current['file_name']; 
    	    $route_name =$get_route_name_current['route_name']; 
    	    $ogname =$get_route_name_current['ogname']; 
    	    $point =$get_route_name_current['point']; 
    	    $id =$get_route_name_current['id']; 
    	    $main_route =$get_route_name_current['main_route']; 
			?>
			
			<div class="media">
                <div class="media-icon"><a href="upload/uploads/<?php echo $file_name;?>" download><img src="logo/download.png"></a></div>
                <div class="media-body">
                  <span style="font-size:13px;"><?php echo  preg_replace('/_/',' ', $point);?></span><br>
                  <a href="upload/uploads/<?php echo $file_name;?>" ><?php echo $ogname;?></a>
                </div>
				 <div id="delete<?php echo $id;?>"class="media-icon"style="position:absolute;right:0px;"><img src="logo/delete.png"> </div>
				 <span id="deleting_text<?php echo $id;?>"></span>
              </div>
			<script>
			
$(document).ready(function(){
		$("#delete<?php echo $id;?>").click(function(){
			id="<?php echo $id;?>";
			file_name="<?php echo $file_name;?>";
		 $.ajax({
    url:"./delete/delete_attachment.php",
    method:"POST",
    data:{id:id,file_name:file_name},
	   beforeSend: function(){
$("#deleting_text<?php echo $id;?>").text("Deleting...");
            
            },
    success:function(data){
route_name="<?php echo $current_route;?>";
route="<?php echo $route_name;?>";
bus="<?php echo $bus;?>";
trip_date="<?php echo $trip_date;?>";
			 $.ajax({
    url:"search/vehicle_attachment.php",
    method:"POST",
    data:{route_name:route_name,route:route,bus:bus,trip_date:trip_date},
	   beforeSend: function(){
            },
    success:function(data){
$("#vehicle_attachment").html(data);


    }
   }) 


    }
   })

		});
	});
			</script>
			<?php

}}else{

}
?>
	<script>
			
	
			</script>
