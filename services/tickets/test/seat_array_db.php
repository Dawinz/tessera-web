 <style>
#seat_array {
  spanst-style-type: none;
  margin-top: 8;
  padding: 0;
  overflow: hidden;
  
}

.seat_col {
  float: left;
}

.available {
color:black;
padding:12px;
margin-left:4px;
width:20px;
background-image: url("armchair2.png");
background-size: contain;
background-repeat: no-repeat;
}
.center {
color:black;
padding:12px;
margin-left:4px;
width:20px;
background-size: contain;
background-repeat: no-repeat;
}
.seat_no{
position:relative;
bottom:6px;
font-size:14px;
text-align: center;

}
</style>
<?php
require_once __DIR__ . '/../includes/tessera_tenant.php';


$roles = [
	'admin' => "1",
	'editor' => 2
];




?>
<?php

include "../connect.php";


$seat_pushed=array("red","green");

include '../connect.php';

$select1=mysqli_query($db,"SELECT *  FROM  booking WHERE " . tessera_sql_tenant_eq() . " AND (route_day_id='22' and trip_date='08/15/2023' and   route_id ='2')");
$select_num1=mysqli_num_rows($select1);
if($select_num1 !=0){
}
$roles = [
	'admin' => "1",
	'editor' => 2
];

   	WHILE($get13=mysqli_fetch_array($select1)){
 
 $seat_no= $get13['seat_no']; 
 $status= $get13['status']; 

$roles["$seat_no"] = $seat_no;


}

foreach ($roles as $subject => $marks){ 
if($marks=="H4"){
	    echo " ".$marks." in ".$subject."<br>"; 

}else{
	
}
} 



$var_56 = function() {
 $alphabet = range('A', 'Z');


 
 $add_number = 0;
$seat = array("ii-ii", "ii-ii", "ii-ii", "iiiii");
$arrLength = count($seat);


for($i = 0; $i < $arrLength; $i++) {
	?>
	<div id="seat_array">
	<?php
	
	$value = $seat[$i];
	$my_array1 = str_split($value);
$sub_string_total = strlen($value);

$str = $value; 
$sub_string_no= substr_count($str, "i"); 

$new_no = 1;
for($x = 0; $x < $sub_string_total; $x++) {	

	$new_number = $value[$x];
if($new_number=="-"){
	echo "<span class='first center seat_col'></span>";
}else{
$new_no = $new_no +1;	
$new_minus = $new_no - 1;
$new_again = $new_minus + $add_number;
$let_no = $new_minus;
$new_seat_row=$i+1;
$letter = $alphabet[$i];

	echo "<span class='first available seat_col'>".$letter."".$let_no."</span>";
}
}

$add_number=$add_number + $sub_string_no;
$add_number = $add_number  ;
echo "<br>";
?>
</div>
<?php

}

};
$var_56();
?>


