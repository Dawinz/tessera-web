$(document).ready(function(){


$("#search").click(function(){
var currecy_name = $("#currecy_name").val();
var Country = $("#Country option:selected" ).text();
var initial = $("#initial").val();
var currency_status = $("#c_status option:selected" ).text();;
var fair = $("#fair").val();


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
				 swal("", "Please enter rate !", "warning");

			 control.disabled="disabled";
}

$("#search").text("registering...");

 $.ajax({
				url:"save_currency.php",
				method:"POST",
				data:{currecy_name:currecy_name,Country:Country,initial:initial,currency_status:currency_status,fair:fair},
				success:function(response){
					$("#example").DataTable().ajax.reload();
if($.trim(response) == "succesfull"){
					
							  swal({
  
    text: "update Succefull.",
    timer: 2000,
    showConfirmButton: false
  });

  $("#search").text("Register");
						} else if($.trim(response) == "not found"){
 swal("Main Currecny", "Main currecy is available please change to Normal !", "warning");
						}else{
swal("", "Something is wrong !", "warning");

						}
						$("#example").DataTable().ajax.reload();
																			

	}
			
			});
});



});