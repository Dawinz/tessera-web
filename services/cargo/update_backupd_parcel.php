
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
<?php
include 'connect.php';
$select=mysqli_query($db,"SELECT *  FROM notification WHERE updated ='' ");
$select_num=mysqli_num_rows($select);

if($select_num !=0){
   	WHILE($get=mysqli_fetch_array($select)){
		$id = $get['id'];	
	$code=$get['code'];	
	$notification=$get['notification'];	
if($notification == 'changes'){
	$select=mysqli_query($db,"SELECT *  FROM parcel_info WHERE code ='$code' ");
$select_num=mysqli_num_rows($select);

if($select_num !=0){
   	WHILE($get=mysqli_fetch_array($select)){
    	    $quantity=$get['question'];
			$date=$get['date']; 
			$trip=$get['account'];
			$s_no=$get['s_no']; 
			$s_name=$get['s_name'];
			$s_surname=$get['s_surname'];
			$r_name=$get['r_name'];
			$r_surname=$get['r_surname']; 	
			$r_no=$get['r_no'];
			$cost=$get['amount'];
			$due=$get['due']; 	
			$date_insert=$get['date_insert']; 
			$detail=$get['detail']; 
			$detail=base64_decode($detail);
			$detail2=$get['detail']; 
			$code = $get['code'];
		$status=$get['status']; 
		$time=$get['time']; 
		$uploader=$get['uploader']; 
		
		$username=$get['username']; 
		$bus=$get['bus'];
		$trip_date=$get['trip_date']; 
		$paid=$get['paid']; 
		$to_be=$get['to_be']; 
		$month=$get['month']; 
		
		
		$payment_status=$get['payment_status']; 
		$rec_status=$get['rec_status'];
		$answer=$get['answer']; 
		$store=$get['store']; 
		$edited=$get['edited']; 
		$scanned=$get['scanned']; 
$amount_remain=$get['amount_remain'];


?>
	 <script language="JavaScript">
    
 

		$(document).ready(function(){
			 if (navigator.onLine) {
			var quantity = "<?php echo $quantity;?>";
			var id = "<?php echo $id;?>";
			var date = "<?php echo $date;?>";
			var trip = "<?php echo $trip;?>";
			var s_no = "<?php echo $s_no;?>";
			var s_name = "<?php echo $s_name;?>";
			var s_surname = "<?php echo $s_surname;?>";
			var r_name = "<?php echo $r_name;?>";
			var r_surname = "<?php echo $r_surname;?>";
			var r_no = "<?php echo $r_no;?>";
			var cost = "<?php echo $cost;?>";
			var due = "<?php echo $due;?>";
			var date_insert = "<?php echo $date_insert;?>";
			
			var detail = "<?php echo $detail2;?>";
			var code = "<?php echo $code;?>";
			var status = "<?php echo $status;?>";
			var time = "<?php echo $time;?>";
			var uploader = "<?php echo $uploader;?>";
			
			var username = "<?php echo $username;?>";
			var bus = "<?php echo $bus;?>";
			var trip_date = "<?php echo $trip_date;?>";
			var paid = "<?php echo $paid;?>";
			var to_be = "<?php echo $to_be;?>";
			var month = "<?php echo $month;?>";
			
			var payment_status = "<?php echo $payment_status;?>";
			var rec_status = "<?php echo $rec_status;?>";
			var answer = "<?php echo $answer;?>";
			var store = "<?php echo $store;?>";
			var edited = "<?php echo $edited;?>";
			var scanned = "<?php echo $scanned;?>";
			var amount_remain = "<?php echo $amount_remain;?>";
			
	  $.ajax({
				url:"https://srclassiccoach.co.tz/updated_delete_backup.php",
				method:"POST",
				contentType: 'application/x-www-form-urlencoded',
				data:{quantity:quantity,date:date,trip:trip,s_no:s_no,s_name:s_name,
				s_surname:s_surname,r_no:r_no,cost:cost,due:due,date_insert:date_insert,
				detail:detail,code:code,status:status,time:time,uploader:uploader,r_name:r_name,r_surname:r_surname,
				username:username,bus:bus,trip_date:trip_date,paid:paid,to_be:to_be,month:month,
				payment_status:payment_status,rec_status:rec_status,answer:answer,store:store,edited:edited,scanned:scanned,amount_remain:amount_remain},
				success:function(response){

}
			
			});
			

				  $.ajax({
				url:"update_notification_delete.php",
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
}}
	
	
}else{
		$select=mysqli_query($db,"SELECT *  FROM parcel_info WHERE code ='$code' ");
$select_num=mysqli_num_rows($select);

if($select_num !=0){
   	WHILE($get=mysqli_fetch_array($select)){
    	    $id=$get['id']; 
			$quantity=$get['question'];
			$date=$get['date']; 
			$trip=$get['account'];
			$s_no=$get['s_no']; 
			$s_name=$get['s_name'];
			$s_surname=$get['s_surname'];
			$r_name=$get['r_name'];
			$r_surname=$get['r_surname']; 	
			$r_no=$get['r_no'];
			$cost=$get['amount'];
			$due=$get['due']; 	
			$date_insert=$get['date_insert']; 
			$detail=$get['detail']; 
			$detail=base64_decode($detail);
			$detail2=$get['detail']; 
			$code = $get['code'];
		$status=$get['status']; 
		$time=$get['time']; 
		$uploader=$get['uploader']; 
		
		$username=$get['username']; 
		$bus=$get['bus'];
		$trip_date=$get['trip_date']; 
		$paid=$get['paid']; 
		$to_be=$get['to_be']; 
		$month=$get['month']; 
		
		
		$payment_status=$get['payment_status']; 
		$rec_status=$get['rec_status'];
		$answer=$get['answer']; 
		$store=$get['store']; 
		$edited=$get['edited']; 
		$scanned=$get['scanned']; 
$amount_remain=$get['amount_remain']; 

?>
	 <script language="JavaScript">
    
 

		$(document).ready(function(){
			 if (navigator.onLine) {
		
			var id2 = "<?php echo $id;?>";
	
			var bus = "<?php echo $bus;?>";
			var trip_date = "<?php echo $trip_date;?>";

	  $.ajax({
				url:"https://srclassiccoach.co.tz/delete_backup.php",
				method:"POST",
				contentType: 'application/x-www-form-urlencoded',
				data:{code:code,bus:bus,trip_date:trip_date,id2:id2},
				success:function(response){

}
			
			});
			
		
			
			} else {

}
		});
    </script>
<?php
}}
	

}

}}
?>