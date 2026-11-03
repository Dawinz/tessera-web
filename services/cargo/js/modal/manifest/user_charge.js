
const formatter = new Intl.NumberFormat('en-US', { });
vehicle_name = $(".vehicle_name").text();
vehicle_date = $(".vehicle_date").text();
var header = ''+vehicle_name+' - '+vehicle_date;
$(".user_charge_name").click(function(){
    
$(".user_manifest_charges").modal('show');

username =  $(this).data("username");
to_be  =  parseInt($(this).data("to_be"));
paid  =  parseInt($(this).data("paid"));
route_name  =  $(this).data("route_name");
total_amount = to_be + paid;

$("#user_manifest_header").text(header);

tbody = '<table class="table mb-0" id="user_table"><thead><th>Route</th><th>Name</th><th>To be paid</th><th>Paid</th><th>Total</th></thead>';
tbody += '<tr><td>'+route_name+'</td><td>'+username+'</td><td>'+formatter.format(to_be)+'</td><td>'+formatter.format(paid)+'</td><td>'+formatter.format(total_amount)+'</td></tr>';
tbody += '</table>';

$("#user_summary_table").html(tbody);
});
$(".user_charge_all").click(function(){
    $("#user_manifest_header").text(header);
$(".user_manifest_charges").modal('show');
user_table = $("#user_table_function").html();

$("#user_summary_table").html(user_table);

});
