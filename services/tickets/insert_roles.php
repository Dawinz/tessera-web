<?php
include'connect.php';

$insert=mysqli_query($db,"INSERT INTO roles 
(name,level,category,file) VALUES 
('Manifest','Agent','Manifest','manifest.php')");




?>