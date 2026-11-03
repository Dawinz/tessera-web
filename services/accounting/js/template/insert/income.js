$(document).ready(function(){
								var count = 0;
acc_name = $("#expens_cat").html();
function add_input_field(count,acc_name)
	{
		acc_name = $("#expens_cat").html();
	
new_count = count+1;
		var html = '';

		html += '<tr>';

		html += '<td>'+new_count +'</td>';
		html += '<td><select name="part_Category[]" class="form-select part_Category" id="part_Category'+count+'" style="" data-live-search="true">'+acc_name+'</select></td>';
		html += '<td><select name="part_detail[]" class="form-select  part_detail" id="part_detail'+count+'" style="" data-live-search="true"><option value="Choose Particular">Choose Particular</option></select></td>';
		html += '<td><select name="part_Currency[]" class="form-select  particular_currency" id="particular_currency'+count+'" style="" data-live-search="true"></select></td>';

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
	

		function myFunction(month,year,route,trip_date,bus,point,start,route_name,currency_value,currency,currency_sign,level) {
                        $.ajax(
                            {
                                url: 'search/vehicle_account.php',
                                method: 'POST',
                                data: {
								   month:month,
								   year:year,
								   route:route,
								   trip_date:trip_date,
								   bus:bus,
								   point:point,
								   start:start,
								   route_name:route_name,
								   currency_value:currency_value,
								   currency:currency,
								   currency_sign:currency_sign,
								   level:level
                                },
								  beforeSend: function(){

            
            },
                                success: function (data) {
								

					$("#load_vehicle_account").html(data);

                                },
                                dataType: 'text'
                            }
                        );
                    
			}
	  $("#insert_form").on('submit', function(e){
        e.preventDefault();
count = 1;		
error="";

$("select[name='part_Category[]']").each(function(){

	if($(this).val() == 'Choose Category'){
		
error += "<li>Choose Particular Category.</li>";
	}
	});	
	
		$("select[name='part_detail[]']").each(function(){
if($(this).val() == 'Choose Particular'){
	error += "<li>Choose Particular .</li>";

}	
			});	

$("select[name='part_Currency[]']").each(function(){
	
if($(this).val() == 'Choose Currency'){
	error += "<li>Choose Particular .</li>";

}	
			});	
			
	$(".amount_income").each(function(){
	
if($(this).val() == ''){
	error += "<li>Add amount.</li>";

}	
			});	
	

				if(error == '')
		{
			  $.ajax({
            type: 'POST',
            url: 'insert/insert_income.php',
            data: new FormData(this),
           
            contentType: false,
            cache: false,
            processData:false,
            beforeSend: function(){
		$("#save").attr("disabled", true);
	  $(".save_changes").text("registering.....");
            
            },
            success: function(response){
				//alert(response);
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
				$('#income_table').find('tr:gt(0)').remove();
				$('#income_table').append(add_input_field(0));	
				//alert(response);
				$("#save").attr("disabled", false);
	  $(".save_changes").text("Save changes");
month = $("#month1").text();
year = $(".currency_year").val();
route = $("#main_route").val();
route_name = $("#current_route").val();
trip_date = $("#trip_date").val();
bus = $("#busname").val();
point = $("#point_value").val();
start = "";
currency_value = $(".currency_value").text();
currency = $(".currency").text();
currency_sign = $(".currency_default").text();
level = $("#level").text();

			myFunction(month,year,route,trip_date,bus,point,start,route_name,currency_value,currency,currency_sign,level) ;
			  $("#save_changes").text("Save changes");
 $("#save").attr("disabled", false);


            }
        });	
		}else{
									Swal.fire({
  title: "Missing Info",
  html: error,
  icon: "warning"
});
		}
	
	
});});