 <?php
 $add_number = 1;
$seat = array("-i-ii", "ii-ii", "ii-ii", "ii-ii");
$arrLength = count($seat);


for($i = 0; $i < $arrLength; $i++) {
	$value = $seat[$i];
	$my_array1 = str_split($value);

$str = $value; 
$sub_string_no= substr_count($str, "i"); 


	for($x = 0; $x < $sub_string_no; $x++) {
	
if($x=="-"){
	echo "";
}else{
	
	echo "".$x+$add_number."";
}
}
$add_number=$add_number + $sub_string_no;
$add_number = $add_number  ;

echo "<br>";
}

?> 
