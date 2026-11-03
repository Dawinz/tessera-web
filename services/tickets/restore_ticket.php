<?php
require_once __DIR__ . '/includes/tessera_tenant.php';

session_start();
include 'connect.php';

$seat_id = $_POST['seat_id'];
$id = $_POST['id'];



$select1=mysqli_query($db,"SELECT *  FROM users WHERE  username='".@$_SESSION['username']."' and role='Super Administrator'");
$select_num1=mysqli_num_rows($select1);
if($select_num1 !=0){

$insert=mysqli_query($db,"UPDATE  booking SET delete_book='' WHERE " . tessera_sql_tenant_eq() . " AND (id='$seat_id')");
$insert=mysqli_query($db,"UPDATE   deleted  SET button='disabled',status_deleted='recovered',recoverd_by='".@$_SESSION['username']."' WHERE id='$id'");


}else{
	
	echo "not";
}
?>