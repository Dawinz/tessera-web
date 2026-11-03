<?php
$trip_date = $_POST['query'];
$choose_route = $_POST['choose_route'];

include '../connect/connect_cargo.php';
 $output = '';
 $query = "SELECT Name  FROM vehicle_name WHERE  date ='$trip_date' and Route='$choose_route'  order by id desc";
  $result = mysqli_query($db_up, $query);
  $select_route_num=mysqli_num_rows($result);
  if($select_route_num==1){
	  
  }else{
	    $output .= '<option value="">Select Vehicle</option>';
  }
 


  while($row = mysqli_fetch_array($result))
  {
   $output .= '<option value="'.$row["Name"].'">'.$row["Name"].'</option>';
  }
   echo $output;

?>