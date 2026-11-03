     <div class="offcanvas offcanvas-top" tabindex="-1" id="offcanvasTop" aria-labelledby="offcanvasTopLabel"style="height:350px;">
  <div class="offcanvas-header">
    <h5 id="offcanvasTopLabel">Update Constants</h5>
    <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas" aria-label="Close"></button>
  </div>
  <div class="offcanvas-body">
<form id="signup" enctype="multipart/form-data">

          <div class="modal-body">
<div id="id_no" style="display:none;"></div>

	<table class="table table-bordered" id="item_table_update">
								<tr>
									<th>Catgory</th>
									<th>Name</th>
									<th>Route</th>
									<th>Route Category</th>
									<th>Currency</th>	
									
									
									
									
									<th>Affect</th>
																	
									<th>Vehicle</th>
																		<th>Value</th>

								</tr>
								<tbody>
								<tr>
								<tr>
								<td><select name="part_Category[]" class="form-select  " id="part_Category_updat" style="" data-live-search="true"><option id="part_Category_update" value="Choose Category">Choose Category</option><?php echo $name_category; ?></select></td>
								<td><select name="name[]" class="form-select  " id="particular_name_update" style="" data-live-search="true"><option value="Choose particular"id="part_particular_update">Choose particular</option><?php echo $name_particulars; ?></select></td>
								<td><select name="route_name[]" class="form-select  " id="choose_route_update" style="" data-live-search="true"><option id="choose_route_update2"value="Choose route name"> Choose route name</option><?php echo $route_full_name; ?></select></td>
								<td><select class="form-select" id="route_category_update" style="width: 100%;" name="route_category[]"><option id="route_category_update23" value="Route Category"></option><option value="Main">Main Route</option><option value="sub_route">Sub Route</option></select></td>
								<td><select name="currency[]" class="form-select  " id="currency_v_update" style="" data-live-search="true"><option id="currency_v_update23" value="Choose Currency">Choose Currency</option><?php echo $name_account; ?></select></td>
								<td><select name="deduction[]" class="form-select  " id="affect_update" style="" data-live-search="true"><option id="affect_update2"value="Choose Affect Area">Choose Affect Area</option><option value="To be Paid">To be Paid</option><option value="Paid">Paid</option></select></td>
								<td><select name="vehilce_category[]" class="form-select  " id="vehicle_update" style="" data-live-search="true"><option id="vehicle_update2" value="Choose Vehicle">Choose Vehicle</option><option>Bus</option><option>Truck</option></select></td>
								<td><input name="value[]" type="text" class="form-control value_name amount" id="amount_update" ></td>
								</tr>
								</tr>
								</tbody>
							</table>

		  </div>
          <div class="modal-footer">
            <button type="button"class="btn btn-indigo" id="udpdate">Save changes</button>
          </div>
		  </form>
  </div>
</div>