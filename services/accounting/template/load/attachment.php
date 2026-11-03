<?php
include '../connect/connect.php';
$route_name = $_GET['route_name'];
$current_route = $_GET['current_route'];
$bus = $_GET['bus'];
$trip_date = $_GET['trip_date'];



$select_route_name_current=mysqli_query($db,"SELECT main_route,id,file_name,route_name,ogname,point FROM  attachment  where  route_name='$route_name' and main_route='$current_route' and bus='$bus' and trip_date='$trip_date' ");
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
                  <span style="font-size:13px;"><?php echo  preg_replace('/_/',' ', $point);?></span>
                  <a href="upload/uploads/<?php echo $file_name;?>" ><?php echo $ogname;?></a>
                </div>
				 <div id="delete<?php echo $id;?>"class="media-icon"style="position:absolute;right:0px;"><img src="logo/delete.png"></div>
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

            
            },
    success:function(data){
$("#load_attachment").load("load/attachment.php?point=<?php echo str_replace(" " , "_",  $point);?>&route_name=<?php echo str_replace(" " , "_",  $main_route);?>&trip_date=<?php echo $trip_date;?>&bus=<?php echo $bus;?>&main_route=<?php echo str_replace(" " , "_",  $main_route);?>&current_route=<?php echo str_replace(" " , "_",  $route_name);?>");


    }
   })

		});
	});
			</script>
			<?php

}}else{
	echo "<span style='color:grey;'>no attachment</span>";
}
?>
	<script>
			
	
			</script>
