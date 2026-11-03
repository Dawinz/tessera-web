$(window).load(function(){
	$('#update_area_manifest').load('update_loader_manifest.php');
setInterval(
function()
{
$('#update_area_manifest').load('update_loader_manifest.php');
}, 3000);

});