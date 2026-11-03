<?php
include '../connect/connect.php';

for($count = 0; $count < count($_POST["category_name"]); $count++)
	{
$category_name = $_POST['category_name'][$count];
$vehicles = $_POST['vehicles'][$count];


$select_category=mysqli_query($db,"SELECT *  FROM   vehicle_particular_details   where  name='$vehicles'");
$select_num_category=mysqli_num_rows($select_category);
if($select_num_category==0){
    $select=mysqli_query($db,"SELECT *  FROM   vehicle_category   where id='$category_name'");
$select_num=mysqli_num_rows($select);
if($select_num !=0){
   	WHILE($get=mysqli_fetch_array($select)){
    	    $name =$get['name']; 
    
	}

}

$sqlQ = "INSERT INTO  vehicle_particular_details  (name,category_id,category_name) VALUES (?,?,?)"; 
                $stmt = $db->prepare($sqlQ); 
                $stmt->bind_param("sss", $vehicles, $category_name,$name ); 
                $insert = $stmt->execute();	
                $new_detail = "$particulars_detail,$name";



$select=mysqli_query($db,"SELECT *  FROM   vehicle_category   where id='$category_name'");
$select_num=mysqli_num_rows($select);
if($select_num !=0){
   	WHILE($get=mysqli_fetch_array($select)){
    	    $particulars_detail =$get['partiuclar']; 
    
	}

}

if($particulars_detail==""){
$new_particular_detail = "$vehicles";
}else{
    $new_particular_detail = "$particulars_detail,$vehicles";
}
$update=mysqli_query($db,"UPDATE  vehicle_category SET partiuclar='$new_particular_detail' WHERE  id ='$category_name'");
	

}else{

echo  "found"; 

}




	}
	
?>