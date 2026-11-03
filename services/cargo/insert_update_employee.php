<?php
require_once __DIR__ . '/includes/tessera_staff_boot.php';
include'connect.php';
$id= $_POST['id'];
$firstname = $_POST['firstname'];
$middlename = $_POST['middlename'];
$surname = $_POST['surname'];
$email = $_POST['email'];
$phone = $_POST['phone'];
$grade = $_POST['grade'];
$account = $_POST['account'];
$currency = $_POST['currency'];
$status_em = $_POST['status_em'];
$sickness = $_POST['sickness'];
$detail = $_POST['detail'];
$detail = base64_encode($detail);
$birth = $_POST['birth'];
$date_join = $_POST['date_join'];
$number_e = $_POST['number_e'];

   $insert=mysqli_query($db,"UPDATE employee SET firstname='$firstname',middlename='$middlename',email='$email',phone='$phone',currency='$currency',grade='$grade',sickness='$sickness',detail='$detail',date_join='$date_join',birth='$birth',number_e='$number_e'  WHERE id ='$id'");
 
?>