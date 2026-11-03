<?php
$langs = array("PHP", "JavaScript", "Python", "C++", "Ruby"); 

$newLangs = implode($langs);
// Since we are printing a string, we can use echo to display the output in the browser
echo $newLangs;


$a=array();
array_push($a,"blue","yellow");
 echo implode($a);
?>