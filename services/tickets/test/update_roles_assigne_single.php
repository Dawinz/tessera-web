<?php
include '../connect.php';
$select_first=mysqli_query($db,"SELECT *  FROM  users WHERE role='Super Administrator'");
$select_num_first=mysqli_num_rows($select_first);
if($select_num_first !=0){
   	WHILE($get_first=mysqli_fetch_array($select_first)){
 
$username= $get_first['username']; 
$role= $get_first['role'];

/*$insert=mysqli_query($db,"INSERT INTO roles_assigned
 (name,level,file,username,category,arrangement) 
VALUES   ('Daily user sales','Administrator','user_ticket_sales_summary.php','$username','Report','')");
*/
$insert=mysqli_query($db,"INSERT INTO roles_assigned
 (name,level,file,username,category,arrangement) 
VALUES   ('Daily user sales','Super Administrator','user_ticket_sales_summary.php','$username','Report','')");


}}
?>