<?php
 include '../connect/connect.php';
$id = $_POST['id'];
$availability = $_POST['availability'];



if($availability=="active"){
$button = '<i class="typcn typcn-tick "></i> Activate';
$button_view = 'info';
$status = 'not active';
$update=mysqli_query($db,"UPDATE  route  SET 
	availability='$status',
	button='$button',
	button_view='$button_view'
	WHERE id='$id'");


}else{
$button = '<i class="typcn typcn-media-stop  "></i> De active';
$button_view = 'danger';
$status = 'active';

$update=mysqli_query($db,"UPDATE  route  SET 
	availability='$status',
	button='$button',
	button_view='$button_view'
	WHERE id='$id'");

}
?>