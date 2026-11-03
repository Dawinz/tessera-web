$(document).ready(function(){
	var equal = $('#equal').text();
	var transit = $('#transit').text();
	if(equal==""){
		
	}else{
		swal("Waring!", "The Luggage is Half Sent!", "warning");

	}
	if(transit!=""){
			swal("Waring!", "Transit Luggages!", "warning");
	}

	$("#send").click(function(){
	
		var vehicle_name = $('#vehicle_name').text();
		var code = $('#code_no').text();
		var trip_date = $('#trip_date').text();
		var qty = $('#qty').val();
		var to_be = $('#to_be').val();
		var paid = $('#paid').val();

	
post_luggage (vehicle_name,code,trip_date,qty,to_be,paid,transit);
			
});


$(document).on('keypress',function(e) { 
 var key = e.which;
 if(key == 13)  // the enter key code
  {
		var vehicle_name = $('#vehicle_name').text();
		var code = $('#code_no').text();
		var trip_date = $('#trip_date').text();
		var qty = $('#qty').val();
		var to_be = $('#to_be').val();
		var paid = $('#paid').val();

	
post_luggage (vehicle_name,code,trip_date,qty,to_be,paid,transit);
			
    return false;  
  }
});
function post_luggage (vehicle_name,code,trip_date,qty,to_be,paid,transit){
	
		$("#send").text("analysing...");
		$("#send").attr("disabled", true);
	  $.ajax({
				url:"save_insert.php",
				method:"POST",
				data:{code:code,vehicle_name:vehicle_name,trip_date:trip_date,qty:qty,to_be:to_be,paid:paid,transit:transit},
				success:function(response){
		alert(response);
				    response = $.trim(response);

	if(response==""){
  // window.location="check_luggages.php?id="+vehicle_name+"&date="+trip_date+"&open=open";		
	}else{
  swal("Waring!", response, "warning");
	}
$("#send").text("Post");
				$("#send").attr("disabled", false);
}
						

			});
	}
});