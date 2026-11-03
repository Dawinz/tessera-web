<?php

$select=mysqli_query($db,"SELECT *  FROM  contact WHERE no='$phone_no' and code='$inter_phone'");
$select_num=mysqli_num_rows($select);
if($select_num ==0){
	
	$s_no_str = strlen($phone_no);
			if(($s_no_str ==10)OR($s_no_str ==9)){
include 'connect.php';
$sqlQ = "INSERT INTO  contact  (firstname,lastname,no,gender,verification,code) VALUES (?,?,?,?,?,?)"; 
                $stmt = $db->prepare($sqlQ); 
                $stmt->bind_param("ssssss", $firstname,$lastname,$phone_no,$gender,$verification,$inter_phone); 
                $insert = $stmt->execute();			
			}
	
}


?>