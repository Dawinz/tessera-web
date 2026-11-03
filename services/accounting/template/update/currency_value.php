<?php

include '../connect/connect.php';

$first_update = $_POST['first_update'];
$secondary_update = $_POST['secondary_update'];
$value_update = $_POST['value_update'];
$id = $_POST['id_update'];


$select_currency=mysqli_query($db,"SELECT *  FROM currency_value     where  first_point='$first_update' and second_point='$secondary_update' and value='$value_update'");
$select_num_currency=mysqli_num_rows($select_currency);
if($select_num_currency ==0){

$update=mysqli_query($db,"UPDATE  currency_value   SET 
	first_point='$first_update',second_point='$secondary_update',value='$value_update'
	WHERE  id = '$id'");
}else{
	echo "found";
}
?>