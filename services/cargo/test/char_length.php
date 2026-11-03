<?php
include '../connect.php' ;

$select=mysqli_query($db,"SELECT DISTINCT s_no FROM parcel_info WHERE CHAR_LENGTH(s_no) = 13 and  s_no  LIKE '+255%'");
$select_num=mysqli_num_rows($select);

if($select_num !=0){
	echo "$select_num";
WHILE($get=mysqli_fetch_array($select)){
	
		$s_no = $get['s_no'];	
		echo "$s_no <br>";
}
}



?>