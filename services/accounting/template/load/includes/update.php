		<script>
			$(".edit<?php echo $id;?>").click(function(){
				
		var category_id = $("#category<?php echo $id;?>").text();
		var particular_id = $("#particular<?php echo $id;?>").text();
		var detail = $("#detail<?php echo $id;?>").text();
		currency_update= $(this).attr('id');
income = $.trim($("#income<?php echo $id;?>").text());
expenses = $.trim($("#expenses<?php echo $id;?>").text());
if((income!="-") && (expenses=="-")){
	$(".amount_income_update").val(income);
}
if((expenses!="-") && (income=="-")){
$(".amount_income_update").val(expenses);
}
alert(category_id);
$("#part_Category_update").text(category_id);
$("#part_Category_update").val(category_id);
$("#part_detail_update").text(particular_id);
$("#part_detail_update").val(particular_id);
$("#currency_update").text(currency_update);
$(".detail_update").val(detail);
$(".part_id").val("<?php echo $id;?>");
$("#row_number").val("<?php echo $row_num_route?>");
$("#end_point_edite").val("<?php echo $end_point?>");

			});
			
			</script>