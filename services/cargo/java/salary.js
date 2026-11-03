$(document).ready(function(){
$('#insert').click(function(){
	var grade = $( "#grade").val();
	var basic = $( "#basic").val();
	var overtime = $( "#overtime").val();
	var food_a = $( "#food_a").val();
	var p_bonus = $( "#p_bonus").val();
	var gross = $( "#gross").val();
	var tax_nssf = $( "#tax_nssf").val();
	var tax_amount = $( "#tax_amount").val();
	var nssf = $( "#nssf").val();
	var pay_as = $( "#pay_as").val();
	var total_deducation = $( "#total_deducation").val();
	var net_pay = $( "#net_pay").val();
	var absent = $( "#absent").val();
 $.ajax({
				url:"./insert/salary.php",
				method:"POST",
				data:{grade:grade,basic:basic,overtime:overtime,food_a:food_a,p_bonus:p_bonus,gross:gross,tax_nssf:tax_nssf,tax_amount:tax_amount,nssf:nssf,pay_as:pay_as,total_deducation:total_deducation,net_pay:net_pay,absent:absent},
				success:function(response){
					
					
alert(response);
				
	}
			
			});
});

});