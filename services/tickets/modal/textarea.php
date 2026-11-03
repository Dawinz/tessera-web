<?php
$route_name = $_GET['route_name'];
$route_name = preg_replace('/_/',' ', $route_name);

include '../connect.php';
$terms = '';
$select=mysqli_query($db,"SELECT terms  FROM  routes WHERE fullname='$route_name'");
$select_num=mysqli_num_rows($select);
if($select_num !=0){
   	WHILE($get=mysqli_fetch_array($select)){
 

 $terms= $get['terms'];

}}

$terms = base64_decode($terms);
$terms = str_replace("^" , "'", $terms);


?>             

			 <form class="needs-validation" name="form_terms" id="form_terms" novalidate>
                                     
                                              

                                  
											
                                      <div class="card-body">
 <textarea name="terms" id="editor"><?php echo  $terms;?></textarea>
                                    </div>
                                               
                                               
   <div class="modal-footer">
                                                        <button type="button" class="btn btn-light"
                                                            data-bs-dismiss="modal">Close</button>
                                                        <button type="submit" class="btn btn-primary" id="register_terms"><span id="register_terms_text">Register</span></button>
                                                    </div>
													</form> 
													
													
<script>
(function () {
  function initRouteTermsEditor() {
    var el = document.querySelector('#route_term_load #editor');
    if (!el || typeof ClassicEditor === 'undefined') {
      return;
    }
    ClassicEditor.create(el).catch(function (error) {
      console.error(error);
    });
  }

  function loadClassicEditor(callback) {
    if (typeof ClassicEditor !== 'undefined') {
      callback();
      return;
    }
    var existing = document.querySelector('script[data-sr-ckeditor="1"]');
    if (existing) {
      existing.addEventListener('load', callback, { once: true });
      return;
    }
    var script = document.createElement('script');
    script.src = 'assets/js/ckeditor-classic.js';
    script.setAttribute('data-sr-ckeditor', '1');
    script.onload = callback;
    script.onerror = function () {
      console.error('Unable to load CKEditor for route terms.');
    };
    document.head.appendChild(script);
  }

  loadClassicEditor(initRouteTermsEditor);
})();
		
			 
	
    </script>
	<script>

		  $("#form_terms").on('submit', function(e){
        e.preventDefault();
editor = $("#editor").val();
this_route_new ="<?php echo $route_name;?>";
if(editor ==""){
				  Swal.fire({
 
  icon: "error",
  title: "Fill terms",
  
});	
}else{
	$("#register_terms_text").text("loading..");
	$.ajax({
				url:"insert/terms.php",
				method:"POST",
				data:{editor:editor,this_route_new:this_route_new},
				success:function(response){
						$("#register_terms_text").text("Register");

						  Swal.fire({
  position: "top-end",
  icon: "success",
  title: "Terms Has been saved",
  showConfirmButton: false,
  timer: 1500
});	
	
		
		
	}
			
			});
}


});

</script>
