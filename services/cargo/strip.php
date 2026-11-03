<?php
$amount="34,000";
$amount = preg_replace('/[^0-9.]+/', '', $amount);
echo $amount;
?>