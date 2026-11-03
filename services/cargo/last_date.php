<?php
   
$date = new DateTime('now');
$date->modify('last day of this month');
echo $date->format('Y-m-d');



?>