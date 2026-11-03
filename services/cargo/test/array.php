<?php
$host = 'localhost';
$user_name = 'root';
$password = 'Teccross@360';
$db_name = 'sr';
$con = mysqli_connect($host,$user_name,$password,$db_name);
$sql = "select * from  contact_info";
$result = mysqli_query($con,$sql);
$response = array();

while($row = mysqli_fetch_array($result)){
    array_push($response,array("Name"=>$row["name"],"Email"=>$row["email"]));
}
echo json_encode($response);

?>