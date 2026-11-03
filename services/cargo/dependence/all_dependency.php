<?php
include './connect.php';

function vehicle($db) {
   
$query2 = "SELECT name FROM vehicles ";
$result2 = mysqli_query($db, $query2);
while($row2 = mysqli_fetch_array($result2))
{
 $name .= '<option value="'.$row2["name"].'">'.$row2["name"].'</option>';
}
echo $name;
}
function route($db) {
   
$query2 = "SELECT route_full_name FROM routes ";
$result2 = mysqli_query($db, $query2);
while($row2 = mysqli_fetch_array($result2))
{
 $name .= '<option value="'.$row2["route_full_name"].'">'.$row2["route_full_name"].'</option>';
}
echo $name;
}
function transit_route($db,$route) {
  $name=""; 
$query2 = "SELECT secondary_route FROM transit where primary_route='$route' ";
$result2 = mysqli_query($db, $query2);
while($row2 = mysqli_fetch_array($result2))
{
 $name .= '<option value="'.$row2["secondary_route"].'">'.$row2["secondary_route"].'</option>';
}
echo $name;
}
Function my_assigned_routes($db,$username){
$select12=mysqli_query($db,"SELECT *  FROM  assigned_routes where username='$username'  order by id asc");
$select_num12=mysqli_num_rows($select12);
if($select_num12 !=0){
	   	WHILE($get132=mysqli_fetch_array($select12)){
$route_full_name =$get132['route_full_name']; 
echo "  <option>".$route_full_name."</option>";
}

}
	

}
?>