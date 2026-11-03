$(window).load(function(){
	$(".update").load("update_not.php");
setInterval(
function()
{
$('.update').load('update_not.php');
}, 3000);
});
