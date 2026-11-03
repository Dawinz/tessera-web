<div id="myModal" class="modal fade bs-example-modal-center" tabindex="-1" role="dialog"
                                            aria-labelledby="myModalLabel" aria-hidden="true">
                                            <div class="modal-dialog">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title" id="myModalLabel">Manifest Vehicle registration</h5>
                                                        <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                            aria-label="Close">

                                                        </button>
                                                    </div>
                                                    <div class="modal-body">
										
												           
						 <label class="form-label" for="validationCustom02">Select Route</label>

                                                   <select name="route" id="route_insert" class="form-select"  data-live-search="true"><?php
	if(isset($_GET['route'])){
		echo "  <option>".$_GET['route']."</option>";
$select12=mysqli_query($db,"SELECT *  FROM  assigned_routes where username='$username'");
$select_num12=mysqli_num_rows($select12);
if($select_num12 ==0){

}else if($select_num12 ==1){
	
	   	WHILE($get132=mysqli_fetch_array($select12)){
$route_full_name =$get132['route_full_name']; 
echo "  <option>".$route_full_name."</option>";
		}
}else if($select_num12 >=2){

	   	WHILE($get132=mysqli_fetch_array($select12)){
$route_full_name =$get132['route_full_name']; 
echo "  <option>".$route_full_name."</option>";
}
}
	}else{								   
									   $select12=mysqli_query($db,"SELECT *  FROM  assigned_routes where username='$username'");
$select_num12=mysqli_num_rows($select12);
if($select_num12 ==0){

}else if($select_num12 ==1){
	
	   	WHILE($get132=mysqli_fetch_array($select12)){
$route_full_name =$get132['route_full_name']; 
echo "  <option>".$route_full_name."</option>";
		}
}else if($select_num12 >=2){

	   	WHILE($get132=mysqli_fetch_array($select12)){
$route_full_name =$get132['route_full_name']; 
echo "  <option>".$route_full_name."</option>";
}
}
	}
									   ?></select><br>
  <div class="bootstrap-select fm-cmp-mg">
                                          <label class="nk-label">Vehicle Number</label>

                                    <select class="form-select"data-live-search="true"id="vehicle">
										<option>Enter Vehicle Name</option>
										<?php echo  $country;?>
										</select>
                                </div>      
  <br>  
                  <div class="row">
                       
                                <div class="form-group ic-cmp-int float-lb floating-lb">
                                    <div class="form-ic-cmp">
                                    </div>
                                    <div class="nk-int-st">
                                        <label class="nk-label">Driver's Name</label>

                                        <input type="text" class="form-control"id="driver">
										<div id="view_place"style="position:absolute;background-color:white;z-index:1000;display:none;">
<br>
</div>
                                    </div>
                                </div> 
								<div class="form-group ic-cmp-int float-lb floating-lb">
                                    <div class="form-ic-cmp">
                                    </div>
                                    <div class="nk-int-st">
  <label class="nk-label"id='chide'>Conductor's name</label>
                                        <input type="text" class="form-control"id="conductor">
                                      <br>
                                    </div>
                                </div>
								<div class="form-group ic-cmp-int float-lb floating-lb">
                                    <div class="form-ic-cmp">
                                    </div>
                                    <div class="nk-int-st">


                                <div class="form-group nk-datapk-ctm form-elet-mg" id="data_1">
                                    <div class="input-group date nk-int-st">
                                        <span class="input-group-addon"></span>
										
                                        <input type="text" class="form-control" id="time2">
                                    </div>
                                </div>
					<br>
                        <div class="material-design-btn">
                          
                            <button class="btn btn-primary"style="padding-top:10px;padding-bottom:10px;"id="savebtn2">Register Manifest</button>
                       
                        </div>
                    </div>                                    </div>
                                </div>

								
                                                    </div>
                                           

                                                </div><!-- /.modal-content -->
                                            </div><!-- /.modal-dialog -->
                                        </div>
                                        <script>
$("#vehicle").change(function(){
  vehicle_value = $(this).val();
   $.ajax({
    url:"dependence/driver.php",
    method:"POST",
    data:{vehicle_value:vehicle_value},
    success:function(data){
    $('#driver').val(data);

    }
   })
   $.ajax({
    url:"dependence/conductor.php",
    method:"POST",
    data:{vehicle_value:vehicle_value},
    success:function(data){
    $('#conductor').val(data);

    }
   })
})

                                        </script>