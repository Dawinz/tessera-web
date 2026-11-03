<?php
include 'connect.php';
	$select25_update=mysqli_query($db,"SELECT * FROM  date_total  ");
$select_num25_update=mysqli_num_rows($select25_update);
if($select_num25_update !=0){
   	WHILE($get125_update=mysqli_fetch_array($select25_update)){
    	    $date_choose =$get125_update['date']; 
}}
			
	?>
<script>

$(document).ready(function(){
load_current_date = "<?php echo $date_choose;?>";

// Set the date we're counting down to

// Update the count down every 1 second
var x = setInterval(function() {

  // Get today's date and time
  var now = new Date().getTime();

  // Find the distance between now and the count down date
  var distance = new Date(load_current_date+" 23:30:00").getTime() - now;
  // Time calculations for days, hours, minutes and seconds
  var days = Math.floor(distance / (1000 * 60 * 60 * 24));
  var hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
  var minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
  var seconds = Math.floor((distance % (1000 * 60)) / 1000);

  // Display the result in the element with id="demo"
  document.getElementById("demo").innerHTML = days+"d "+hours+"h"+ minutes + "m " + seconds + "s ";

  // If the count down is finished, write some text
  if (distance < 0) {
    clearInterval(x);
    document.getElementById("demo").innerHTML = "(Deleted) ";
	var holder = "boya";
$.ajax({
				url:"update_daily.php",
				method:"POST",
				data:{holder:holder},
				success:function(response){
					$("#load_timer_backup").load("load_timer_backup.php");

				
	}
			
			});
	

  }
}, 1000);


});

</script>