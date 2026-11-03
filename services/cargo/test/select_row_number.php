<?php
include 'connect.php';


$select2=mysqli_query($db,"SELECT 
	ROW_NUMBER() OVER (
		ORDER BY id
	) row_num,
    username,
    id
FROM 
	users
ORDER BY 
	id
  ");
$select_num2=mysqli_num_rows($select2);
if($select_num2 !=0){
   	WHILE($get12=mysqli_fetch_array($select2)){
			$username =$get12['username'];
			$row_num =$get12['row_num'];
		
echo "$row_num $username <br>";
		
	}
}

?>