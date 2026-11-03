		<?php
		$sum_to_be = 0;
		include 'connect.php';
		$route= $_GET['route'];
				$route = preg_replace('/_/',' ', $route);

	
		
			$select13=mysqli_query($db,"SELECT to_be   FROM  store  WHERE location='$route' ");
$select_num13=mysqli_num_rows($select13);
if($select_num13 !=0){
   	WHILE($get133=mysqli_fetch_array($select13)){
    	
					$to_be_store =$get133['to_be']; 
					$sum_to_be += $to_be_store;



	}
	echo number_format($sum_to_be);
}
		
		?>
				