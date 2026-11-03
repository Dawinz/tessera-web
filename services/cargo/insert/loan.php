<?php
include 'connect.php';
$loan = $_POST['loan'];
$emp = $_POST['emp'];
$return_ = $_POST['loan'];
$amount = $_POST['amount'];
$detail = $_POST['detail'];
$detail = base64_encode($detail);

											$select1=mysqli_query($db,"SELECT *  FROM employee Where number_e = '$emp'");
$select_num1=mysqli_num_rows($select1);

if($select_num1 !=0){
   	WHILE($get13=mysqli_fetch_array($select1)){
    	    $firstname=$get13['firstname'];   $lastname=$get13['surname'];   

		
	}
}
   $insert=mysqli_query($db,"INSERT INTO loan VALUES('','$emp','$loan','$return_','$amount','$detail','not paid',' $firstname $lastname')");

?>