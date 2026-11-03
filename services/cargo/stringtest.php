<?php
$string = '2020-03-17 09:07:18';
  $rest = substr($string, 5, 2);  
  $first = substr($string, 0, 4);
   $last = substr($string, 8, 2);
  echo"$rest $first $last";
?>