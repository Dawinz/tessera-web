$(document).ready(function(){
	  $("#insert_form_exp").on('submit', function(e){
        e.preventDefault();
count = 1;		
error="";
part_Category = $(".part_Category_exp option:selected").text();
	if(part_Category=="Choose Category"){
error += "<li>Choose Particular Category.</li>";
	}
	part_detail = $(".part_detail_exp option:selected").text();
	if(part_detail=="Choose Particular"){
error += "<li>Choose Particular .</li>";
	}	
	particular_currency = $(".particular_currency_exp option:selected").text();
	if((part_detail=="No Currency Assigned") || (part_detail=="Choose Currency") ){
error += "<li>Add Currency or select currency .</li>";
	}	
	
	amount = $(".amount_exp").val();
	if(amount=="") {
error += "<li>Add Ammount.</li>";
	}
	
				if(error == '')
		{
			  $.ajax({
            type: 'POST',
            url: 'insert/insert_expenses.php',
            data: new FormData(this),
           
            contentType: false,
            cache: false,
            processData:false,
            beforeSend: function(){
		$("#save").attr("disabled", true);
	  $("#save").text("Add Currency..");
            
            },
            success: function(response){
				alert(response);
				url = $.trim(response);
					$(".url_checked").text(url);
			//$("#load_vehicle_account").load("load/vehicle_account.php?"+url);
		
			  $("#save").text("Save changes");
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