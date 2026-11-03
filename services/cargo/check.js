$(document).ready(function(){
$('#check').click(function(){
	var no = $('#no').val();
					$("#check").text("checking....");
  $.ajax({
				url:"check.php",
				method:"POST",
				data:{no:no},
	
				success:function(response){
					$("#check").text("check");	
if($.trim(response)=="yes"){
swal("Choose what you want for the parcel", {
  buttons: {
    cancel: "Post",
    catch: {
      text: "Edit!",
      value: "catch",
    },
    remove: true,
  },
})
.then((value) => {
  switch (value) {
 
    case "remove":
       window.location="delete_luggage2.php?u="+no;
      break;
 
    case "catch":
       window.location="edit_insert.php?code="+no;
      break;
 
    default:
 window.location="checked.php?code="+no;
  }
});
}else if($.trim(response)=="no"){
swal("luggage number don't Exist !", "please enter again!", "warning");
				$("#send").text("Post");
			 			 control.disabled="disabled";
}else if($.trim(response)=="finish"){
swal("out 0/0  !", "all luggages are out!", "warning");
				$("#send").text("Post");
			 			 control.disabled="disabled";
}
	$("#check").text("checked");							
	}
			
			});

});
});