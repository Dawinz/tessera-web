$(document).ready(function(){
	var equal = $('#equal').text();
	if(equal==""){
		
	}else{
		swal("Waring!", "The Luggage is Half Sent!", "warning");

	}
	$("#send").click(function(){
		$("#send").text("Posting...");
		var choosen = $('#choosen').text();
		var date_insert = $('#date_insert').text();
		    var amount = parseInt($('#amount').text());
			var code = $("#code").text();
			var status2 = $("#status").text();
			var status_check = $("#status_check").text();
			var scanned = parseInt($("#scan").text());
			var scan = parseInt($("#scan").text()); 
			var quantity = parseInt($("#quantity").text());	
			var time_remain = $("#time_remain").text();
			var due_topay_man = parseInt($("#due_topay_man").text());	
			var due_pay_man = parseInt($("#due_pay_man").text());
			
		
			
			var number = parseInt($("#number").val());
			var amount_remain_new = parseInt($('#amount_remain_new').text());
		
              var to_pay_amm =0;
			  var due_topaid =0;
		  
	
	if((status_check=="Due payment") && (quantity == 1)){
			var due_topaid = parseInt($("#due_topaid").val());
			}else if((status_check=="paid") && (quantity == 1)){
		var to_pay_amm = parseInt($("#topaid").val());
			}else if((status_check=="To be Payed") && (quantity == 1)){
		var to_pay_amm = parseInt($("#topaid").val());
			} else if((status_check=="paid") && (quantity > 1)){
					var to_pay_amm = parseInt($("#topaid").val().replace(/,/g, ''));
			}else if((status_check=="To be Payed") && (quantity > 1)){
					
					var to_pay_amm = parseInt($("#topaid").val().replace(/,/g, ''));
			}else if((status_check=="Due payment") && (quantity > 1)){
                var due_topaid = parseInt($("#due_topaid").val().replace(/,/g, ''));
					var to_pay_amm = parseInt($("#topaid").val().replace(/,/g, ''));
			}
			if($("#number").val() == 0){
			swal("Fill Quantity!", "Quantity Can't be zero!", "warning");
				$("#send").text("Post");
			
			 			 control.disabled="disabled";
		}
	
	if(status_check=="Due payment") {
		
		if ((($("#number").val() == "")) && (quantity > 1)){
swal("Quantity Empty !", "Fill The quantity and procced !", "warning");
				$("#send").text("Post");
			 			 control.disabled="disabled";
		}
		
			if(number > scan){
			swal("Quantity is Greater !", "Please Reduce The number of quantities!", "warning");
				$("#send").text("Post");
				alert(scan);
			 			 control.disabled="disabled";	
		}	
		
		
		if(to_pay_amm > due_topay_man ){
			swal("To Pay Ammount High !", "Please Reduce The Ammount !", "warning");
				$("#send").text("Post");
			 			 control.disabled="disabled";	
		}
		if(due_topaid > due_pay_man ){
			swal("Payed Ammount High !", "Please Reduce The Ammount !", "warning");
				$("#send").text("Post");
			 			 control.disabled="disabled";	
		}
		if((quantity > 1) && (scan==1) && (due_topaid != due_pay_man )){
				swal("Pay Don't Balance  !", "Please the pay Ammout !", "warning");
				$("#send").text("Post");
			 			 control.disabled="disabled";	
		}	
		if((quantity > 1) && (scan==1) && (to_pay_amm  != due_topay_man )){
				swal("To Pay Don't Balance  !", "Please the to pay Ammount !", "warning");
				$("#send").text("Post");
			 			 control.disabled="disabled";	
		}
			if((number < scan) && (to_pay_amm >= due_topay_man) && (due_topaid  >= due_pay_man)){
				swal("Ammount And Quantity!", "YOU CANT SEND LESS FOR FULL AMMOUNT!", "warning");
				$("#send").text("Post");
			
			 			 control.disabled="disabled";
		}if((number < scan) && (to_pay_amm == due_topay_man) && (due_topaid  == due_pay_man)){
				swal("Ammount And Quantity!", "YOU CANT SEND LESS FOR FULL AMMOUNT!", "warning");
				$("#send").text("Post");
			
			 			 control.disabled="disabled";
		}
		if((number == scan) && (to_pay_amm < due_topay_man) && (due_topaid  < due_pay_man)){
				swal("Ammount And Quantity!", "Increase the amount to fit the quantity!", "warning");
				$("#send").text("Post");
			
			 			 control.disabled="disabled";
		}
	}else if(status_check=="paid"){
		
		if($("#number").val() == ""){
		swal("Fill Quantity!", "Please Fill Quantity!", "warning");
				$("#send").text("Post");
			 			 control.disabled="disabled";
		}
			if(number > scan){
			swal("Quantity is Greater !", "Please Reduce The number of quantities!", "warning");
				$("#send").text("Post");
				alert(scan);
			 			 control.disabled="disabled";	
		}
			if(to_pay_amm > amount_remain_new){
			swal("The Ammount is High !", "Please Reduce The Ammount !", "warning");
				$("#send").text("Post");
			 			 control.disabled="disabled";	
		}
				if((number < scan) && (to_pay_amm >= amount_remain_new)){
				swal("Ammount And Quantity!", "YOU CANT SEND ONE FOR FULL AMMOUNT!", "warning");
				$("#send").text("Post");
			
			 			 control.disabled="disabled";
		}if((number == scan) && (to_pay_amm < amount_remain_new)){
				swal("Ammount And Quantity!", "Increase the amount to fit the quantity!", "warning");
				$("#send").text("Post");
			
			 			 control.disabled="disabled";
		}
			if((isNaN(to_pay_amm) ) && (number < scan) ) {
				swal("Reduce The Number!", "Reduce the number to match the amount!", "warning");
				$("#send").text("Post");
			
			 			 control.disabled="disabled";
		}
	}else if(status_check=="To be Payed"){
		
		if($("#number").val() == ""){
			swal("Fill Quantity!", "Please Fill Quantity!", "warning");
				$("#send").text("Post");
			
			 			 control.disabled="disabled";
		}
		if(number > scan){
			swal("Quantity is Greater !", "Please Reduce The number of quantities!", "warning");
				$("#send").text("Post");
			 			 control.disabled="disabled";	
		}
		
		if(to_pay_amm > amount_remain_new){
			swal("The Ammount is High !", "Please Reduce The Ammount !", "warning");
				$("#send").text("Post");
			 			 control.disabled="disabled";	
		}
		if((number < scan) && (to_pay_amm >= amount_remain_new)){
				swal("Ammount And Quantity!", "YOU CANT SEND ONE FOR FULL AMMOUNT!", "warning");
				$("#send").text("Post");
			
			 			 control.disabled="disabled";
		}if((number == scan) && (to_pay_amm < amount_remain_new)){
				swal("Ammount And Quantity!", "Increase the amount to fit the quantity!", "warning");
				$("#send").text("Post");
			
			 			 control.disabled="disabled";
		}
		if((isNaN(to_pay_amm) ) && (number < scan) ) {
				swal("Reduce The Number!", "Reduce the number to match the amount!", "warning");
				$("#send").text("Post");
			
			 			 control.disabled="disabled";
		}
	}
	if(isNaN(due_topaid)) {
var due_topaid = 0;
}	
if(isNaN(to_pay_amm)) {
var to_pay_amm = 0;
}
	
		
	  $.ajax({
				url:"save_insert.php",
				method:"POST",
				data:{code:code,scanned:scanned,amount:amount,scan:scan,quantity:quantity,time_remain:time_remain,choosen:choosen,number:number,to_pay_amm:to_pay_amm,date_insert:date_insert,due_topaid:due_topaid},
				success:function(response){

	if($.trim(response)=="exist"){
swal("Waring!", "Already sent Remain Zero!", "warning");
	 control.disabled="disabled";
}			if($.trim(response)=="inside"){
swal("Waring found in Manifest!", "you can not insert two luggage in the same manifest delete one to proceed!", "warning");
	 control.disabled="disabled";
}
window.location="check_luggages.php?id="+choosen+"&date="+date_insert+"&open=open";	
}
						

			});
	
		
			$("#send").text("redirecting...");
});


$(document).on('keypress',function(e) { 
 var key = e.which;
 if(key == 13)  // the enter key code
  {
	$("#send").text("Posting...");
		var choosen = $('#choosen').text();
		var date_insert = $('#date_insert').text();
		    var amount = parseInt($('#amount').text());
			var code = $("#code").text();
			var status2 = $("#status").text();
			var status_check = $("#status_check").text();
			var scanned = parseInt($("#scan").text());
			var scan = parseInt($("#scan").text()); 
			var quantity = parseInt($("#quantity").text());	
			var time_remain = $("#time_remain").text();
			var due_topay_man = parseInt($("#due_topay_man").text());	
			var due_pay_man = parseInt($("#due_pay_man").text());
			
		
			
			var number = parseInt($("#number").val());
			var amount_remain_new = parseInt($('#amount_remain_new').text());
		

		  
	
	if((status_check=="Due payment") && (quantity == 1)){
			var due_topaid = parseInt($("#due_topaid").val());
			}else if((status_check=="paid") && (quantity == 1)){
		var to_pay_amm = parseInt($("#topaid").val());
			}else if((status_check=="To be Payed") && (quantity == 1)){
		var to_pay_amm = parseInt($("#topaid").val());
			} else if((status_check=="paid") && (quantity > 1)){
					var to_pay_amm = parseInt($("#topaid").val().replace(/,/g, ''));
			}else if((status_check=="To be Payed") && (quantity > 1)){
					
					var to_pay_amm = parseInt($("#topaid").val().replace(/,/g, ''));
			}else if((status_check=="Due payment") && (quantity > 1)){
                var due_topaid = parseInt($("#due_topaid").val().replace(/,/g, ''));
					var to_pay_amm = parseInt($("#topaid").val().replace(/,/g, ''));
			}
			if($("#number").val() == 0){
			swal("Fill Quantity!", "Quantity Can't be zero!", "warning");
				$("#send").text("Post");
			
			 			 control.disabled="disabled";
		}
	
	if(status_check=="Due payment") {
		
		if ((($("#number").val() == "")) && (quantity > 1)){
swal("Quantity Empty !", "Fill The quantity and procced !", "warning");
				$("#send").text("Post");
			 			 control.disabled="disabled";
		}
		
			if(number > scan){
			swal("Quantity is Greater !", "Please Reduce The number of quantities!", "warning");
				$("#send").text("Post");
				alert(scan);
			 			 control.disabled="disabled";	
		}	
		
		
		if(to_pay_amm > due_topay_man ){
			swal("To Pay Ammount High !", "Please Reduce The Ammount !", "warning");
				$("#send").text("Post");
			 			 control.disabled="disabled";	
		}
		if(due_topaid > due_pay_man ){
			swal("Payed Ammount High !", "Please Reduce The Ammount !", "warning");
				$("#send").text("Post");
			 			 control.disabled="disabled";	
		}
		if((quantity > 1) && (scan==1) && (due_topaid != due_pay_man )){
				swal("Pay Don't Balance  !", "Please the pay Ammout !", "warning");
				$("#send").text("Post");
			 			 control.disabled="disabled";	
		}	
		if((quantity > 1) && (scan==1) && (to_pay_amm  != due_topay_man )){
				swal("To Pay Don't Balance  !", "Please the to pay Ammount !", "warning");
				$("#send").text("Post");
			 			 control.disabled="disabled";	
		}
			if((number < scan) && (to_pay_amm >= due_topay_man) && (due_topaid  >= due_pay_man)){
				swal("Ammount And Quantity!", "YOU CANT SEND LESS FOR FULL AMMOUNT!", "warning");
				$("#send").text("Post");
			
			 			 control.disabled="disabled";
		}if((number < scan) && (to_pay_amm == due_topay_man) && (due_topaid  == due_pay_man)){
				swal("Ammount And Quantity!", "YOU CANT SEND LESS FOR FULL AMMOUNT!", "warning");
				$("#send").text("Post");
			
			 			 control.disabled="disabled";
		}
		if((number == scan) && (to_pay_amm < due_topay_man) && (due_topaid  < due_pay_man)){
				swal("Ammount And Quantity!", "Increase the amount to fit the quantity!", "warning");
				$("#send").text("Post");
			
			 			 control.disabled="disabled";
		}
	}else if(status_check=="paid"){
		
		if($("#number").val() == ""){
		swal("Fill Quantity!", "Please Fill Quantity!", "warning");
				$("#send").text("Post");
			 			 control.disabled="disabled";
		}
			if(number > scan){
			swal("Quantity is Greater !", "Please Reduce The number of quantities!", "warning");
				$("#send").text("Post");
				alert(scan);
			 			 control.disabled="disabled";	
		}
			if(to_pay_amm > amount_remain_new){
			swal("The Ammount is High !", "Please Reduce The Ammount !", "warning");
				$("#send").text("Post");
			 			 control.disabled="disabled";	
		}
				if((number < scan) && (to_pay_amm >= amount_remain_new)){
				swal("Ammount And Quantity!", "YOU CANT SEND ONE FOR FULL AMMOUNT!", "warning");
				$("#send").text("Post");
			
			 			 control.disabled="disabled";
		}if((number == scan) && (to_pay_amm < amount_remain_new)){
				swal("Ammount And Quantity!", "Increase the amount to fit the quantity!", "warning");
				$("#send").text("Post");
			
			 			 control.disabled="disabled";
		}
			if((isNaN(to_pay_amm) ) && (number < scan) ) {
				swal("Reduce The Number!", "Reduce the number to match the amount!", "warning");
				$("#send").text("Post");
			
			 			 control.disabled="disabled";
		}
	}else if(status_check=="To be Payed"){
		
		if($("#number").val() == ""){
			swal("Fill Quantity!", "Please Fill Quantity!", "warning");
				$("#send").text("Post");
			
			 			 control.disabled="disabled";
		}
		if(number > scan){
			swal("Quantity is Greater !", "Please Reduce The number of quantities!", "warning");
				$("#send").text("Post");
				alert(scan);
			 			 control.disabled="disabled";	
		}
		
		if(to_pay_amm > amount_remain_new){
			swal("The Ammount is High !", "Please Reduce The Ammount !", "warning");
				$("#send").text("Post");
			 			 control.disabled="disabled";	
		}
		if((number < scan) && (to_pay_amm >= amount_remain_new)){
				swal("Ammount And Quantity!", "YOU CANT SEND ONE FOR FULL AMMOUNT!", "warning");
				$("#send").text("Post");
			
			 			 control.disabled="disabled";
		}if((number == scan) && (to_pay_amm < amount_remain_new)){
				swal("Ammount And Quantity!", "Increase the amount to fit the quantity!", "warning");
				$("#send").text("Post");
			
			 			 control.disabled="disabled";
		}
		if((isNaN(to_pay_amm) ) && (number < scan) ) {
				swal("Reduce The Number!", "Reduce the number to match the amount!", "warning");
				$("#send").text("Post");
			
			 			 control.disabled="disabled";
		}
	}
		
if(isNaN(due_topaid)) {
var due_topaid = 0;
}	
if(isNaN(to_pay_amm)) {
var to_pay_amm = 0;
}
		
	  $.ajax({
				url:"save_insert.php",
				method:"POST",
				data:{code:code,scanned:scanned,amount:amount,scan:scan,quantity:quantity,time_remain:time_remain,choosen:choosen,number:number,to_pay_amm:to_pay_amm,date_insert:date_insert,due_topaid:due_topaid},
				success:function(response){
				
					/*
			if($.trim(response)=="exist"){
swal("Waring!", "Already sent Remain Zero!", "warning");
	 control.disabled="disabled";
}			if($.trim(response)=="inside"){
swal("Waring found in Manifest!", "you can not insert two luggage in the same manifest delete one to proceed!", "warning");
	 control.disabled="disabled";
}
window.location="check_luggages.php?id="+choosen+"&date="+date_insert+"&open=open";	
*/

}
						

			});
	
		
			$("#send").text("redirecting...");
    return false;  
  }
});
});