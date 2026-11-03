$("#save").click(function(){
insert_date = $("#insert_date_job").val();
choose_route = $(".choose_route2 option:selected").text();

if(insert_date==""){
	  Swal.fire("Please Enter Job card");
        control.disabled="disabled";
}
if(choose_route=="Choose Route"){
	alert("Choose Route");
	        control.disabled="disabled";

}
	 $.ajax({
    url:"insert/job_card.php",
    method:"POST",
    data:{insert_date:insert_date,choose_route:choose_route},
	   beforeSend: function(){
$("#save_text").text("loading...");
		
            },
    success:function(data){
			$("#save_text").text("Save changes");
alert(data);
data = $.trim(data);
if(data !="found"){
$("#save_text").notify(
 "Saved Successfull",
 "success",
  { position:"right" }
);
}else{
$("#save_text").notify(
 "Found",
 "error",
  { position:"right" }
);
}
$("#example1").DataTable().ajax.reload();


    }
   })


});