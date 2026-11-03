<?php

?>
<div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Job card</h5>
      </div>
      <div class="modal-body">
	  <div class="row">
             <div class="col"> <input type="date" id ="insert_date_job"class="form-control fc-datepicker start_date datepicker" placeholder="MM/DD/YYYY" placeholder="MM/DD/YYYY" onkeypress="return false" onclick="this.showPicker()"style="margin-right:3px;"value="<?php echo "".date("m/d/Y")."";?>"></div>
     <div class="col">  <select class="form-select choose_route2" id="choose_route2"><option>Choose Route</option><?php echo $route_full_name;?></select></div>
	  </div>
	  </div>
      <div class="modal-footer">

        <button type="button" class="btn btn-primary" id="save"><span id="save_text">Save changes</span></button>
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>     
	 </div>
    </div>
  </div>
</div>