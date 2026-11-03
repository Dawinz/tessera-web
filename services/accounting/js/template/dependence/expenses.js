	$(document).on('change','.part_Category_exp', function(){
my_id = $(this).attr('id')
my_id = my_id.replace(/\D/g,'');
part_category_exp = $("#part_Category_exp"+my_id+" option:selected" ).text();

	    $.ajax({
    url:"./dependence/fetch_exp.php",
    method:"POST",
    data:{part_category_exp:part_category_exp},
	   beforeSend: function(){
		   
	$("#part_detail_exp"+my_id+" option:selected" ).text("loading..."); 
            },
    success:function(data){
		
$('#part_detail_exp'+my_id+'').html(data);

    }
   })
});