
<div class="offcanvas offcanvas-top" tabindex="-1" id="offcanvasTop" aria-labelledby="offcanvasTopLabel" style="height:300px;">
  <div class="offcanvas-header">
    <h5 id="offcanvasTopLabel">Update Entries</h5>
    <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas" aria-label="Close"></button>
  </div>
  <div class="offcanvas-body">
  <form id="update_form" enctype="multipart/form-data">
  <input type="hidden"name="row_number" id="row_number">
  <input type="hidden"name="end_point_edite" id="end_point_edite">
	<table class="table table-bordered" id="update_entries">
								<tr>


									<th>Particular Category</th>
									<th>Particular </th>
									<th>Currency </th>
									<th>Amount </th>
									<th>Detail </th>
									
									<th></th>
								</tr>
						<tr>
						<td><select name="part_Category_update" class="form-select part_Category_update" id="" style="" data-live-search="true"><option id="part_Category_update"value="Choose Category">Choose Category</option><?php echo $particular_category_all; ?></select></td>
						<td><select name="part_detail_update" class="form-select part_detail_update" id="" style="" data-live-search="true"><option id="part_detail_update"value="Choose Category">Choose Category</option></select></td>
						<td><select name="part_Currency" class="form-select particular_currency_update" id="particular_currency" style="" data-live-search="true"><option id="currency_update"></option><?php echo $particular_Currency;?></select></td>
						<td><input name="amount" type="text" class="form-control value_name amount amount_income_update" /></td>
						<td><input name="part_id"type="hidden" class="part_id"><input name="detail" type="text" class="form-control value_name detail_update" ></td>
						</tr>
							</table>
											 <div class="modal-footer">
            <button type="submit"class="btn btn-indigo" id="update">Update changes</button>
            
          </div>
		  </form>
 </div>
</div>
<script src="/accounts/js/template/update/vehicle_account.js"></script>

<script>

$(document).on('change','.part_Category_update', function(){
	part_category = $(this).val();
	    $.ajax({
    url:"./dependence/fetch_all_part.php",
    method:"POST",
    data:{part_category:part_category},
	   beforeSend: function(){
	$(".part_detail_update option:selected" ).text("loading...");
            
            },
    success:function(data){
$('.part_detail_update').html(data);
    }
   })
	});

</script>