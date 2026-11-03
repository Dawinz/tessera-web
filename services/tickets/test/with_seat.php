 <?php
 $alphabet = range('A', 'Z');


 
 $add_number = 0;
$seat = array("ii-ii", "ii-ii", "ii-ii", "iiii");
$arrLength = count($seat);


for($i = 0; $i < $arrLength; $i++) {
	
	
	$value = $seat[$i];
	$my_array1 = str_split($value);
$sub_string_total = strlen($value);

$str = $value; 
$sub_string_no= substr_count($str, "i"); 

$new_no = 1;
for($x = 0; $x < $sub_string_total; $x++) {	

	$new_number = $value[$x];
if($new_number=="-"){
	echo ".";
}else{
$new_no = $new_no +1;	
$new_minus = $new_no - 1;
$new_again = $new_minus + $add_number;
$let_no = $new_minus-1;
$letter = $alphabet[$let_no];
$new_seat_row=$i+1;
	echo "".$letter."".$new_seat_row."";
}
}

$add_number=$add_number + $sub_string_no;
$add_number = $add_number  ;
echo "<br>";
}

?> 
