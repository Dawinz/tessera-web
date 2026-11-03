

<div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-fullscreen">
    <div class="modal-content">
	
    <div class="modal-header">
        <h5 class="modal-title">Daily Expenses</h5>
      <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
          <div class="modal-body">
		   <form id="insert_form" enctype="multipart/form-data">

              <!-- media -->
           <!-- media -->
       
<input type="hidde" id="route_name" class="route_name_in"name="route_name">
<div class="table-repsonsive">
							<span id="error"></span>
							<table class="table table-border " id="item_table2">
								<tr>
									<th>Category</th>
									<th>Particular</th>
									<th class="vehicleth">Vehcile</th>
									<th class="">Account</th>
									<th>Date</th>
									<th>Amount</th>
									<th>Detail</th>
									
									<th><button type="button" name="add_new" class="btn btn-success btn-sm add_new "><i class="fas fa-plus"></i></button></th>
								</tr>
							</table>
						
						</div>


		  </div>
          <div class="modal-footer">
            <button type="submit"class="btn btn-indigo" id="save">Save changes</button>
            <button type="button" class="btn btn-outline-light" data-bs-dismiss="modal">Close</button>
          </div>
		  </form>
    </div>
  </div>
</div>
	<script src="/accounts/js/template/insert/daily.js"></script>


 <script>
        $(document).ready(function(){

			var count = 0;
	
	function add_input_field(count)
	{

		var html = '';

		html += '<tr>';

		html += '<td><select name="group_cat[]" class="form-select  group_cat" style="" data-live-search="true"id="group_cat'+count+'"><option value="Choose group" id="choose_group" value="Choose Group">Choose Group</option><?php echo $cat_group;?></select>	</td>';
		html += '<td><div class="row"><div class="col"><select name="expenses[]" class="form-select expenses " id="expenses'+count+'" style="" data-live-search="true"><option value="Select Particular" class="catcl'+count+'">Select Particular</option></select></div><div class="col item_select'+count+'" style="display:none;"><select  name="item[]" class="form-select item " id="item'+count+'" style="" data-live-search="true"><option value="Choose Item" class="item'+count+'">Choose Item</option></select></div><div class="col item_select'+count+'" style="display:none;"><input name="quantity[]" type="text" id="quantity" class="form-control value_name quantity'+count+'" placeholder="Quantity"></div></div></select></td>';
		html += '<td class="vehicletr'+count+'" style="" ><select   name="vehicle[]" class="form-select vehicle " id="vehicle'+count+'" style="" data-live-search="true"value=""><option value="Choose Vehicle" class="vehicled_text'+count+'">Choose Vehicle</option></select></td>';
		html += '<td class="account'+count+'" style="" ><select   name="account[]" class="form-select account " id="account'+count+'" style="" data-live-search="true"value="Choose Account"><option value="Cash" class="cash">Cash</option><option value="Cheque" class="vehicled_text">Cheque</option></select></td>';
		html += '<td><input  name="date[]" type="date" id="date"class="form-control  datepicker date" placeholder="MM/DD/YYYY" onkeypress="return false" onclick="this.showPicker()" ></td>';
		html += '<td><input name="price[]" type="text" id="amount" class="form-control value_name amount" placeholder="price"></td>';
		html += '<td><input name="detail[]" type="text" id="detail" class="form-control  " placeholder="detail"></td>';

		var remove_button = '';

		if(count > 0)
		{
			remove_button = '<button type="button" name="remove" class="btn btn-danger btn-sm remove"><i class="fas fa-minus"></i></button>';
		}

		html += '<td>'+remove_button+'</td></tr>';

		return html;

	}
		$('#item_table2').append(add_input_field(0));	
			
			
	
			$(document).on('click', '.remove', function(){

		$(this).closest('tr').remove();

	});	
			
			
current_route = $(".current_route").val();	

function route_name(current_route,count) {
 
		    $.ajax({
    url:"dependence/fetch_days.php",
    method:"POST",
    data:{current_route:current_route},
    success:function(data){
    $('#sdate'+count).html(data);

    }
   })

}	
				
	$(document).on('click', '.add_new', function(){

		count++;
		
		$('#item_table2').append(add_input_field(count));
route_name(current_route,count);
		$('.selectpicker').selectpicker('refresh');
		

		
		
		//route_name(current_route);


	});	
	route_name(current_route,count);
			
			$(document).on('change', '.group_cat', function(){
		my_id = $(this).attr('id')
my_id = my_id.replace(/\D/g,'');

  var query = $(this).val();
  $('.catcl'+count+'').text("Loading...");
 if(query == "Choose Group"){
	 
 }else{
	
	    $.ajax({
    url:"dependence/fetch_daily.php",
    method:"POST",
    data:{query:query},
    success:function(data){
    $('#expenses'+my_id+'').html(data);

    }
   });
 
 
 }
 

 if((query=="Repair and maintenance") || (query=="Bus")) {

  //$('.vehicled_text'+count+'').text("Loading...");
	 	     $.ajax({
    url:"dependence/fetch_vehicles.php",
    method:"POST",
    data:{query:query},
    success:function(data){
    $('#vehicle'+my_id+'').html(data);
    $('.item_select'+my_id+'').show();

    }
   });
 }else{
  $('#vehicle'+count+' option:selected').text("");
      $('.item_select'+my_id+'').hide();

 }


	});
			
			$(document).on('change', '.expenses', function(){
my_id = $(this).attr('id')
my_id = my_id.replace(/\D/g,'');

  var query = $(this).val();
group_category = $("#group_cat"+my_id).val();
if(group_category=="Repair and maintenance"){
	  $('#item'+count+'').text("Loading...");

     $.ajax({
    url:"dependence/sub_particular.php",
    method:"POST",
    data:{query:query},
    success:function(data){
    $('#item'+my_id+'').html(data);
    }
   });}
  });
 		
			
         
          });
		  
 
	
    </script>
