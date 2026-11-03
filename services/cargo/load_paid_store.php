		<?php
		$sum_paid = 0;
		include 'connect.php';
		$route= $_GET['route'];
				$route = preg_replace('/_/',' ', $route);

$select13=mysqli_query($db,"SELECT paid   FROM  store  WHERE location='$route' ");
$select_num13=mysqli_num_rows($select13);
if($select_num13 !=0){
   	WHILE($get133=mysqli_fetch_array($select13)){
    	 
					$paid_store =$get133['paid']; 
					 $sum_paid += $paid_store;


	



	}
	
	echo number_format($sum_paid);
}
		?>
				