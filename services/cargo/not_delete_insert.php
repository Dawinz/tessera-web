<?php
session_start();
?>
<?php
include 'connect.php';
$id = $_POST['id'];
$code = $_POST['counter'];

$select2=mysqli_query($db,"SELECT *  FROM  manifest WHERE code='$code' ");
$select_num2=mysqli_num_rows($select2);
if($select_num2 ==0){





$select=mysqli_query($db,"SELECT *  FROM  notification WHERE s_id='$id' ");
$select_num=mysqli_num_rows($select);
if($select_num ==0){
	$select1=mysqli_query($db,"SELECT *  FROM  parcel_info WHERE id='$id' ");
$select_num1=mysqli_num_rows($select1);
if($select_num1 !=0){
   	WHILE($get13=mysqli_fetch_array($select1)){
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
	  $insert=mysqli_query($db,"INSERT INTO notification VALUES('','$s_name','$r_name','$id','$date','$uploader','delete','$code','$quantity','$tobe','$paid','$amount','not deleted','".$_SESSION['username']."','','','','')");
$insert2=mysqli_query($db,"UPDATE parcel_info SET 	r_surname='Sent to be edited'  WHERE id = '$id'");
	}
}
echo "deleted";
}else{
	echo "found_edit";
}
}else{
	echo "found";
}


?>