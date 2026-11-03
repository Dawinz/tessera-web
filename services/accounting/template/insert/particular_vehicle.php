<?php
include '../connect/connect.php';
$category_id = $_POST['category_item'];
$category_name = $_POST['category_name'];
for($count = 0; $count < count($_POST["vehicles"]); $count++)

	{
        $vehicles = $_POST['vehicles'][$count];
      




$select=mysqli_query($db,"SELECT *  FROM   vehicle_particular_details   where category_id='$category_id' and name='$particular'");
$select_num=mysqli_num_rows($select);
if($select_num ==0){

$sqlQ = "INSERT INTO  vehicle_particular_details  (name,category_id,category_name) 
VALUES (?,?,?)"; 
                $stmt = $db->prepare($sqlQ); 
                $stmt->bind_param("sss", $vehicles,$category_id, $category_name ); 
                $insert = $stmt->execute();


$select=mysqli_query($db,"SELECT *  FROM   vehicle_category   where id='$category_id' ");
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
$update=mysqli_query($db,"UPDATE  vehicle_category SET partiuclar='$new_particular_detail' WHERE  id ='$category_id'");
}else{
echo "found";
}

    }
?>