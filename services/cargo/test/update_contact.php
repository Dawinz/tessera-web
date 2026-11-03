<?php
include 'connect.php';
$select1=mysqli_query($db,"SELECT *   FROM  contact   ");
$select_num1=mysqli_num_rows($select1);
if($select_num1 !=0){
   	WHILE($get13=mysqli_fetch_array($select1)){
    	 
			$s_no =$get13['s_no']; 
			$r_no =$get13['r_no']; 
			$id =$get13['id']; 
$s_no_char =  strlen($s_no); 
$r_no_char =  strlen($r_no); 
if(($s_no_char == 13) and ($r_no_char==13)){
$s_no = substr($s_no, 4);
$r_no = substr($r_no, 4);

$update=mysqli_query($db,"UPDATE contact SET s_no='$s_no',r_no='$r_no' WHERE id='$id'");
     
}else{
    $insert=mysqli_query($db,"DELETE from contact WHERE id = '$id'");
}
	}
}
?>