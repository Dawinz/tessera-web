$(document).ready(function(){


	
$("#starting_location").change(function(){ 
var starting_location = $( "#starting_location option:selected" ).text();
var ending_location = $( "#ending_location option:selected" ).text();

starting_date = $("#starting_date").val();
ending_date = $(".ending_date").val();

if(ending_location=="Choose Ending Point"){
 $("#first_preloader").html('<tr><td><div class="spinner-border text-light m-1" role="status"><span class="sr-only">Loading...</span></div></td></tr>');
	  $.ajax({
        url:'load_with_go_date_only.php', // URL of where AJAX should POST the data
        type: 'POST', // Send a POST to server
        data: {starting_location: starting_location,
		ending_location:ending_location,
		starting_date:starting_date
		
		}, // Data to be POSTED to server. In PHP use echo $_POST['id']; to see what the AJAX passed in
        dataType: 'HTML', // Expected data from server
        success: function(data){
            // OR

            $("#first_preloader").html(data);

        }
    });
	

	
}else{
	


  $("#first_preloader").html('<tr><td><div class="spinner-border text-light m-1" role="status"><span class="sr-only">Loading...</span></div></td></tr>');
	  $.ajax({
        url:'load_with_go_date.php', // URL of where AJAX should POST the data
        type: 'POST', // Send a POST to server
        data: {starting_location: starting_location,
		ending_location:ending_location,
		starting_date:starting_date
		
		}, // Data to be POSTED to server. In PHP use echo $_POST['id']; to see what the AJAX passed in
        dataType: 'HTML', // Expected data from server
        success: function(data){
            // OR

            $("#first_preloader").html(data);
        }
    });
}
});
$("#ending_location").change(function(){ 
var starting_location = $( "#starting_location option:selected" ).text();
var ending_location = $( "#ending_location option:selected" ).text();

var starting_date = $("#starting_date").val();
var ending_date = $(".ending_date").val();

  $("#first_preloader").html('<tr><td><div class="spinner-border text-light m-1" role="status"><span class="sr-only">Loading...</span></div></td></tr>');
	  $.ajax({
        url:'load_with_go_date.php', // URL of where AJAX should POST the data
        type: 'POST', // Send a POST to server
        data: {starting_location: starting_location,
		ending_location:ending_location,
		starting_date:starting_date
		
		}, // Data to be POSTED to server. In PHP use echo $_POST['id']; to see what the AJAX passed in
        dataType: 'HTML', // Expected data from server
        success: function(data){
            // OR

            $("#first_preloader").html(data);
        }
    });


});

 $("#starting_date").change(function(){
var starting_location = $( "#starting_location option:selected" ).text();
var ending_location = $( "#ending_location option:selected" ).text();

starting_date = $("#starting_date").val();
ending_date = $(".ending_date").val();

if(ending_location=="Choose Ending Point"){
 $("#first_preloader").html('<tr><td><div class="spinner-border text-light m-1" role="status"><span class="sr-only">Loading...</span></div></td></tr>');
	  $.ajax({
        url:'load_with_go_date_only.php', // URL of where AJAX should POST the data
        type: 'POST', // Send a POST to server
        data: {starting_location: starting_location,
		ending_location:ending_location,
		starting_date:starting_date
		
		}, // Data to be POSTED to server. In PHP use echo $_POST['id']; to see what the AJAX passed in
        dataType: 'HTML', // Expected data from server
        success: function(data){
            // OR

            $("#first_preloader").html(data);

        }
    });
	

	
}else{
	


  $("#first_preloader").html('<tr><td><div class="spinner-border text-light m-1" role="status"><span class="sr-only">Loading...</span></div></td></tr>');
	  $.ajax({
        url:'load_with_go_date.php', // URL of where AJAX should POST the data
        type: 'POST', // Send a POST to server
        data: {starting_location: starting_location,
		ending_location:ending_location,
		starting_date:starting_date
		
		}, // Data to be POSTED to server. In PHP use echo $_POST['id']; to see what the AJAX passed in
        dataType: 'HTML', // Expected data from server
        success: function(data){
            // OR

            $("#first_preloader").html(data);
        }
    });
}
 });
 
 });