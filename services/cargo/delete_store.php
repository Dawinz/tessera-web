<?php
require_once __DIR__ . '/includes/tessera_staff_boot.php';
session_start();
?>
<?php
include 'connect.php';
require_once __DIR__ . '/includes/tessera_tenant.php';
$id = $_POST['id'];
$code = $_POST['code'];
$password = $_POST['password'];
$username_uploader =$_SESSION['username'];






$select2=mysqli_query($db,"SELECT *  FROM  users  where username='".$_SESSION['username']."' and password='$password'");
$select_num2=mysqli_num_rows($select2);
if($select_num2 !=0){
		WHILE($get123=mysqli_fetch_array($select2)){
$role=$get123['role'];
}
	
date_default_timezone_set('Africa/Dar_es_Salaam');
$date_insert = date('Y-m-d', time());
$date_time_insert = date(' h:i A', time());

$select2=mysqli_query($db,"SELECT *  FROM  manifest WHERE code='$code' AND " . tessera_sql_tenant_eq() . "");
$select_num2=mysqli_num_rows($select2);
if($select_num2 ==0){
	
	$select12=mysqli_query($db,"SELECT *  FROM  parcel_info WHERE code='$code' AND " . tessera_sql_tenant_eq() . "");
$select_num12=mysqli_num_rows($select12);
if($select_num12 !=0){
WHILE($get12=mysqli_fetch_array($select12)){
$to_be_orginal=$get12['to_be'];
$paid_original=$get12['paid'];
$status=$get12['status'];
$trip=$get12['account']; 
$date_insert=$get12['date_insert']; 
$month=$get12['month']; 
$username_original=$get12['username']; 

}}

if(($username_original !=$username_uploader) and ($role !="Administrator")){
	echo "not_uploader";
	}else{


	
$insert=mysqli_query($db,"DELETE from parcel_info WHERE id = '$id' AND " . tessera_sql_tenant_eq() . "");

$insert2=mysqli_query($db,"UPDATE notification SET status='deleted' ,deleted='".$_SESSION['username']."',deleted_date='$date_insert at $date_time_insert'  WHERE code = '$code'");
echo "deleted";
}}else{
	echo "found";
}

}else{
echo "wrong ";	
}




?>