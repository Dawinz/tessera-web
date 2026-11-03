$(document).ready(function(){
trip_date = $("#trip_date").val();
vehicle = $("#vehicle").val();
start_date = $("#start_date").val();
end_date = $("#end_date").val();
route_name = $("#route_name").val();
status_luggage = $("#status_luggage").val();
type = $("#type").val();
remark = $("#remark").text();
row_remark = $("#row_remark").val();
route_origin = $("#main_route").val();

manifest_entries(trip_date,vehicle,start_date,end_date,route_name,status_luggage,type,remark,row_remark,route_origin);


$("#start_date").off('change').on('change', function() { 
status_luggage =  $("#status_luggage").val();
route_name =  $("#route_name").val();
trip_date = $("#trip_date").val();
vehicle = $("#vehicle").val();
start_date = $("#start_date").val();
end_date = $("#end_date").val();
type = $("#type").val();
manifest_entries(trip_date,vehicle,start_date,end_date,route_name,status_luggage,type,remark,row_remark,route_origin);
});

$("#end_date").off('change').on('change', function() { 
status_luggage =  $("#status_luggage").val();
route_name =  $("#route_name").val();
trip_date = $("#trip_date").val();
vehicle = $("#vehicle").val();
start_date = $("#start_date").val();
end_date = $("#end_date").val();
type = $("#type").val();
manifest_entries(trip_date,vehicle,start_date,end_date,route_name,status_luggage,type,remark,row_remark,route_origin);
});


$("#route_name").off('change').on('change', function() { 
status_luggage =  $("#status_luggage").val();
route_name =  $("#route_name").val();
trip_date = $("#trip_date").val();
vehicle = $("#vehicle").val();
start_date = $("#start_date").val();
end_date = $("#end_date").val();
type = $("#end_date").val();
manifest_entries(trip_date,vehicle,start_date,end_date,route_name,status_luggage,type,remark,row_remark,route_origin);
});
$("#status_luggage").off('change').on('change', function() { 
   
status_luggage =  $("#status_luggage").val();
route_name =  $("#route_name").val();
trip_date = $("#trip_date").val();
vehicle = $("#vehicle").val();
start_date = $("#start_date").val();
end_date = $("#end_date").val();
type = $("#type").val();
manifest_entries(trip_date,vehicle,start_date,end_date,route_name,status_luggage,type,remark,row_remark,route_origin);
});
$("#type").off('change').on('change', function() { 
   
status_luggage =  $("#status_luggage").val();
route_name =  $("#route_name").val();
trip_date = $("#trip_date").val();
vehicle = $("#vehicle").val();
start_date = $("#start_date").val();
end_date = $("#end_date").val();
type = $("#type").val();
manifest_entries(trip_date,vehicle,start_date,end_date,route_name,status_luggage,type,remark,row_remark,route_origin);
});


function manifest_entries(trip_date,vehicle,start_date,end_date,route_name,status_luggage,type,remark,row_remark,route_origin){
username = $("#username_name").val();

     	 $.ajax({
    url:"load/manifest_view.php",
    method:"POST",
    data:{trip_date:trip_date,vehicle:vehicle,start_date:start_date,end_date:end_date,route_name:route_name,status_luggage:status_luggage,type:type,remark:remark,row_remark:row_remark,route_origin:route_origin,username:username},
	   beforeSend: function(){
$("#load_manifest_view").html("<p class='placeholder-glow' ><span class='placeholder col-12' style='height:1100px'></span></p>"); 	
            },
    success:function(data){
$("#load_manifest_view").html(data);

    }
   })
}


$(".filter_btn").click(function(){
    $(".filter_panel").toggle();
})

$(".option_btn").click(function(){
    $(".export_panel").toggle();
})

});

