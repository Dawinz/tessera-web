<?php
function get_occurrenc($db,$start_date,$end_date,$vehicle){


    $select_oc=mysqli_query($db,"SELECT trip_date FROM   vehicle_account   where trip_date >= '$start_date' AND trip_date < '$end_date' and bus='$vehicle' group by trip_date");
$select_num_oc=mysqli_num_rows($select_oc);

return $select_num_oc;



}
?>