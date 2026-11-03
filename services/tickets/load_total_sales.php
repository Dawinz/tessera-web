<?php
require_once __DIR__ . '/includes/tessera_staff_boot.php';
require_once __DIR__ . '/includes/home_dashboard_route.php';
include 'connect.php';
date_default_timezone_set('Africa/Dar_es_Salaam');

$scope = tessera_home_route_scope($db, (string) ($_GET['route'] ?? ''));
$query_date = date('Y-m-d');
$date1 = date('Y-m-01', strtotime($query_date));
$date2 = date('Y-m-t', strtotime($query_date));

$where = $scope['sql_tenant'] . "book_date >= '$date1' AND book_date <= '$date2' AND delete_book=''";
if ($scope['sql_route'] !== '') {
	$where = $scope['sql_route'] . $where;
}

$sql = $db->query("SELECT SUM(cost) AS cost FROM booking WHERE {$where}");
$amount = 0.0;
if ($sql && ($data = $sql->fetch_assoc())) {
	$amount = (float) ($data['cost'] ?? 0);
}

echo $amount > 0
	? '<span class="sr-kpi-value">' . number_format($amount, 0) . ' Tsh</span>'
	: '<span class="sr-kpi-value">0 Tsh</span>';
