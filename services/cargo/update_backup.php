<script src="js/vendor/jquery-1.12.4.min.js"></script>
<?php
include 'connect.php';
$select=mysqli_query($db,"SELECT *  FROM manifest WHERE updated =''");
$select_num=mysqli_num_rows($select);

if($select_num !=0){
   	WHILE($get=mysqli_fetch_array($select)){
    	    $id=$get['id'];
			$code=$get['code'];
	 $username=$get['username'];
	
	   $amount=$get['amount'];
	    $trip_date=$get['trip_date'];
		 $bus=$get['bus'];
		  $paid=$get['paid'];
		   $to_be=$get['to_be'];
		    $status=$get['status'];
			$uploader=$get['uploader'];
			$q_left=$get['q_left'];
			$quantity=$get['quantity'];
			$detail=$get['detail'];
						$last_left=$get['last_left'];
			$parcel_uploader=$get['parcel_uploader'];

			$checked=$get['checked'];
						$timetaken=$get['timetaken'];

			$username_taken=$get['username_taken'];
			$updated=$get['updated'];
			
?>
<script>

$(document).ready(function(){
	 if (navigator.onLine) {
			var id = "<?php echo $id;?>";
			var code = "<?php echo $code;?>";
			var username = "<?php echo $username;?>";
			var amount = "<?php echo $amount;?>";
			var trip_date = "<?php echo $trip_date;?>";
			var bus = "<?php echo $bus;?>";
			var paid = "<?php echo $paid;?>";
			var to_be = "<?php echo $to_be;?>";
			var status = "<?php echo $status;?>";
			var uploader = "<?php echo $uploader;?>";
			var q_left = "<?php echo $q_left;?>";
			var quantity = "<?php echo $quantity;?>";
			
			var last_left = "<?php echo $last_left;?>";
			var parcel_uploader = "<?php echo $parcel_uploader;?>";
			var checked = "<?php echo $checked;?>";
			var timetaken = "<?php echo $timetaken;?>";
			var username_taken = "<?php echo $username_taken;?>";
			var updated = "<?php echo $updated;?>";
			var detail = "<?php echo $detail;?>";
			
	
	  $.ajax({
				url:"https://srclassiccoach.co.tz/insert_update_backup.php",
				method:"POST",
				data:{id:id,code:code,username:username,amount:amount,trip_date:trip_date,
				bus:bus,paid:paid,to_be:to_be,status:status,uploader:uploader,
				q_left:q_left,quantity:quantity,last_left:last_left,parcel_uploader:parcel_uploader,checked:checked,timetaken:timetaken,username_taken:username_taken,
				updated:updated,detail:detail},
				success:function(response){


}
			
			});
			
				  $.ajax({
				url:"update_manifest_backup.php",
				method:"POST",
				contentType: 'application/x-www-form-urlencoded',
				data:{code:code},
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
