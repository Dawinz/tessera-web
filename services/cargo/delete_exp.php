<?php
require_once __DIR__ . '/includes/tessera_staff_boot.php';
include 'connect.php';
session_start();
$username=$_SESSION['username'];

$password = $_POST['password'];
$id = $_POST['id'];

$select23=mysqli_query($db,"SELECT *  FROM  manifest_exp WHERE id='$id'");
$select_num23=mysqli_num_rows($select23);

if($select_num23 !=0){
   	WHILE($get123=mysqli_fetch_array($select23)){
    	    $uploader =$get123['username']; 
		
}}
$select233=mysqli_query($db,"SELECT *  FROM  users  where username='$username'");
$select_num233=mysqli_num_rows($select233);

if($select_num233 !=0){
   	WHILE($get1233=mysqli_fetch_array($select233)){
    	    $level =$get1233['level']; 
			$password2 =$get1233['password']; 
			
}}
		if(($username == $uploader) && ($password2==$password) OR (($level == "admin") && ($password==$password2))){

$select2=mysqli_query($db,"SELECT *  FROM  manifest_exp WHERE id='$id'");
$select_num2=mysqli_num_rows($select2);
if($select_num2 !=0){
   	WHILE($get12=mysqli_fetch_array($select2)){
    	   $amount=$get12['amount'];
		   $to_be=$get12['to_be']; 
		   $paid=$get12['paid'];
		    $bus=$get12['bus'];
		   $trip_date=$get12['trip_date']; 
		   $id_delete=$get12['id'];
		
			

if($amount == 0){
$insert=mysqli_query($db,"DELETE from manifest_exp WHERE id = '$id'");
echo "Deleted";
}else{
$select22=mysqli_query($db,"SELECT *  FROM  manifest_exp WHERE id > '$id_delete' and bus='$bus' and trip_date='$trip_date' LIMIT 1 ");
$select_num22=mysqli_num_rows($select22);
if($select_num22 >= 1){
   	WHILE($get122=mysqli_fetch_array($select22)){
    	   
		   $id2=$get122['id'];
		 
   $insert=mysqli_query($db,"UPDATE manifest_exp SET amount='$amount',to_be='$to_be',paid='$paid'  WHERE id ='$id2'");
$insert=mysqli_query($db,"DELETE from manifest_exp WHERE id = '$id'");
echo "Deleted";
}		


		
	}else{
$insert=mysqli_query($db,"DELETE from manifest_exp WHERE id = '$id'");
echo "Deleted";

	}
	
}
}}
		}else{
			echo "not delete";
		}
?>