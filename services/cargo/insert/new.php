										

<?php
include'connect.php';
$select1=mysqli_query($db,"SELECT *  FROM situation  ORDER BY id DESC LIMIT 1 ");
$select_num1=mysqli_num_rows($select1);

if($select_num1 !=0){
   	WHILE($get13=mysqli_fetch_array($select1)){
    	    $name=$get13['ob'];   
		
	}
}
	
											?>
											<?php
 
//Our example date.
//The 4th of February 2014.
date_default_timezone_set('Africa/Dar_es_Salaam');
$date_insert = date('Y-m-t', time());
 
//Last date of current month.
$lastDateOfMonth = date("Y-m-t", strtotime($date_insert));
 
if($date_insert == $lastDateOfMonth && $name == 0){
	echo "smae";
}else{
	echo "no";
}
?>