
<span id="currency_holder"style="display:none;"></span>

<!-- Modal -->
<div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content" style="width:1200px;right:320px; ">
      <div class="modal-header entrie_header">
        <h5 class="modal-title" id="exampleModalLabel"><span class="income_type"></span></h5>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
          <span id="route_currency_text"></span>          
	  <form id="insert_form" enctype="multipart/form-data">
	<input name ="point_value"type="hidden"id="point_value"value="<?php echo $point;?>"></input>
		<input name="busname"type="hidden"id="busname"value="<?php echo $_GET['bus'];?>"></input>
		<input name="trip_date"type="hidden"id="trip_date"value="<?php echo $_GET['trip_date'];?>"></input>
		<input name="route_name"type="hidden"id="route_name"value="<?php echo $_GET['route_name'];?>"></input>
		<input name="main_route"type="hidden"id="main_route"value="<?php echo  $_GET['route_name'];?>"></input>
		<input name="current_route"type="hidden"id="current_route"value=""></input>
		<input name="end_point"type="hidden"id="end_point"value=""></input>
		<input name="start"type="hidden"id="start"value=""></input>
		<input type="hidden" name="acc_type" id="acc_type">
	<table class="table " id="income_table">
								<tr>
<th></th>

									<th>Particular Category</th>
									<th>Particular </th>
									<th>Currency </th>
									<th>Amount </th>
									<th>Detail </th>
									
									<th><button type="button" name="add" class="btn btn-success btn-sm add_table"><i class="fas fa-plus"></i></button></th>
								</tr>
							
							</table>
	 <div class="modal-footer">
        <button type="button" class="btn btn-light" data-bs-dismiss="modal">Close</button>
      <button type="submit"class="btn btn-indigo" id="save"><span class="save_changes">Save changes<span></button>  
	  </div>
		</form>
      </div>
    
    </div>
  </div>
</div>

<div id="expens_cat" style="display:none;"><?php echo $particular_category; ?></div>
	    <script src="/accounts/js/template/insert/income.js"></script>

	<script>
		$(document).ready(function(){
			route_currency_text = $("#route_currency_text").html();
									var count = 0;
acc_name = $("#expens_cat").html();

function add_input_field(count,acc_name,currency_holder)
	{
		acc_name = $("#expens_cat").html();
		currency_holder = $("#currency_holder").html();

	//alert(currency_holder);

new_count = count+1;
		var html = '';

		html += '<tr>';

		html += '<td>'+new_count +'</td>';
		html += '<td><select name="part_Category[]" class="form-select part_Category" id="part_Category'+count+'" style="" data-live-search="true">'+acc_name+'</select></td>';
		html += '<td><select name="part_detail[]" class="form-select  part_detail" id="part_detail'+count+'" style="" data-live-search="true"><option value="Choose Particular">Choose Particular</option></select></td>';
		html += '<td><select name="part_Currency[]" class="form-select  particular_currency" id="particular_currency'+count+'" style="" data-live-search="true">'+currency_holder+'</select></td>';

		html += '<td><input name="amount[]" type="text" class="form-control value_name amount amount_income" ></td>';
		html += '<td><input name="detail[]" type="text" class="form-control value_name detail" ></td>';

		var remove_button = '';

		if(count > 0)
		{
			remove_button = '<button type="button" name="remove" class="btn btn-danger btn-sm remove_mod"><i class="fas fa-minus"></i></button>';
		}

		html += '<td>'+remove_button+'</td></tr>';

		return html;

	}
	
			$('#income_table').append(add_input_field(0));	
			

			
	$(document).on('click', '.add_table', function(){

		count++;
				route_name = $("#route_name").val();
/*
    $.ajax({
    url:"./dependence/fetch_route_currency.php",
    method:"POST",
    data:{route_name:route_name},
	   beforeSend: function(){
		$("#particular_currency"+count+" option:selected" ).text("loading..."); 

            
            },
    success:function(data){
$("#particular_currency"+count).html(data);
  }
   });
*/
		$('#income_table').append(add_input_field(count,acc_name));

		$('.selectpicker').selectpicker('refresh');
		
		
		


	});
			$(document).on('click', '.remove_mod', function(){

		$(this).closest('tr').remove();

	})	
	
	
	
		$(document).on('change','.part_Category', function(){
my_id = $(this).attr('id')
my_id = my_id.replace(/\D/g,'');
part_category = $("#part_Category"+my_id+" option:selected" ).text();

	    $.ajax({
    url:"./dependence/fetch_income.php",
    method:"POST",
    data:{part_category:part_category},
	   beforeSend: function(){
	$("#part_detail"+my_id+" option:selected" ).text("loading...");
            
            },
    success:function(data){
$('#part_detail'+my_id+'').html(data);
    }
   })

});
	
	
/*
	route_name = $("#route_name").val();

    $.ajax({
    url:"./dependence/fetch_route_currency.php",
    method:"POST",
    data:{route_name:route_name},
	   beforeSend: function(){
	
            	$(".particular_currency").text("loading..."); 
            },
    success:function(data){
$(".particular_currency").html(data);
  }
   });

*/

	});
	</script>