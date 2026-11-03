$(document).ready(function(){

var starting_location = $( "#starting_location option:selected" ).text().replace(/ /g,"_");
var ending_location = $( "#ending_location option:selected" ).text().replace(/ /g,"_");

starting_date = $("#starting_date").val();
ending_date = $(".ending_date").val();
		$("#load_route_content").load("load_table_with_start_date.php?starting_location="+starting_location+"&ending_location="+ending_location+"&starting_date="+starting_date+"");

$("#starting_location").change(function(){ 

var starting_location = $( "#starting_location option:selected" ).text().replace(/ /g,"_");
var ending_location = $( "#ending_location option:selected" ).text().replace(/ /g,"_");

starting_date = $("#starting_date").val();
ending_date = $(".ending_date").val();

if(ending_location=="Choose_Ending_Point"){

  $("#load_route_content").html('<div class="placeholder-glow" ><span class="placeholder col-12"style="height:300px"></span></div>');
	$("#load_route_content").load("load_table_with_start_date.php?starting_location="+starting_location+"&ending_location="+ending_location+"&starting_date="+starting_date+"");
	
}else{
	

$("#load_route_content").html('<div class="placeholder-glow" ><span class="placeholder col-12"style="height:300px"></span></div>');
	$("#load_route_content").load("load_with_go_date.php?starting_location="+starting_location+"&ending_location="+ending_location+"&starting_date="+starting_date+"");
	
}
	$("#load_seat_chart").load("seat_holder.php");
	$('#item_table').find('tr:gt(0)').remove();
						$('#item_table').append(add_input_field(0));

					

	
});
$("#ending_location").change(function(){ 
var starting_location = $( "#starting_location option:selected" ).text().replace(/ /g,"_");
var ending_location = $( "#ending_location option:selected" ).text().replace(/ /g,"_");

starting_date = $("#starting_date").val();
ending_date = $(".ending_date").val();

$("#load_route_content").html('<div class="placeholder-glow" ><span class="placeholder col-12"style="height:300px"></span></div>');
	$("#load_route_content").load("load_with_go_date.php?starting_location="+starting_location+"&ending_location="+ending_location+"&starting_date="+starting_date+"");
	$("#load_seat_chart").load("seat_holder.php");
	
		$('#item_table').find('tr:gt(0)').remove();
						$('#item_table').append(add_input_field(0));
});

 $("#starting_date").change(function(){

var starting_location = $( "#starting_location option:selected" ).text().replace(/ /g,"_");
var ending_location = $( "#ending_location option:selected" ).text().replace(/ /g,"_");

starting_date = $("#starting_date").val();
ending_date = $(".ending_date").val();

if(ending_location=="Choose_Ending_Point"){

	$("#load_route_content").html('<div class="placeholder-glow" ><span class="placeholder col-12"style="height:300px"></span></div>');
	$("#load_route_content").load("load_table_with_start_date.php?starting_location="+starting_location+"&ending_location="+ending_location+"&starting_date="+starting_date+"");
	
}else{


	  $("#load_route_content").html('<div class="placeholder-glow" ><span class="placeholder col-12"style="height:300px"></span></div>');
	$("#load_route_content").load("load_with_go_date_only.php?starting_location="+starting_location+"&ending_location="+ending_location+"&starting_date="+starting_date+"");
	


}
	$("#load_seat_chart").load("seat_holder.php");
		$('#item_table').find('tr:gt(0)').remove();
						$('#item_table').append(add_input_field(0));
 });
 
 });