	<?php

		$select_sub=mysqli_query($db,"SELECT *  FROM   route   where main_route='$fullname' and status = 'Sub route'");
$select_num_sub=mysqli_num_rows($select_sub);
if($select_num_sub !=0){
   	WHILE($get_sub=mysqli_fetch_array($select_sub)){
    	    //$name =$get['name']; 
    	    $start_point =$get_sub['start_point']; 
    	    $end_point =$get_sub['end_point']; 
    	    $route_fullname =$get_sub['fullname']; 
			
			$checked ="";
			$active = "None Active";
	$select_checked=mysqli_query($db,"SELECT route_name   FROM   assign_route   WHERE route_name ='$route_fullname' and  username ='admin'");
$select_num_checked=mysqli_num_rows($select_checked);
if($select_num_checked !=0){
	
	$checked ="checked";
				$active = " Active";

}
	
?>
             <li class="list-group-item">
			  <div class="row">
			  <div class="col"><?php echo $route_fullname;?></div>
			  <div class="col">
			  		     <div class="form-check form-switch">
  <input class="form-check-input route_name" type="checkbox" role="switch" id="<?php echo $route_fullname;?>" <?php echo $checked;?> >
  <label class="form-check-label" for="<?php echo $route_fullname;?>"><?php echo $active;?></label>
</div>
			  </div>
			  </div>
			  
	</li>
<?php


}}
	?>