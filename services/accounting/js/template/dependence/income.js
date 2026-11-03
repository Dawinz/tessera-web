	$(document).on('change','.part_Category_exp', function(){
my_id = $(this).attr('id')
my_id = my_id.replace(/\D/g,'');
part_category = $("#part_Category_exp"+my_id+" option:selected" ).text();

	    $.ajax({
    url:"./dependence/fetch_exp.php",
    method:"POST",
    data_exp:{part_category:part_category},
	   beforeSend: function(){
		   
	$("#part_detail_exp"+my_id+" option:selected" ).text("loading..."); 
            },
    success:function(part_category){
		alert(data_exp);
$('#part_detail_exp'+my_id+'').html(data_exp);

    }
   })
});