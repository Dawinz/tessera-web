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
				echo "<option>Routs no found</option>";
			}else if($select_route_num <= 1){
					echo $route_full_name;
			}else if($select_route_num > 1){
					echo "<option>Choose Route</option>";
	echo $route_full_name;
			}
		
			
			?>
              </select>
			  </div><!-- col --> 
			   <div class="col-lg">
			                  <label class="az-content-label tx-11 tx-medium tx-gray-600">Trip Date</label>
              <br><select class="form-control select2" id="trip_date" style="width: 100%;" name="category">
                <option value="Main Route">Main Route</option>
                <option value="Sub Route">Sub Route</option>
            
			
              </select>
			  </div> 
			  <div class="col-lg">
			                  <label class="az-content-label tx-11 tx-medium tx-gray-600">Vehicle Name</label>
              <br><select class="form-control select2" id="vehicle" style="width: 100%;" name="category">
                <option value="Choose Route Category">Trip Date</option>
               
            
			
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
if(choose_route=="Routs no found"){
	
}else if(choose_route=="Choose Route"){
	
}else{
	
	    $.ajax({
    url:"./dependence/fetch_manifest_dependence.php",
    method:"POST",
    data:{choose_route:choose_route},
    success:function(data){


    }
   })
}
          });
	
	</script>