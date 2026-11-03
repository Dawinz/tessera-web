/*$(document).ready(function(){
  $("#form_terms").on('submit', function(e){
        e.preventDefault();
editor = $("#editor").val();
this_route_new = $("#this_route_new").val();

if(editor ==""){
alert("empty");	
}else{
	
	$.ajax({
				url:"insert/terms.php",
				method:"POST",
				data:{editor:editor,this_route_new:this_route_new},
				success:function(response){
		alert(response);
	}
			
			});
}

		
		});		
		
		});
		*/