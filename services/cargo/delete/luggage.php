<?php
session_start();
?>
<?php
include '../connect.php';
$id = $_POST['id'];
$code = $_POST['code'];
$password = $_POST['password'];
$username_check =$_SESSION['username'];
$reason =$_POST['reason'];



$select2=mysqli_query($db,"SELECT *  FROM  users  where username='".$_SESSION['username']."' and password='$password'");
$select_num2=mysqli_num_rows($select2);
if($select_num2 !=0){
		WHILE($get123=mysqli_fetch_array($select2)){
$role=$get123['role'];
}
	
date_default_timezone_set('Africa/Dar_es_Salaam');
$date_insert_now = date('Y-m-d', time());
$date_time_insert = date(' h:i A', time());

$select2=mysqli_query($db,"SELECT *  FROM  manifest WHERE code='$code' ");
$select_num2=mysqli_num_rows($select2);
if($select_num2 ==0){
	
	$select12=mysqli_query($db,"SELECT *  FROM  parcel_info WHERE code='$code'");
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

$select12=mysqli_query($db,"SELECT *  FROM  parcel_info WHERE id='$id' ");
$select_num12=mysqli_num_rows($select12);
if($select_num12 !=0){
   	WHILE($get13=mysqli_fetch_array($select12)){
    	    $code=$get13['code']; 
	        $id = $get13['id'];
			$s_name = $get13['s_name'];
			$r_name=$get13['r_name'];
			$date =$get13['date_insert'];
		$quantity = $get13['question'];
		$tobe = $get13['to_be'];
		$paid = $get13['paid'];
				$amount = $get13['amount'];
				$uploader = $get13['uploader'];
                $status=$get13['status'];
$trip=$get13['account']; 
$date_insert=$get13['date_insert']; 
$month=$get13['month']; 
$username_original=$get13['username']; 

	}
}


if(($username_original ==$username_check) or ($role =="Administrator")  or ($role =="Super Administrator") ){
		$insert=mysqli_query($db,"DELETE from parcel_info WHERE id = '$id'");
	  $insert=mysqli_query($db,"INSERT INTO notification VALUES('','$s_name','$r_name','$id','$date','$uploader','Delete','$code','$quantity','$tobe','$paid','$amount','deleted','".$_SESSION['username']."','".$_SESSION['username']."','$date_insert_now at $date_time_insert','','','','$reason')");
echo 'deleted';
include '../update/my_charges.php';

}else{
		echo "not_uploader";

}







}else{
	echo "found";
}

}else{
echo "wrong ";	
}




?>

