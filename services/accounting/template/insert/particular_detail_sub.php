<?php
include '../connect/connect.php';

for($count = 0; $count < count($_POST["particular"]); $count++)

	{
$category = $_POST['category'][$count];
$name = $_POST['particular'][$count];


$select_route=mysqli_query($db,"SELECT * FROM particular_sub  WHERE   particular_name='$category' and name ='$name' ");
$select_num_route=mysqli_num_rows($select_route);
if($select_num_route ==0){
	
$sqlQ = "INSERT INTO  particular_sub  (particular_name,name) VALUES (?,?)"; 
                $stmt = $db->prepare($sqlQ); 
                $stmt->bind_param("ss", $category, $name ); 
                $insert = $stmt->execute();	
				
$select=mysqli_query($db,"SELECT *  FROM   particulars   where name='$category' and sub_particulars != ''");
$select_num=mysqli_num_rows($select);
if($select_num !=0){
   	WHILE($get=mysqli_fetch_array($select)){
    	    $particulars_detail =$get['sub_particulars']; 
    
	}
	
	$new_detail = "$particulars_detail,$name";
	$update=mysqli_query($db,"UPDATE  particulars SET sub_particulars='$new_detail' WHERE  name ='$category'");
	


}else{
$new_detail = "$name";
	$update=mysqli_query($db,"UPDATE  particulars SET sub_particulars='$new_detail' WHERE name='$category'");

}

				
}else{
	echo "found";
}



	}
	
?>