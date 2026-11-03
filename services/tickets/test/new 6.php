 <?php
 $add_number = 0;
$seat = array("-i-ii", "ii-ii", "ii-ii");
$arrLength = count($seat);
echo $arrLength;
foreach ($seat as $value) {
$value = $value;
$my_array1 = str_split($value);

$str = $value; 
  
 $sub_string_no= substr_count($str, "i"); 
 echo $sub_string_no;
$sub_string_no = $sub_string_no++;
foreach ($my_array1 as $x) {
	
if($x=="-"){
	
}else{
	
	echo "".$x."";
}
}

 
echo "<br>";
}

?> 
<?php

$animals = array("Bear", "Leopard", "Tiger");

$arrLength = count($animals);

// loop through the array

for($i = 0; $i < $arrLength; $i++) {

    echo $animals[$i];

    echo "</ br>";

}

?>