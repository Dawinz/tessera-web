$(document).ready(function(){

var starting_location = $( "#starting_location option:selected" ).text();
var ending_location = $( "#ending_location option:selected" ).text();

starting_date = $("#starting_date").val();
ending_date = $(".ending_date").val();

start_route_query = " start_route = '"+starting_location+"'  ";
end_route_query = "";
function seat_table(starting_location,ending_location,starting_date,ending_date,start_route_query,end_route_query) {
                        $.ajax(
                            {
                                url: 'search/seat_table.php',
                                method: 'POST',
                                data: {
								   starting_location:starting_location,
								   ending_location:ending_location,
								   starting_date:starting_date,
								   ending_date:ending_date,
								   start_route_query:start_route_query,
								   end_route_query:end_route_query
                                },
								  beforeSend: function(){

            $("#load_route_content").html('<div class="placeholder-glow" ><span class="placeholder col-12"style="height:300px"></span></div>');
            },
                                success: function (data) {
									
$("#load_route_content").html(data);



                                },
                                dataType: 'text'
                            }
                        );
                    
			}

seat_table(starting_location,ending_location,starting_date,ending_date,start_route_query,end_route_query)


$("#starting_location").change(function(){ 

var starting_location = $( "#starting_location option:selected" ).text();
var ending_location = $( "#ending_location option:selected" ).text();

starting_date = $("#starting_date").val();
ending_date = $(".ending_date").val();
start_route_query = " start_route = '"+starting_location+"'  ";

if(ending_location=="Choose Ending Point"){

end_route_query="";

}else{
	end_route_query = " and end_route = '"+ending_location+"'  ";	
}

seat_table(starting_location,ending_location,starting_date,ending_date,start_route_query,end_route_query)

 });
 $("#ending_location").change(function(){ 

var starting_location = $( "#starting_location option:selected" ).text();
var ending_location = $( "#ending_location option:selected" ).text();

starting_date = $("#starting_date").val();
ending_date = $(".ending_date").val();
start_route_query = " start_route = '"+starting_location+"'  ";

if(ending_location=="Choose Ending Point"){

end_route_query="";

}else{
	end_route_query = " and end_route = '"+ending_location+"'  ";	
}

seat_table(starting_location,ending_location,starting_date,ending_date,start_route_query,end_route_query)

 });





 $("#starting_date").change(function(){

var starting_location = $( "#starting_location option:selected" ).text();
var ending_location = $( "#ending_location option:selected" ).text();

starting_date = $("#starting_date").val();
ending_date = $(".ending_date").val();
start_route_query = " start_route = '"+starting_location+"'  ";

if(ending_location=="Choose Ending Point"){

end_route_query="";

}else{
	end_route_query = " and end_route = '"+ending_location+"'  ";	
}

seat_table(starting_location,ending_location,starting_date,ending_date,start_route_query,end_route_query)

 });
 


 });