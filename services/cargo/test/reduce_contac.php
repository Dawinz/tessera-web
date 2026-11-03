<?php
include '../connect.php';
$select12=mysqli_query($db,"SELECT *   FROM  contact");
$select_num12=mysqli_num_rows($select12);
if($select_num12 !=0){
   	WHILE($get132=mysqli_fetch_array($select12)){
    	    $s_no =$get132['s_no'];	
			$r_no =$get132['r_no'];
			$id =$get132['id'];
            $s_no_char =  strlen($s_no); 
            $r_no_char =  strlen($r_no); 


            if(($s_no_char == 13) and ($r_no_char==13)){
              
                    
            }else{

                $insert=mysqli_query($db,"DELETE from contact WHERE id = '$id'");

            }
            
            


	}
}
?>