$(document).ready(function(){

$("#lock_save").click(function(){
locked_manifest = $("#locked_manifest").val();
bus = $("#bus_manifest").text();
trip_date = $("#trip_date_manifest").text();
if(locked_manifest==""){
	Swal.fire("Fill Password");

	control.disabled="disabled";
}
$("#lock_save_text").text("locking....");
 $.ajax({
				url:"update/lock.php",
				method:"POST",
				data:{locked_manifest:locked_manifest,bus:bus,trip_date:trip_date},
				success:function(response){
					
$("#lock_save_text").text("Save Changes");
			
			$("#lock_save").notify(
 "Locked",
 "success",
  { position:"right" }
);
			}

			
			});


});
});