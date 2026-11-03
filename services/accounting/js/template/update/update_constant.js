$(document).ready(function(){
$("#udpdate").click(function(){
var part_Category_updat = $( "#part_Category_updat option:selected" ).text();
var particular_name_update = $( "#particular_name_update option:selected" ).text();
var choose_route_update = $( "#choose_route_update option:selected" ).text();
var route_category_update = $( "#route_category_update option:selected" ).text();
var affect_update = $( "#affect_update option:selected" ).text();
var vehicle_update = $( "#vehicle_update option:selected" ).text();
var currency_v_update = $( "#currency_v_update option:selected" ).text();
var amount = $( "#amount_update" ).val();
var id = $( "#id_no" ).text();
$("#udpdate").text("Updating...");
  $.ajax({
				url:"update/constant.php",
				method:"POST",
				data:{id:id,part_Category_updat:part_Category_updat,particular_name_update:particular_name_update,choose_route_update:choose_route_update,route_category_update:route_category_update,affect_update:affect_update,vehicle_update:vehicle_update,amount:amount,currency_v_update:currency_v_update},
				success:function(response){
					
//alert(response);
			$("#udpdate").text("Save changes");
				$("#udpdate").notify(
 "Changes saved",
 "success",
  { position:"right" }
);
			$("#example1").DataTable().ajax.reload();

			}

			
			});
});
});