$(".save_avcance_d").click(function(){
advance_chcek_text = $("#advance_chcek_text").text();
advance_left = $(".advance_left").val();

$('#adnce_info'+advance_chcek_text+'').val(advance_left);
if(advance_left===""){
		 alertify.error("fill Transit detail ");
alertify.set('notifier','position', 'top-right');
}else{
	$('#advance_panel').modal('hide');
}



});
$("#close_advc_panel").click(function(){
advance_chcek_text = $("#advance_chcek_text").text();

$('#switchtad'+advance_chcek_text+'').prop('checked', false);
$('#adnce_info'+advance_chcek_text+'').val("");
});
$("#close_advc_panel2").click(function(){
advance_chcek_text = $("#advance_chcek_text").text();

$('#switchtad'+advance_chcek_text+'').prop('checked', false);
$('#adnce_info'+advance_chcek_text+'').val("");
});