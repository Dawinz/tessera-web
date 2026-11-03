<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
<?php
								include'connect.php';
			
										
$select=mysqli_query($db,"SELECT *  FROM vehicle_name WHERE updated ='' and disable2='disabled' ");
$select_num=mysqli_num_rows($select);

if($select_num !=0){
   	WHILE($get=mysqli_fetch_array($select)){
    	    $id =$get['id'];
			$Name=$get['Name'];
			$Driver=$get['Driver'];
			$Conductor=$get['Conductor'];
			$Route=$get['Route'];
			$luggage_no=$get['luggage_no'];
			$to_pay=$get['to_pay'];
			$paid=$get['paid'];
			$due=$get['due'];
			$status=$get['status'];
			$date=$get['date'];
			$passcode=$get['passcode'];
			
			$creaded_by=$get['creaded_by'];
			$locked_by=$get['locked_by'];
			$l_paid=$get['l_paid'];
			$l_to_be=$get['l_to_be'];
			$l_amount=$get['l_amount'];
			
			$img=$get['img'];
			$disable=$get['disable'];
			$disable2=$get['disable2'];
			$updated=$get['updated'];
		?>
			 <script language="JavaScript">
    
 

		$(document).ready(function(){
			 if (navigator.onLine) {
			var id = "<?php echo $id;?>";
			var Name = "<?php echo $Name;?>";
			var Driver = "<?php echo $Driver;?>";
			var Conductor = "<?php echo $Conductor;?>";
			var Route = "<?php echo $Route;?>";
			var luggage_no = "<?php echo $luggage_no;?>";
			var to_pay = "<?php echo $to_pay;?>";
			var paid = "<?php echo $paid;?>";
			var due = "<?php echo $due;?>";
			var status = "<?php echo $status;?>";
			var date = "<?php echo $date;?>";
			var passcode = "<?php echo $passcode;?>";
			
			var creaded_by = "<?php echo $creaded_by;?>";
			var locked_by = "<?php echo $locked_by;?>";
			var l_paid = "<?php echo $l_paid;?>";
			var l_to_be = "<?php echo $l_to_be;?>";
			var l_amount = "<?php echo $l_amount;?>";
			
			var img = "<?php echo $img;?>";
			var disable = "<?php echo $disable;?>";
			var disable2 = "<?php echo $disable2;?>";
			var updated = "<?php echo $updated;?>";
		
	  $.ajax({
				url:"https://srclassiccoach.co.tz/insert_vehicle_back_up.php",
				method:"POST",
				contentType: 'application/x-www-form-urlencoded',
				data:{id:id,Name:Name,Driver:Driver,Conductor:Conductor,Route:Route,
				luggage_no:luggage_no,to_pay:to_pay,paid:paid,due:due,status:status,
				date:date,passcode:passcode,creaded_by:creaded_by,locked_by:locked_by,l_paid:l_paid,l_to_be:l_to_be,l_amount:l_amount,
				img:img,disable:disable,disable2:disable2,updated:updated},
				success:function(response){

}
			
			});
			
			  $.ajax({
				url:"update_vehicle_code_backup.php",
				method:"POST",
				contentType: 'application/x-www-form-urlencoded',
				data:{Name:Name,date:date},
				success:function(response){


}
			
			});
			
			} else {

}
		});
    </script>
		<?php	
}}
			
			?>