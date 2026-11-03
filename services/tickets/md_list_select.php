<div style=" overflow: hidden;"><?php
$select_all_seats = $_POST['select_all_seats'];

    $my_string = $select_all_seats;    

    // passing "," as the delimiter

    $my_array1 = explode(",", $my_string);

for($i = 0;$i < count($my_array1);$i++)
if($i==0){
	echo "<div id=''style=' animation: blinker 1s linear infinite;float: left; padding:2px;'class=flex-shrink-0 me-3><div class='avatar-sm'><span class='avatar-title bg-light text-danger rounded-circle'>".$my_array1[$i]."</span></div></div>";

}else{
	echo "<div style=' float: left; padding:2px;'class=flex-shrink-0 me-3><div class='avatar-sm'><span class='avatar-title bg-light text-primary rounded-circle'>".$my_array1[$i]."</span></div></div>";
}
?>
</div>