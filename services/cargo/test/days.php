<?php
date_default_timezone_set('Africa/Dar_es_Salaam');
$date_insert = date('Y-m-d', time());
$total_number = date("t", strtotime($date_insert));
function addOrdinalNumberSuffix($num) {
    if (!in_array(($num % 100),array(11,12,13))){
      switch ($num % 10) {
        // Handle 1st, 2nd, 3rd
        case 1:  return $num.'st';
        case 2:  return $num.'nd';
        case 3:  return $num.'rd';
      }
    }
    return $num.'th';
  }

for ($x = 1; $x <= $total_number; $x++) {
$result_no =  addOrdinalNumberSuffix($x);
echo "'$result_no',";

}



?>