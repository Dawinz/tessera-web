<?php
require_once __DIR__ . '/includes/tessera_staff_boot.php';
require_once __DIR__ . '/includes/home_dashboard_route.php';
include 'connect.php';
date_default_timezone_set('Africa/Dar_es_Salaam');

$scope = tessera_home_route_scope($db, (string) ($_GET['route'] ?? ''));
$query_date = date('Y-m-d');
$date1 = date('Y-m-01', strtotime($query_date));
$date2 = date('Y-m-t', strtotime($query_date));

$where = $scope['sql_tenant'] . "trip_date >= '$date1' AND trip_date <= '$date2' AND status != 'booked' AND delete_book=''";
if ($scope['sql_route'] !== '') {
	$where = $scope['sql_route'] . $where;
}

$count = 0;
$sql = $db->query("SELECT COUNT(*) AS c FROM booking WHERE {$where}");
if ($sql && ($row = $sql->fetch_assoc())) {
	$count = (int) ($row['c'] ?? 0);
}

echo '<span class="sr-kpi-value">' . $count . ' seats</span>';
