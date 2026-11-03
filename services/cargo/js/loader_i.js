
$(document).ready(function(){
	var vehicle =$('vehicle').text();
	var date =$('date').text();
$("#loaderdiv").load("toast.php?id="+date+"&date="+date+"");
setTimeout(function(){
$("#loaderdiv").remove();
}, 3000);
});