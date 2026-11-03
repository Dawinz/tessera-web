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
			var number = parseInt($("#number").val());
			var amount_remain_new = parseInt($('#amount_remain_new').text());
			if(status_check=="paid"){
				var to_pay_amm = parseInt($("#topaid").val());
			}else{

				var to_pay_amm = parseInt($("#topaid").val().replace(/,/g, ''));
				
			}
			
		
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
		if(status2 =="paid"){
			
		}else{
		if(number==""){
			swal("Fill Quantity!", "Please Fill Quantity!", "warning");
				$("#send").text("Post");
			
			 			 control.disabled="disabled";
		}if(to_pay_amm ==""){
			swal("Fill Ammount!", "Please Fill Ammount!", "warning");
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
		}
  $.ajax({
				url:"save_insert.php",
				method:"POST",
				data:{code:code,scanned:scanned,amount:amount,scan:scan,quantity:quantity,time_remain:time_remain,choosen:choosen,number:number,to_pay_amm:to_pay_amm,date_insert:date_insert},
				success:function(response){
					if($.trim(response)=="exist"){
swal("Waring!", "Already sent Remain Zero!", "warning");
}
window.location="check_luggages.php?id="+choosen+"&date="+date_insert+"";	
}
						

			});
			$("#send").text("Posted");
});

});