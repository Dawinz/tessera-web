
			<?php		
include 'connect.php';
session_start();
$username_change=$_SESSION['username'];
date_default_timezone_set('Africa/Dar_es_Salaam');
$date_now = date('Y-m-d', time());


		$sum_to_be =0;
$select23=mysqli_query($db,"SELECT SUM(to_be) AS  to_be FROM parcel_info  where   username='$username_change'and date_insert='$date_now' and open !='yes'");
$select_num23=mysqli_num_rows($select23);
if($select_num23 !=0){
   	WHILE($get123=mysqli_fetch_array($select23)){
		$to_be=$get123['to_be'];

		
		 $sum_to_be += $to_be;


}
echo number_format($sum_to_be,2);
}else{
echo "0.00";	
	
}		
		
		?>