<?php
$Date = "05/04/2021";
echo ''.date('d-M-y', strtotime($Date. ' + 5 days')). '<br>';

echo date('m/d/y', strtotime($Date. ' + 2 days'));
?>