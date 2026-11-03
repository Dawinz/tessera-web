<?php
session_start();
  $username=$_SESSION['username'];
    include 'connect.php';
$select1=mysqli_query($db,"SELECT *  FROM  pin WHERE username='".$_SESSION['username']."' ");
$select_num1=mysqli_num_rows($select1);
                                                 
			echo $select_num1;														   
  
?>