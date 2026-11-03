<?php
include '../connect/connect_cargo.php';
include '../connect/connect.php';
$route = @$_POST['route'];
$choose_sub_route = @$_POST['choose_sub_route'];
$currency = @$_POST['currency'];
$detail =  @$_POST['detail'];
$detail = str_replace("'" , "_", $detail);
$detail = str_replace("," , "~", $detail);

if($choose_sub_route == $route ){
	echo "equal";
}else{
$select=mysqli_query($db,"SELECT *  FROM   sub_route   where fullname='$choose_sub_route' and main_route='$route'");
$select_num=mysqli_num_rows($select);
if($select_num ==0){
$select=mysqli_query($db,"SELECT *  FROM   route   where fullname='$choose_sub_route' ");
$select_num=mysqli_num_rows($select);
if($select_num !=0){
   	WHILE($get=mysqli_fetch_array($select)){
    	    $route_name =$get['name']; 
    	    $start_route =$get['start_point']; 
    	    $end_route =$get['end_point']; 
    	    $route_full_name =$get['fullname']; 
    	    $currency_value =$get['currency_value']; 

	}
}

	$sqlQ = "INSERT INTO  sub_route  (start_point,end_point,fullname,name,currency,currency_value,detail,main_route) VALUES (?,?,?,?,?,?,?,?)"; 
                $stmt = $db->prepare($sqlQ); 
                $stmt->bind_param("ssssssss", $start_route, $end_route,$choose_sub_route,$route_name,$currency,$currency_value,$detail,$route); 
                $insert = $stmt->execute();	

}else{
	echo "found";
}

	
}




?>