$(document).ready(function() {
		function myFunction(month,year,route) {
				
                        $.ajax(
                            {
                                url: 'search/daily.php',
                                method: 'POST',
                                data: {
								   month:month,
								   year:year,
								   route:route
                                },
								  beforeSend: function(){

            
            },
                                success: function (data) {
									

					$("#load_daily_account").html(data);

                                },
                                dataType: 'text'
                            }
                        );
                    
			}
	   $("#insert_form").on('submit', function(e){
        e.preventDefault();
		
		count = 1;
error="";

	$("select[name='group_cat[]']").each(function(){



			if($(this).val() == 'Choose group')
			{
             
		

				error += "<li>Choose Category</li>";
				
			}


		});
	
		$("select[name='expenses[]']").each(function(){

			if($(this).val() == 'Select Particular')
			{
             
				error += "<li>Choose Particular</li>";			
			}


		});	
		
		$("select[name='item[]']").each(function(){
my_advc_id = $(this).attr('id');
my_advc_id = my_advc_id.replace(/\D/g,'');
catcl = $('#group_cat'+my_advc_id+'').val();

if(catcl=="Repair and maintenance (Vehicle)"){
if($(this).val() == 'Choose Item')
			{
             
				error += "<li>Choose Item</li>";			
			}	
}

		});	
	$("select[name='vehicle[]']").each(function(){
my_advc_id = $(this).attr('id');
my_advc_id = my_advc_id.replace(/\D/g,'');
catcl = $('#group_cat'+my_advc_id+'').val();
if(catcl=="Repair and maintenance (Vehicle)"){
if($(this).val() == 'Choose Vehicle')
			{
             
				error += "<li>Choose Vehcile</li>";			
			}	
}

		});	
		$("#date").each(function(){
			if($(this).val() == '')
			{
           
				error += "<li>Add Date</li>";
			
			}


		});
	$("#amount").each(function(){
			if($(this).val() == '')
			{
           
				error += "<li>Add Ammount</li>";
				
			}


		});
			

	
		if(error == '')
		{
	  $.ajax({
            type: 'POST',
            url: 'insert/daily.php',
            data: new FormData(this),
           
            contentType: false,
            cache: false,
            processData:false,
            beforeSend: function(){
		$("#save_part").attr("disabled", true);
	  $("#save_part").text("Add Currency..");
            
            },
            success: function(response){
				alert(response);
	myFunction(month,year,route);

            }
        });	
	
		}else{
								Swal.fire({
  title:"Missing Info",
  html: "Fill all information",
  icon: "warning"
});		
		}
	
	/*
part_Category = $(".group_cat option:selected").text();
	if(part_Category=="Choose Group"){
error += "<li>Choose Particular Category.</li>";
	}
	
	expenses = $(".expenses option:selected").text();
	if((expenses =="Select Particular") || (expenses =="")){
error += "<li>Choose Particular .</li>";
	}
	vehicle = $(".vehicle option:selected").text();

if(((part_Category=="Lorry (Vehicle)") && (vehicle=="Choose Vehicle")) || ((part_Category=="Bus(Vehicle)") && (vehicle=="Choose Vehicle")) ) {
	error += "<li>Choose Vehicle .</li>";

}

date = $(".date").val();
	if(date=="") {
error += "<li>Add date.</li>";
	}
	
	amount = $(".amount").val();
	if(amount=="") {
error += "<li>Add amount.</li>";
	}
	sdate = $(".sdate option:selected").text();
	if(sdate=="Loading..."){
error += "<li>Add Cash in hand date.</li>";
	}



		if(error == '')
		{
			
	
	  $.ajax({
            type: 'POST',
            url: 'insert/daily.php',
            data: new FormData(this),
           
            contentType: false,
            cache: false,
            processData:false,
            beforeSend: function(){
		$("#save_part").attr("disabled", true);
	  $("#save_part").text("Add Currency..");
            
            },
            success: function(response){
				alert(response);
	

            }
        });			
			
		}else{
	
	
					Swal.fire({
  title: "Missing Info",
  html: error,
  icon: "warning"
});		
		
		}
			*/

	});
});