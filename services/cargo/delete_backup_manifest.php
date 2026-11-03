<script src="js/vendor/jquery-1.12.4.min.js"></script>
<?php
include 'connect.php';
$select=mysqli_query($db,"SELECT *  FROM manifest_deleted WHERE updated =''");
$select_num=mysqli_num_rows($select);

if($select_num !=0){
   	WHILE($get=mysqli_fetch_array($select)){
    	    $id=$get['id2'];
		
	
?>
<script>

$(document).ready(function(){
	 if (navigator.onLine) {
			var id = "<?php echo $id;?>";

			
	
	  $.ajax({
				url:"https://srclassiccoach.co.tz/delete_manifest_backup.php",
				method:"POST",
				data:{id:id},
				success:function(response){


}
			
			});
			
					  $.ajax({
				url:"update_manifest_backup_return.php",
				method:"POST",
				contentType: 'application/x-www-form-urlencoded',
				data:{id:id},
				success:function(response){


}
			
			});
			
	
			
						} else {

}
		});
</script>
<?php

	 
			
	}
}


?>