$(document).ready(function(){
	
	
	$("#send").click(function(){
			$("#send").text("Posting...");
		var choosen = $('#choosen option:selected').text();
		    var amount = $('#amount').text();
			var code = $("#code").text();
			var scanned = $("#scan").text();
			var scan = $("#scan").text(); 
			var quantity = $("#quantity").text();	
			var time_remain = $("#time_remain").text();
			var number = $("#number").val();
		
		if(choosen=="Choose Vehicle"){
			swal("Fill Vehicle!", "Please Fill Vehicle!", "warning");
			 			 control.disabled="disabled";
		}
			
  $.ajax({
				url:"save_insert2.php",
				method:"POST",
				data:{code:code,scanned:scanned,amount:amount,scan:scan,quantity:quantity,time_remain:time_remain,choosen:choosen,number:number},
				success:function(response){
					if($.trim(response)=="exist"){
swal("Waring!", "Already sent Remain Zero!", "warning");
}
	window.location="choose.php";
	}
			
			});
		$("#send").text("Posted");
});

});