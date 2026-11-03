<?php
require_once __DIR__ . '/includes/tessera_staff_boot.php';
include 'connect.php';
$id = $_POST['id'];
$select=mysqli_query($db,"SELECT *  FROM vehicle_name WHERE id2 ='$id'");
$select_num=mysqli_num_rows($select);

if($select_num ==0){
	$insert=mysqli_query($db,"INSERT INTO vehicle_name (id2,Name,Driver,Conductor,Route,luggage_no,to_pay,paid,due,status,date,passcode,creaded_by,locked_by,l_paid,l_to_be,l_amount,img,disable,disable2,updated) 
VALUES ('".$_POST['id']."','".$_POST['Name']."','".$_POST['Driver']."','".$_POST['Conductor']."','".$_POST['Route']."','".$_POST['luggage_no']."',
'".$_POST['to_pay']."','".$_POST['paid']."','".$_POST['due']."','".$_POST['status']."','".$_POST['date']."','".$_POST['passcode']."','".$_POST['creaded_by']."',
'".$_POST['locked_by']."','".$_POST['l_paid']."','".$_POST['l_to_be']."',
'".$_POST['l_amount']."','".$_POST['img']."','".$_POST['disable']."','".$_POST['disable2']."','updated')");
echo $_POST['detail'];

}else{
	
}
?>