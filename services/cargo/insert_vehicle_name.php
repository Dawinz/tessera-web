<?php
require_once __DIR__ . '/includes/tessera_staff_boot.php';
include 'connect.php';
$name = $_POST['name'];
$select1=mysqli_query($db,"SELECT *  FROM  vehicles where name='$name' ");
$select_num1=mysqli_num_rows($select1);
if($select_num1 !=0){
echo "found";

}else{
$insert=mysqli_query($db,"INSERT INTO vehicles (name) VALUES ('$name')");
echo "insert";
}

?>