$(window).load(function(){
	$('.manifest').load('update_not_manifest.php');
setInterval(
function()
{
$('.manifest').load('update_not_manifest.php');
}, 3000);

});