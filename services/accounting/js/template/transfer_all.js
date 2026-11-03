$(document).ready(function(){
$("#transfer_all").click(function(){
$("#transfer_option").modal('show');
route_checked = $("#checd_route").text();

	    $.ajax({
    url:"./dependence/transfer_particular.php",
    method:"POST",
    data:{route_checked:route_checked},
	   beforeSend: function(){
		   
	$("#trip_dates option:selected" ).text("loading..."); 
            },
    success:function(data){
		
$('#trip_dates').html(data);

    }
   })

});

$(document).on('change','#trip_dates', function(){
trip_dates = $("#trip_dates option:selected" ).text();
    $.ajax({
    url:"./dependence/transfer_particular_date.php",
    method:"POST",
    data:{trip_dates:trip_dates},
	   beforeSend: function(){
		   
	$("#transfer_bus option:selected" ).text("loading..."); 
            },
    success:function(data){
$('#transfer_bus').html(data);

    }
   })

});


});