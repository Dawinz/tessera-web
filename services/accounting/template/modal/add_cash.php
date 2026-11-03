  <div id="modaldemo1" class="modal " tabindex="-1">
      <div class="modal-dialog modal-lg" role="document" >
        <div class="modal-content modal-content-demo">
          <div class="modal-header">
            <h6 class="modal-title">Add Cash in Hand</h6>
            <button type="button" class="close " data-bs-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
		   <form id="signup" enctype="multipart/form-data">

          <div class="modal-body">
            
   <div class="row">
            

			 <div class="col"> <label>Route</label>
<select class="form-control select2 " name="route_name"id="choose_route" style="width: 100%;" name="route"><option>Choose Route</option><?php 
		
	echo $route_full_name;
		
		
			
			?></select>			 </div>
			
			  <div class="col">

			  <label>Date</label>
			 <input type="text" name="insert_date" id ="insert_date"class="form-control fc-datepicker start_date datepicker" placeholder="MM/DD/YYYY" style="margin-right:3px;"value="<?php echo "".date("m/d/Y")."";?>">
			 </div>  
             <div class="col"> 
			 <label>Authorizer</label>
			 <input type="text" name = "Authorizer"id ="Authorizer"class="form-control " placeholder="Authorizer" style="margin-right:3px;"value=""></div>
             <div class="col"> 
			 			 <label>Cash</label>

			 <input type="text" name="amount"id ="amount"class="form-control amount" placeholder="Amount" style="margin-right:3px;"value=""></div>
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
 
if(choose_route=="Routes not found" || choose_route=="Routs no found"){
	
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