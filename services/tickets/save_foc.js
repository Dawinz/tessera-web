$(document).ready(function(){
	
$("#save_foc_d").click(function(){
foc_text = $("#foc_text").text();
foc_detail_val = $(".floatingTextarea23").val();
$('#foc_info'+foc_text+'').val(foc_detail_val);

if(foc_detail_val===""){
	
}else{
		
	$('#foc_panel').modal('hide');

}

});
$("#save_trans_d").click(function(){
trans_text = $("#trans_text").text();
trans_detail_val = $("#transit_details").val();
$('#trans_info'+trans_text+'').val(trans_detail_val);
if(trans_detail_val===""){
		 alertify.error("fill Transit detail ");
alertify.set('notifier','position', 'top-right');
}else{
	$('#trans_panel').modal('hide');
}



});





$("#close_foc_panel").click(function(){
foc_text = $("#foc_text").text();
$('#switch'+foc_text+'').prop('checked', false);
$('#foc_info'+foc_text+'').val("");
});
$("#close_foc_panel2").click(function(){
foc_text = $("#foc_text").text();
$('#switch'+foc_text+'').prop('checked', false);
$('#foc_info'+foc_text+'').val("");
});
$("#close_trans_panel").click(function(){
trans_text = $("#trans_text").text();
$('#switcht'+trans_text+'').prop('checked', false);
$('#trans_info'+trans_text+'').val("");
});
$("#close_trans_panel2").click(function(){
trans_text = $("#trans_text").text();
$('#switcht'+trans_text+'').prop('checked', false);
$('#trans_info'+trans_text+'').val("");
});




});


