        

		<ul class="list-group list-group-flush">
								<?php
												include 'connect.php';
												$username=$_GET['username'];
												$route_id_assigned="";
												$id2="";
											
$select17=mysqli_query($db,"SELECT *  FROM  routes ");
$select_num17=mysqli_num_rows($select17);
if($select_num17 !=0){
   	WHILE($get137=mysqli_fetch_array($select17)){
 $id=$get137['id']; 
 $start_route= $get137['start_route']; 
 $end_route= $get137['end_route']; 
 $fare_price= $get137['fare_price']; 
 $route_id= $get137['route_id'];  
 $detail= $get137['detail']; 


$select12=mysqli_query($db,"SELECT *  FROM  assigned_routes where route_id ='$route_id' and username='$username'");
$select_num12=mysqli_num_rows($select12);
if($select_num12 !=0){
   	WHILE($get132=mysqli_fetch_array($select12)){
$route_id_assigned =@$get132['route_id'];
 $id2 =@$get132['id']; 
 $preference =@$get132['preference'];  
 $status =@$get132['status']; 
}}
												?>		
												
											
     <li class="list-group-item py-3 px-0">
                                                                <div class="d-flex align-items-center">
                                                                    <div class="flex-shrink-0 me-3">
                                                                        <div>
			<input <?php if($route_id==$route_id_assigned){ echo "checked";}else{}?> type="checkbox" class="form-check-input" id="change<?php echo $id;?>">

                                                                        </div>
                                                                    </div>
                                                                    <div class="flex-grow-1 overflow-hidden">
                                                                        <h5 class="font-size-14 mb-1 text-truncate"><a href="#" class="text-dark"><?php // echo base64_decode($detail);?></a></h5>
                                                                        <ul class="list-inline font-size-13 text-muted">
                                                                            <li class="list-inline-item">
                                                                                <a href="#" class="text-muted">
                                                                                    <i class="uil uil-user me-1"></i> <?php echo $start_route;?>
                                                                                </a>
                                                                            </li>
                                                                            <li class="list-inline-item">
                                                                                <i class="uil uil-calender me-1"></i><?php echo "$end_route";?>
                                                                            </li>  
																			
																			
																			<li class="list-inline-item">
                                                                               <?php
																			   if($route_id==$route_id_assigned){
																				 ?>
																				 									   <div class="form-check form-switch form-switch-md mb-2" dir="ltr">
                                                        <input type="checkbox" class="form-check-input" id="customSwitchsizemd<?php echo $id;?>"value = "<?php if($preference==1){ echo "checked";}else{}?>" <?php if($preference==1){ echo "checked";}else{}?> >
                                                        <label class="form-check-label" for="customSwitchsizemd22">Main route</label>
                                                    </div>
<?php																				 
																			   }
																			   
																			   ?>
											
                                                                            </li>				
																			<li class="list-inline-item">
                                                                        							   <div class="form-check form-switch form-switch-md mb-2" dir="ltr">
                                                        <input type="checkbox" class="form-check-input" id="customSwitchsizemd_status<?php echo $id;?>"<input <?php if(@$status=="receiver"){ echo "checked";}else{}?>>
                                                        <label class="form-check-label" for="customSwitchsizemd22">Luggage Sender or Receiver</label>
                                                    </div>

											
                                                                            </li>
                                                                        </ul>
                                                                    </div>
                                                                 
                                                                </div>
                                                            </li>
															

  
															  <script>
        $(document).ready(function(){
$("#change<?php echo $id;?>").click(function(){

	if ($('#change<?php echo $id;?>').prop('checked')) {
    //blah blah


$("#change<?php echo $id;?>").val("on");
}else{
		
		$("#change<?php echo $id;?>").val("");
}

var username_names = $("#username_names option:selected" ).text();

	if(username_names=="List of user in the system"){
 swal("", "Choose username !", "warning");

			 control.disabled="disabled";	}
	var change= $("#change<?php echo $id;?>").val();

	var route_id_assigned="<?php echo $route_id;?>";
	$.ajax({
				url:"change_assigned.php",
				method:"POST",
				data:{change:change,route_id_assigned:route_id_assigned,username_names:username_names},
				success:function(response){
					
if($.trim(response) == "succesfull"){
					
							  swal({
  
    text: "update Succefull.",
    timer: 1000,
    showConfirmButton: false
  });
	
$("#load_username_roles").load("user_routes.php?username="+username_names);
  $("#register_user").text("Register");
						}else{
							swal("Found", "Trip found !", "warning");
						}				}
			});
});

	$('#customSwitchsizemd<?php echo $id;?>').click(function(){
	var check = $("#customSwitchsizemd<?php echo $id;?>").val();

    var id = "<?php echo $id2;?>";
	var route_id_assigned="<?php echo $route_id;?>";
	var username_names = $("#username_names option:selected" ).text();
	
		$.ajax({
				url:"update_preference.php",
				method:"POST",
				data:{check:check,route_id_assigned:route_id_assigned,username_names:username_names,id:id},
				success:function(response){
		
 $("#load_username_roles").load("user_routes.php?username="+username_names);

		}
			});
	
	
});


$('#customSwitchsizemd_status<?php echo $id;?>').click(function(){
	 var id2 = "<?php echo $id2;?>";
	if ($('#customSwitchsizemd_status<?php echo $id;?>').prop('checked')) {
    //blah blah


$("#customSwitchsizemd_status<?php echo $id;?>").val("on");
	var check2 = $("#customSwitchsizemd_status<?php echo $id;?>").val();

}else{
		
		$("#customSwitchsizemd_status<?php echo $id;?>").val("");
			var check2 = $("#customSwitchsizemd_status<?php echo $id;?>").val();

}

		$.ajax({
				url:"update_status_sender.php",
				method:"POST",
				data:{check2:check2,id2:id2},
				success:function(response){
						alert(response);
						var username_names = $("#username_names option:selected" ).text();
 $("#load_username_roles").load("user_routes.php?username="+username_names);

		}
			}); 

 });
        });
        </script>
															<?php
															}}
															?>
															</ul>