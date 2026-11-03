$(document).ready(function(){

  $("#transit_transfer").on('submit', function(e){
        e.preventDefault();
		var transfer = $("#seat_id_trans").val();
if(transfer === undefined){
	
}else{
	$.ajax({
            type: 'POST',
            url: 'insert/transit.php',
            data: new FormData(this),
           
            contentType: false,
            cache: false,
            processData:false,
            beforeSend: function(){
		$("#save").attr("disabled", true);
	  $("#save").text("Adding..");
            
            },
            success: function(response){
				$("#save").text("Save Changes");
response = $.trim(response);
window.location="print_multiple_ticket.php?print_id="+response;

		 }
        });	
		

}
});
});