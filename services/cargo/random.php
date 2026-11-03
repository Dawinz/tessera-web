<?php
$n=10; 
function getName($n) { 
    $characters = '012346895687aks'; 
    $randomString = ''; 
  
    for ($i = 0; $i < $n; $i++) { 
        $index = rand(0, strlen($characters) - 1); 
        $randomString .= $characters[$index]; 
    } 
  
    return $randomString; 
} 
  

$code2 = getName($n); 
$code3 = "sr";
$sr_code =  str_replace(' ', '', "$code3 $code2");
echo $sr_code;
?>