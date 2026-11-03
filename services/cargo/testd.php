<?php
ini_set('display_errors', 1);
$data = "13/15/10";    
$whatIWant = substr($data, strpos($data, "/") + 1);    
echo $whatIWant;
?>