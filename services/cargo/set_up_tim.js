$(document).ready(function(){
	$("#load_current_date").load("current_increment_date.php");
load_current_date = $("#load_current_date").text();

// Set the date we're counting down to
var countDownDate = new Date(load_current_date+" 05:59:10 PM").getTime();

// Update the count down every 1 second
var x = setInterval(function() {

  // Get today's date and time
  var now = new Date().getTime();

  // Find the distance between now and the count down date
  var distance = countDownDate - now;
  // Time calculations for days, hours, minutes and seconds
  var days = Math.floor(distance / (1000 * 60 * 60 * 24));
  var hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
  var minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
  var seconds = Math.floor((distance % (1000 * 60)) / 1000);

  // Display the result in the element with id="demo"
  document.getElementById("demo").innerHTML =  minutes + "m " + seconds + "s ";

  // If the count down is finished, write some text
  if (distance < 0) {
    clearInterval(x);
    document.getElementById("demo").innerHTML = "(Deleted) ";
	new_date_load = $("#new_date_load").text();
	$("#load_current_date").text(new_date_load);

	
  }
}, 1000);
});