<?php
$date= $_POST['date'];
$date2= $_POST['date2'];
$trip = $_POST['trip'];
$trip_assign = $_POST['trip_assign'];
$yyyy = $_POST['yyyy'];

session_start();
$_SESSION['date'] = $date;
$_SESSION['date2'] = $date2;
$_SESSION['trip'] = $trip;
$_SESSION['trip_assign'] = $trip_assign;
$_SESSION['yyyy'] = $yyyy;



?>