$(".advance_left").keyup(function(){
  advance_left = $(".advance_left").val();
  t_price = $(".t_price").text();
  t_price = parseInt(t_price);
  advance_left=parseInt(advance_left);
  
  total_val = t_price-advance_left;
total_val=parseInt(total_val);
zero = 0;
if(total_val <= zero){
	 $('#save_avcance_d').prop('disabled', true);
	 	  $(".result_half").text("0");
}else{
	  $(".result_half").text(total_val);
	 $('#save_avcance_d').prop('disabled', false);
}
});