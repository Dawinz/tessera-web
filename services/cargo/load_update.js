$(window).load(function(){
	$('#update_area').load('update_area.php');
setInterval(
function()
{
$('#update_area').load('update_area.php');
}, 3000);

});