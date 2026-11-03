<?php
include '../connect.php';

$editor = $_POST['editor'];
$route_name = $_POST['this_route_new'];


$editor = str_replace("'" , "^", $editor);
$editor = base64_encode($editor);


$update=mysqli_query($db,"UPDATE  routes SET terms='$editor' WHERE fullname='$route_name'");

?>