<?php
require_once __DIR__ . '/includes/tessera_staff_boot.php';
include 'connect.php';
$amount = $_POST['amount'];

$authorizer = $_POST['authorizer'];

$date= $_POST['time2'];
$id=$_POST['id'];
$amount = preg_replace('/[^0-9.]+/', '', $amount);

$select222=mysqli_query($db,"SELECT *  FROM  office_expenses  where id='$id'");
$select_num222=mysqli_num_rows($select222);
if($select_num222 !=0){
   	WHILE($get122=mysqli_fetch_array($select222)){
    	    $stock_no =$get122['stock_no']; 
		

		
	}
}
$select222=mysqli_query($db,"SELECT *  FROM  expenses  where stock_no='$stock_no'");
$select_num222=mysqli_num_rows($select222);
if($select_num222 !=0){
echo "cant";
}else{
	   $insert=mysqli_query($db,"UPDATE office_expenses SET opening_stock='$amount',authorizer ='$authorizer',open_date='$date' WHERE id ='$id'");

	
}




?>