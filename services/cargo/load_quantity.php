		<?php
		
		$route= $_GET['route'];
		$route = preg_replace('/_/',' ', $route);
		
				include_once 'connect.php';
				$select2=mysqli_query($db,"SELECT *  FROM  parcel_info where store !='out' and account='$route'");
$select_num2=mysqli_num_rows($select2);
if($select_num2 !=0){
echo "$select_num2";
}else{
	
}
				