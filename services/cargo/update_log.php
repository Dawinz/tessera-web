<?php
require_once __DIR__ . '/includes/tessera_staff_boot.php';
include 'connect.php';
$selection_r= $_POST['selection_r'];
$name = $_POST['name'];
$reson = $_POST['reson'];
$time_see = $_POST['time_see'];
$insert_id = $_POST['insert_id'];
$date_check = $_POST['date_check'];
date_default_timezone_set('Africa/Dar_es_Salaam');
$date_insert = date('h:i A', time());
   $insert=mysqli_query($db,"UPDATE situation_report SET response='$selection_r',reson ='$reson',insert_time='$date_insert' WHERE date_insert ='$time_see' && str_date='$date_check' && l_id='$name'");
echo $selection_r;
?>