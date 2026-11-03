<?php
$select_all_seats = $_POST['seat_array_list'];

    $my_string = $select_all_seats;    

    // passing "," as the delimiter

    $my_array1 = explode(",", $my_string);



for($i = 0;$i < count($my_array1);$i++){
if($i==0){

$first_seat = $my_array1[$i];

}
}
$prev_id = $first_seat;
?>

<?php
$select_all_seats = $_POST['seat_array_list'];
    $my_string = $select_all_seats;    

    // passing "," as the delimiter

    $my_array1 = explode(",", $my_string);



for($i = 0;$i < count($my_array1);$i++){
$first_seat2 = $my_array1[$i];

if($first_seat2==$prev_id){
	
}else{
echo "$first_seat2 ";
	
}
}
?>