$(document).ready(function(){
$('#savebtn2').click(function(){
var name = $("#name").val();
var category = $( "#category option:selected" ).text();




if(category=="choose"){
	 swal("Choose!", "Fill The Choose Filled!", "warning");	
 control.disabled="disabled";
}else if(name==""){
	 swal("Name!", "Please Fill name!", "warning");	
 control.disabled="disabled";
} 
$("#savebtn2").text('loading');
  $.ajax({
				url:"insert_item.php",
				method:"POST",
				data:{category:category,name:name},
				success:function(response){
					
					
window.location="man_exp.php";
}
			
			});
})
});