<div class="offcanvas offcanvas-top" tabindex="-1" id="offcanvasTop" aria-labelledby="offcanvasTopLabel">
  <div class="offcanvas-header">
    <h5 class="offcanvas-title" id="offcanvasTopLabel">Offcanvas top</h5>
    <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Close"></button>
  </div>
  <div class="offcanvas-body">
  <form id="update_daily" enctype="multipart/form-data">

	<table class="table table-border " id="item_table2">
	<input type="hidden" id="current_route_update" name="id_name">
								<tr>

<td><select name="group_cat_update" class="form-select  group_cat_update" style="" data-live-search="true"id="group_cat_update"><option value="Choose group" id="choose_group_update" value="Choose Group">Choose Group</option><?php echo $cat_group;?></select></td>
<td><select name="expenses_update" class="form-select expenses_update " id="expenses_update" style="" data-live-search="true"><option value="" id="expenses_update_text" class="catcl"></option></select></td>
<td><select  name="item_update" class="form-select item_update " id="item_update" style="" data-live-search="true"><option value="" id="item_update_holder">Choose Item</option></select></td>
<td><input name="quantity_update" type="text" id="quantity_update" class="form-control value_name quantity_update" placeholder="Quantity"></td>
<td><select  name="vehicle_update" class="form-select vehicle_update " id="vehicle_update" style="" data-live-search="true"><option class="Choose Vehicle" id="vehicle_update_text">Choose Vehicle</option></select></td>
<td><input  name="date_update" type="date" class="form-control  datepicker date_update" placeholder="MM/DD/YYYY" id="date_update"onkeypress="return false" onclick="this.showPicker()" ></td>
<td><input name="price_update" type="text" id="amount_update" class="form-control value_name amount" placeholder="price"></td>
<td><input name="detail_update" type="text" id="detail_update" class="form-control  " placeholder="detail"></td>
								
								
								</tr>
							</table>
							
 <div class="modal-footer">
            <button type="submit"class="btn btn-indigo" id="save"><span id="text_change">Save changes</span></button>
            <button type="button" class="btn btn-outline-light" data-bs-dismiss="modal">Close</button>
</div>
		</form>					
  </div>
</div>
<script>
	$(document).on('change', '.group_cat_update', function(){
  var query = $(this).val();
  $('.catcl').text("Loading...");
 if(query == "Choose Group"){
	 
 }else{
	
	    $.ajax({
    url:"dependence/fetch_daily.php",
    method:"POST",
    data:{query:query},
    success:function(data){
    $('#expenses_update').html(data);

    }
   });
 
 
 
 if((query=="Repair and maintenance") || (query=="Bus(Vehicle)")) {
  $('.vehicled_text_update').text("Loading...");

	 	     $.ajax({
    url:"dependence/fetch_vehicles.php",
    method:"POST",
    data:{query:query},
    success:function(data){
    $('#vehicle_update').html(data);

    }
   });
 }else{
  $('#vehicle_update option:selected').text("");
 }

 
 
 }

	});
			$(document).on('change', '.expenses_update', function(){
  var query = $(this).val();
  group_category = $("#group_cat_update").val();
if(group_category=="Repair and maintenance"){
	 $("#item_update").html("<option value='Loading'>Loading..</option>");
  $.ajax({
    url:"dependence/sub_particular.php",
    method:"POST",
    data:{query:query},
    success:function(data){
  $("#item_update").html(data);
    }
   });
}


  });		

</script>