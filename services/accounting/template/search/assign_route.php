
<?php

include '../connect/connect.php';
$route_name = $_POST['route_name'];
$username = $_POST['username'];
 $point="";

function center_point($start_pointz_sub,$route_name,$username,$db) {
	
 	$select_checked=mysqli_query($db,"SELECT *   FROM   assign_route   WHERE route_name ='$start_pointz_sub' and main_route='$route_name' and username ='$username'");
$select_num_checked=mysqli_num_rows($select_checked);
if($select_num_checked !=0){
	echo "checked";
}

}
?>
	<div><input type="checkbox" class="checker route_check" id="<?php echo $route_name;?>" <?php center_point($route_name,$route_name,$username,$db);?>>		<?php echo $route_name;?></div>

<?php
$select_sub=mysqli_query($db,"SELECT fullname,id  FROM   route   where main_route='$route_name' order by id desc");
$select_num_sub=mysqli_num_rows($select_sub);
if($select_num_sub !=0){
   	WHILE($get_sub=mysqli_fetch_array($select_sub)){
    	    //$name =$get['name']; 
    	    $fullname_sub =$get_sub['fullname']; 
    	    $id_route =$get_sub['id']; 
			
	?>
	<div><input type="checkbox" class="checker route_check<?php echo $id_route;?>" id="<?php echo $fullname_sub;?>" <?php center_point($fullname_sub,$route_name,$username,$db);?>>		<?php echo $fullname_sub;?></div>
<div style="display:none;"id="route_name<?php echo $id_route;?>"><?php echo $fullname_sub;?></div>


<script>
$(document).ready(function(){
	$(".route_check<?php echo $id_route;?>").click(function(){
		my_id = $(this).attr('id');
		route_name = $("#route_name<?php echo $id_route;?>").text();
		main_route = "<?php echo $route_name;?>";
		username = "<?php echo $username;?>";
	
		$.ajax({
				url:"insert/assign_route.php",
				method:"POST",
				data:{my_id:my_id,route_name:route_name,main_route:main_route,username:username},
				success:function(response){
		
	}
			
			});
	
		
	});
	
	
	$(".select<?php echo $id_route;?>").click(function(){
		    $('.checker').not(this).prop('checked', this.checked);
			alert("asdf");
	});
});
</script>

	<?php		
			
}}
?>
<script>
$(document).ready(function(){
	$(".route_check").click(function(){
		route_name = $(this).attr('id');
		main_route = "<?php echo $route_name;?>";
		username = "<?php echo $username;?>";
		my_id="";
		$.ajax({
				url:"insert/assign_route.php",
				method:"POST",
				data:{my_id:my_id,route_name:route_name,main_route:main_route,username:username},
				success:function(response){
		//alert(response);
	}
			
			});
	});
	
	

});
</script>