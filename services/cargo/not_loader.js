$(window).load(function(){
	$('#load_area').load('delete_area.php');
setInterval(
function()
{
$('#load_area').load('delete_area.php');
}, 3000);

});