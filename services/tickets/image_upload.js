    $(document).ready(function(){
      
      $("#submit").click(function(e){
      	e.preventDefault();

      	let form_data = new FormData();
      	let img = $("#myImage")[0].files;
 
        // Check image selected or not
        if(img.length > 0){
        	form_data.append('my_image', img[0]);

        	$.ajax({
        		url: 'upload.php',
        		type: 'post',
        		data: form_data,
        		contentType: false,
                processData: false,
                success: function(res){
                	const data = JSON.parse(res);

                	if (data.error != 1) {
                       let path = "uploads/"+data.src;
                       $("#preImg").attr("src", path);
                       $("#preImg").fadeOut(1).fadeIn(1000);
                       $("#myImage").val('');

                	}else {
                		$("#errorMs").text(data.em);
                	}
                }

        	});
         
        }else {
           $("#errorMs").text("Please select an image.");
        }
      });
        
    });