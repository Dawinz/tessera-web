<?php
include '../connect.php';

$insert=mysqli_query($db,"INSERT INTO roles
 (name,level,file,username,category,image,arrangement) 
VALUES   ('Daily user sales','Administrator','user_ticket_sales_summary.php','','Reports','','22')");

$insert=mysqli_query($db,"INSERT INTO roles
 (name,level,file,username,category,image,arrangement) 
VALUES   ('Daily user sales','Super Administrator','user_ticket_sales_summary.php','','Reports','','22')");




?>