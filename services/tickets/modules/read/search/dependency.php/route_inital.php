<?php
include_once '../../../connection/connect.php';
include_once '../../general/Read.php';
error_reporting(E_ALL);
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);

header('Content-Type: application/json');

$route_starting_point = $_POST['route_starting_point'] ?? '';
$route_ending_point = $_POST['route_ending_point'] ?? '';
$company_initial = "SRC";
$select = ['name'];
$where = ['id' => $route_starting_point];
$start_location = new Read(['select' => $select, 'pdo' => $pdo, 'where' => $where, 'table' => "locations_names", 'order' => "", '' => ""]);
$route_starting_point_name = $start_location->rows[0]['name'] ?? '';

$where = ['id' => $route_ending_point];
$end_location = new Read(['select' => $select, 'pdo' => $pdo, 'where' => $where, 'table' => "locations_names", 'order' => "", '' => ""]);
$route_ending_point_name = $end_location->rows[0]['name'] ?? '';

function locationAbbreviation($name) {
    $letters = preg_replace('/[^A-Za-z]/', '', $name ?? '');
    if ($letters === '') {
        return '';
    }
    return ucfirst(strtolower(substr($letters, 0, 3)));
}

$start_abbreviation = locationAbbreviation($route_starting_point_name);
$end_abbreviation = locationAbbreviation($route_ending_point_name);
$route_abbreviation = trim($start_abbreviation . ' to ' . $end_abbreviation);

$route_initial = strtoupper(substr($route_starting_point_name, 0, 1) . substr($route_ending_point_name, 0, 1));



$select = ['id'];
$where = ['route_initial_letters' => $route_initial, 'company_initial' => $company_initial];
$existing_route = new Read(['select' => $select, 'pdo' => $pdo, 'where' => $where, 'table' => "routes", 'order' => "", '' => ""]);
$route_initial_exists = $existing_route->available_row > 0;

echo json_encode([
    'route_initial' => $route_initial,
    'route_abbreviation' => $route_abbreviation,
    'exists' => $route_initial_exists
]);
