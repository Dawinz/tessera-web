<?php
include 'connect.php';
$grade = $_POST['grade'];
$basic = $_POST['basic'];
$overtime = $_POST['overtime'];
$food_a = $_POST['food_a'];
$p_bonus = $_POST['p_bonus'];
$gross= $_POST['gross'];
$tax_nssf  = $_POST['tax_nssf'];
$tax_amount = $_POST['tax_amount'];
$nssf = $_POST['nssf'];
$pay_as = $_POST['pay_as'];
$total_deducation = $_POST['total_deducation'];
$net_pay = $_POST['net_pay'];
$absent= $_POST['absent'];
   $insert=mysqli_query($db,"INSERT INTO `salary slip` VALUES('','$grade','','$basic','$overtime','$food_a','$p_bonus','$gross','$tax_nssf','$tax_amount','$nssf','$pay_as','$total_deducation','$net_pay','$absent')");

?>