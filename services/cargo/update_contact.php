<?php
require_once __DIR__ . '/includes/tessera_staff_boot.php';

include 'connect.php';
$select1345=mysqli_query($db,"SELECT *   FROM  contact  WHERE 
s_name ='$firstname' and 
 s_no='$s_no_plain' and 
 r_name='$r_firstname' and 
 r_no='$r_no_plain'
 ");
$select_num1345=mysqli_num_rows($select1345);
if($select_num1345 ==0){

$s_no_char =  strlen($s_no_plain); 
$r_no_char =  strlen($r_no_plain); 

if(($s_no_char == 9) and ($r_no_char==9)){
    $insert=mysqli_query($db,
    "INSERT INTO contact 
    (s_name,s_no,r_name,r_no) 
    VALUES
    ('$firstname','$s_no_plain','$r_firstname','$r_no_plain')");
        
}



	
} else{
}
?>