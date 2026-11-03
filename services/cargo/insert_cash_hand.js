$(document).ready(function(){
$('#savebtn2').click(function(){
var amount = $("#due_amount").val();
var title = $("#title").val();

var time= $("#time2").val();

if(amount==""){
	 if (window.TesseraStates) {
		TesseraStates.toastWarn('Amount required', 'Enter the cash amount.');
	 } else {
		swal("Fill The Ammount!", "Please Enter Ammount!", "warning");
	 }
 control.disabled="disabled";
}else if(time==""){
	 if (window.TesseraStates) {
		TesseraStates.toastWarn('Date required', 'Enter the cash date.');
	 } else {
		swal("Fill Date!", "Please Enter Date!", "warning");
	 }
 control.disabled="disabled";
}else if(title==""){
	 if (window.TesseraStates) {
		TesseraStates.toastWarn('Authorizer required', 'Enter who authorized this cash.');
	 } else {
		swal("Authorizer!", "Please Enter the person who authorized The money!", "warning");
	 }
 control.disabled="disabled";
} 
$("#savebtn2").text('loading');
  $.ajax({
				url:"insert_cash_hand.php",
				method:"POST",
				data:{amount:amount,title:title,time:time},
				success:function(response){
					if (window.TesseraStates) {
						TesseraStates.toastOk('Cash saved', 'Opening cash entry recorded.');
					}
window.location="cash_hand.php";
},
				error: function () {
					$("#savebtn2").text('Save');
					if (window.TesseraStates) {
						TesseraStates.toastErr('Payment failed', 'Could not save cash-in-hand.');
					}
				}
			
			});
})
});