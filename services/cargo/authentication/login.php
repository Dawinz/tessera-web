<?php
include 'connect.php';
$username = $_POST['username'];
$password = $_POST['password'];


$select1=mysqli_query($db,"SELECT *  FROM users WHERE username='$username' and password='$password' OR email='$username'and password='$password' OR number='$username'and password='$password' and  status='active' ");
$select_num1=mysqli_num_rows($select1);

if($select_num1 !=0){
   	WHILE($get13=mysqli_fetch_array($select1)){
    	    $username=$get13['username']; 
		

		
	}
session_start();
 $_SESSION['username'] = $username;

 $select2=mysqli_query($db,"SELECT *  FROM  users  where username='$username'");
$select_num2=mysqli_num_rows($select2);
if($select_num2 !=0){
   	WHILE($get12=mysqli_fetch_array($select2)){
    	    $level =$get12['role']; 

		echo $level;
	}
}
}else{
	echo "nothing";
}

?>