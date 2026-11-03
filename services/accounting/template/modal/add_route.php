  <div id="modaldemo1" class="modal " tabindex="-1">
      <div class="modal-dialog modal-lg" role="document" >
        <div class="modal-content modal-content-demo">
          <div class="modal-header">
            <h6 class="modal-title">Add Manifest</h6>
            <button type="button" class="close " data-bs-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
		   <form id="signup" enctype="multipart/form-data">

          <div class="modal-body">
            
 <div class="row row-sm">
            <div class="col-lg">
			                  <label class="az-content-label tx-11 tx-medium tx-gray-600">Route Name</label>
              <br><select class="form-control select2 " id="choose_route" style="width: 100%;" name="route">
        
            
			<?php 
			if($select_route_num <= 0){
				echo '<option value="">Routes not found</option>';
			}else if($select_route_num <= 1){
					echo $route_full_name;
			}else if($select_route_num > 1){
					echo '<option value="">Choose Route</option>';
	echo $route_full_name;
			}
		
			
			?>
              </select>
			  </div><!-- col --> 
			   <div class="col-lg">
			                  <label class="az-content-label tx-11 tx-medium tx-gray-600">Trip Date</label>
              <br><select class="form-control select2" id="trip_date" name="trip_date" style="width: 100%;" name="category">
                <option value ="Select Trip Date">Select Trip Date</option>
            
			
              </select>
			  </div> 
			  <div class="col-lg">
			                  <label class="az-content-label tx-11 tx-medium tx-gray-600">Vehicle Name</label>
              <br><select class="form-control select2" id="vehicle2" name="vehicle_name"style="width: 100%;" name="category">
                              <option value ="Select Trip Date">Select Vehicle</option>

               
            
			
              </select>
			  </div> <div class="col-lg">
			                  <label class="az-content-label tx-11 tx-medium tx-gray-600">Type</label>
              <br><select class="form-control select2" id="vehicle_cag" name="vehicle_cag"style="width: 100%;" name="category">
                              <option value ="Select Type">Select Type</option>
                              <option value ="Bus">Bus</option>
                              <option value ="Truck">Truck</option>

               
            
			
              </select>
			  </div><!-- col -->
       
			
			
			<!-- col -->
          <!-- col -->
          </div>         


		  </div>
          <div class="modal-footer">
            <button type="submit"class="btn btn-indigo" id="save">Save changes</button>
            <button type="button" class="btn btn-outline-light" data-bs-dismiss="modal">Close</button>
          </div>
		  </form>
        </div>
      </div><!-- modal-dialog -->
    </div>
	<script>
	$(document).ready(function(){
		    $('.select2').select2({
  
            searchInputPlaceholder: 'Search',
			dropdownParent: $("#modaldemo1")
          });
		  
 var choose_route = $( "#choose_route option:selected" ).text();
 
if(choose_route=="Routes not found"){
	
}else if(choose_route=="Choose Route"){
	
}else{
	route_name = choose_route;
	    $.ajax({
    url:"./dependence/fetch_manifest_trip_date.php",
    method:"POST",
    data:{route_name:route_name},
    success:function(data){

$("#trip_date").append(data);
    }
   })
}
          });
	
	</script>