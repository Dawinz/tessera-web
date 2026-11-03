
			<?php		
include 'connect.php';
session_start();
$username_change=$_SESSION['username'];
date_default_timezone_set('Africa/Dar_es_Salaam');
$date_now = date('Y-m-d', time());


		$sum_amount =0;
$select23=mysqli_query($db,"SELECT SUM(paid) AS  paid FROM parcel_info  where   username='$username_change'and date_insert='$date_now' and open !='yes' ");
$select_num23=mysqli_num_rows($select23);
if($select_num23 !=0){
   	WHILE($get123=mysqli_fetch_array($select23)){
		$amount=$get123['paid'];
		
		
		 $sum_amount += $amount;


}
echo number_format($sum_amount,2);
}else{
echo "0.00";	
	
}		
		
		?>