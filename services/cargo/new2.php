<?php
session_start();
$username=$_SESSION['username'];
include 'connect.php';
$password = $_POST['password'];
$id = $_POST['id'];

$select2=mysqli_query($db,"SELECT *  FROM  users  where username='$username'");
$select_num2=mysqli_num_rows($select2);
if($select_num2 !=0){
}
	
	
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
$insert=mysqli_query($db,"DELETE from manifest_exp WHERE id = '$id_delete'");
echo "$id";
}else{
$select22=mysqli_query($db,"SELECT *  FROM  manifest_exp WHERE id > '$id' and bus='$bus' and trip_date='$trip_date' LIMIT 1 ");
$select_num22=mysqli_num_rows($select22);
if($select_num22 > 1){
   	WHILE($get122=mysqli_fetch_array($select22)){
    	   
		   $id2=$get122['id'];
		 
   $insert=mysqli_query($db,"UPDATE manifest_exp SET amount='$amount',to_be='$to_be',paid='$paid'  WHERE id ='$id2'");
$insert=mysqli_query($db,"DELETE from manifest_exp WHERE id = '$id_delete'");
echo "deleted";
}		


		
	}else{
		$insert=mysqli_query($db,"DELETE from manifest_exp WHERE id = '$id_delete'");

	}
	
}
}}
?>