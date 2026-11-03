<?php
include 'connect.php';
$account = $_POST['account'];
$detail = $_POST['detail'];


											$select1=mysqli_query($db,"SELECT *  FROM account Where name = '$account'");
$select_num1=mysqli_num_rows($select1);

if($select_num1 !=0){
echo"nooo";
}else{
	   $insert=mysqli_query($db,"INSERT INTO account VALUES('','$account','$detail')");

}

?>