<?php

function assigned_route ($db,$username){
$route_name_fullname = '';
$select=mysqli_query($db,"SELECT route_name  FROM  assign_route where username='$username' and status='main route' order by id asc ");
$select_num=mysqli_num_rows($select);
if($select_num !=0){
   	WHILE($get=mysqli_fetch_array($select)){
    	    $route_name =$get['route_name'];  
					
			$route_name_fullname .=  "route_name='$route_name' OR ";
  		
	}
}

echo "$route_name_fullname";
}


function route_list($db,$username){
    $route_name = '';
$query = "SELECT route_name FROM assign_route where username='$username' and status='main route' order by id asc";
$result = mysqli_query($db, $query);
while($row = mysqli_fetch_array($result))
{
 	$fullname = $row["route_name"];
  $route_name .= "<option value=\"(route_name='$fullname') AND \">$fullname</option>";
}
echo $route_name;
}


function VehicleCategory($db){
    $vehicle_name = '';
$query = "SELECT name FROM vehicle_category ";
$result = mysqli_query($db, $query);
while($row = mysqli_fetch_array($result))
{
 	$fullname = $row["name"];
  $vehicle_name .= "<option value=''>$fullname</option>";
}
echo $vehicle_name;
}



class VehicleCategory {
  public $vehicle_name;
  public $db;

  function set_details($vehicle_name, $db) {
	

$query = "SELECT name FROM vehicle_category ";
$result = mysqli_query($db, $query);
while($row = mysqli_fetch_array($result))
{
 	$fullname = $row["name"];
  $vehicle_name .= "<option value='$fullname'>$fullname</option>";
}

    $this->vehicle_name = $vehicle_name ;
  
  }

  function get_details() {
    echo $this->vehicle_name ;
  }
}
class Vehicles {
  public $vehicle_name;
  public $db_up;

  function set_details($vehicle_name, $db_up) {
	

$query = "SELECT name FROM vehicles ";
$result = mysqli_query($db_up, $query);
while($row = mysqli_fetch_array($result))
{
 	$fullname = $row["name"];
  $vehicle_name .= "<option value='$fullname'>$fullname</option>";
}

    $this->vehicle_name = $vehicle_name ;
  
  }

  function get_details() {
    echo $this->vehicle_name ;
  }
}

?>