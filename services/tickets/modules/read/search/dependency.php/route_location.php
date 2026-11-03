<?php
include_once '../../../connection/connect.php';
include_once '../../general/Read.php';
error_reporting(E_ALL);
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);

header('Content-Type: application/json');

$point_id = $_POST['point_id'] ?? '';
$select = ['name','id'];
$where = ['location_id' => $point_id];
$points = new Read(['select' => $select, 'pdo' => $pdo, 'where' => $where, 'table' => "route_location_points", 'order' => "", '' => ""]);
$points_array = $points->rows;

echo json_encode($points_array);