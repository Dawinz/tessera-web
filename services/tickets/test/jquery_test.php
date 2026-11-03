<!DOCTYPE html>
		<script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>

<html>
<head>
  <title>test</title>
</head>
<body>
                        <input type="text" id="inputName" placeholder="Name" required>
	<script type="text/javascript">
$(document).ready(function(){
$("#inputName").keyup(function(){
    localStorage.setItem("name", $('#inputName').val());
});

   
});
window.onload = function() {

    var name = localStorage.getItem("name");
    if (name !== null) $('#inputName').val(name);

    // ...
}
	</script>	
</body>
</html>