<?php
session_start();
?>
<?php
include 'connect.php';
$id = $_POST['id'];
$code = $_POST['code'];
$password = $_POST['password'];
$username =$_SESSION['username'];


$select2=mysqli_query($db,"SELECT *  FROM  users  where username='".$_SESSION['username']."' and password='$password'");
$select_num2=mysqli_num_rows($select2);
if($select_num2 !=0){
date_default_timezone_set('Africa/Dar_es_Salaam');
$date_insert = date('Y-m-d', time());
$date_time_insert = date(' h:i A', time());

$select2=mysqli_query($db,"SELECT *  FROM  manifest WHERE code='$code' ");
$select_num2=mysqli_num_rows($select2);
if($select_num2 ==0){
$insert=mysqli_query($db,"DELETE from parcel_info WHERE id = '$id'");
$insert2=mysqli_query($db,"UPDATE notification SET status='deleted' ,deleted='".$_SESSION['username']."',deleted_date='$date_insert at $date_time_insert'  WHERE code = '$code'");
echo "deleted";
}else{
	echo "found";
}

}else{
echo "wrong ";	
}




?>