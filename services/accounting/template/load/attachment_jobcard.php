<?php
include '../connect/connect.php';
$route_name = $_GET['route_name'];
$route_name = preg_replace('/_/',' ', $route_name);

$date = $_GET['date'];
$select_route_name_current=mysqli_query($db,"SELECT main_route,id,file_name,route_name,ogname,point FROM  attachment  where  route_name='$route_name'  and trip_date='$date' ");
$select_num_route_name_current=mysqli_num_rows($select_route_name_current);
if($select_num_route_name_current !=0){
   	WHILE($get_route_name_current=mysqli_fetch_array($select_route_name_current)){
    	    $id =$get_route_name_current['id']; 
    	    $file_name =$get_route_name_current['file_name']; 
    	    $route_name =$get_route_name_current['route_name']; 
    	    $ogname =$get_route_name_current['ogname']; 
			?>
		<div class="media">
                <div class="media-icon"><a href="upload/uploads/jobcard/<?php echo $file_name;?>" download><img src="logo/download.png"></a></div>
                <div class="media-body">
                  <span style="font-size:13px;"></span>
                  <a href="upload/uploads/jobcard/<?php echo $file_name;?>" target="_blank"><?php echo $ogname;?></a>
                </div>
				 <div id="delete<?php echo $id;?>"class="media-icon"style="position:absolute;right:0px;"><img src="logo/delete.png"></div>
              </div>
			<span id="tdelte"></span>
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
$("#tdelte").text("deleting...");
            
            },
    success:function(data){
$("#load_attachment").load("load/attachment_jobcard.php?route_name=<?php echo str_replace(" " , "_",  $_GET['route_name']);?>&date=<?php echo $_GET['date'];?>");

    }
   })

		});
	});
			</script>
			<?php
			
			
}}else{
	echo "<span style='color:grey;'>No attachment</span>";
}



?>