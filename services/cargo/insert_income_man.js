$(document).ready(function(){
	$("#insert").click(function(){
	var trip_date = $( "#country option:selected" ).text();
	var vehicle = $( "#state option:selected" ).text();
	var detail = $("#summernote").summernote("code");
	var amount = $("#amount").val();
	var status2 = $( "#status option:selected" ).text();

if(trip_date =='Select Trip Date'){
	swal("Enter Trip Date!");
				 			 control.disabled="disabled";

}
if(vehicle =='Select Vehicles'){
	swal("Enter Vehicel Name!");
				 			 control.disabled="disabled";

}if(vehicle =='Vehicle'){
		swal("Enter Vehicel Name!");
					 			 control.disabled="disabled";

}if(detail =='detail'){
		swal("Enter Detail!");
					 			 control.disabled="disabled";

}if(amount =='amount'){
		swal("Enter Ammount!");
					 			 control.disabled="disabled";

}
if(status2 =='Choose Category'){
		swal("Enter Categroy!");
					 			 control.disabled="disabled";

}
	$("#insert").text("loading..");
  $.ajax({
				url:"insert_man_income.php",
				method:"POST",
				data:{trip_date:trip_date,vehicle:vehicle,detail:detail,amount:amount,status2:status2},
				success:function(response){
	if($.trim(response)=="ava"){
					swal("Available!", "The Inserted Category Exits!", "warning");	
					}else{
						
				
		if($.trim(response)=="small"){
			
						swal("Negativ!", "Ammount Remain Can not be Negative!", "warning");
						$("#insert").text("Insert");
		}	else{
			$("#amount").val("");
		
								  swal({
    title: "Inserted!",
    text: "Data Succesfull Insert.",
    timer: 1000,
    showConfirmButton: false
  });
  	$("#insert").text("Insert");

		}		
				}

	}
			
			});
});});