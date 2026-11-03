<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>CKEditor 5 - Classic editor</title>
    <script src="https://cdn.ckeditor.com/ckeditor5/12.0.0/classic/ckeditor.js"></script>
		<script src="js/vendor/jquery-1.12.4.min.js"></script>


</head>
<body>
    <h1>Classic editor</h1>
<form class="needs-validation" name="form_terms" id="form_terms" novalidate>

    <textarea name="content" id="editor">
        &lt;p&gt;This is some sample content.&lt;/p&gt;
    </textarea>
	
	<button type="submit" id ="register_terms">adasf</button>
	</form>
    <script>
        ClassicEditor
            .create( document.querySelector( '#editor' ) )
            .catch( error => {
                console.error( error );
            } );
			

		
		  $("#form_terms").on('submit', function(e){
        e.preventDefault();
editor = $("#editor").val();
alert(editor);

});
    </script>
</body>
</html>