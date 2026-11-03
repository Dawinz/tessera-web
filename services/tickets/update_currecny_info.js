$(document).ready(function(){


$("#save_update").click(function(){
var currecy_name = $("#currecy_name_update").val();
var Country = $("#Country_update option:selected" ).text();
var initial = $("#initial_update").val();
var currency_status = $("#c_status_update option:selected" ).text();;
var fair = $("#fair_update").val();
var id_no = $("#id_no").text();


if(currecy_name=="") {
				 swal("", "Please Enter Currency Name !", "warning");

			 control.disabled="disabled";
}

if(Country=="Choose Country") {
				 swal("", "Please select Country !", "warning");

			 control.disabled="disabled";
}

if(initial=="") {
				 swal("", "Please Enter Intial", "warning");

			 control.disabled="disabled";
}

if(currency_status=="Choose Currency Status") {
				 swal("", "Please enter Currency Status!", "warning");

			 control.disabled="disabled";
}
if(fair=="") {
				 swal("", "Please enter fair !", "warning");

			 control.disabled="disabled";
}

$("#save_update").text("registering...");

 $.ajax({
				url:"update_currency.php",
				method:"POST",
				data:{id_no:id_no,currecy_name:currecy_name,Country:Country,initial:initial,currency_status:currency_status,fair:fair},
				success:function(response){
if($.trim(response) == "succesfull"){
					
							  swal({
  
    text: "update Succefull.",
    timer: 2000,
    showConfirmButton: false
  });

  $("#save_update").text("Register");
						} else if($.trim(response) == "change"){
 swal("Main Currecny", "Main currecy is available please change to Normal !", "warning");
						}else{
swal("", "Something is wrong !", "warning");

						}
						$("#example").DataTable().ajax.reload();
	}
			
			});
});



});