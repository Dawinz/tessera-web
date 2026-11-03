<?php
require_once __DIR__ . '/includes/tessera_staff_boot.php';
require_once __DIR__ . '/includes/home_dashboard_route.php';
ini_set('display_errors', '0');
error_reporting(E_ERROR | E_PARSE);
include 'connect.php';
date_default_timezone_set('Africa/Dar_es_Salaam');

$scope = tessera_home_route_scope($db, (string) ($_GET['route'] ?? ''));

$days = [];
for ($i = 6; $i >= 0; $i--) {
	$days[] = date('Y-m-d', strtotime("-{$i} days"));
}
$from = $days[0];
$to = $days[6];
$fromEsc = $db->real_escape_string($from);
$toEsc = $db->real_escape_string($to);

$salesByDay = array_fill_keys($days, 0.0);
$seatsByDay = array_fill_keys($days, 0);
$tripsByDay = array_fill_keys($days, 0);
$focByDay = array_fill_keys($days, 0);

$bookWhere = $scope['sql_tenant'] . "trip_date >= '{$fromEsc}' AND trip_date <= '{$toEsc}' AND delete_book='' AND status != 'booked'";
$focWhere = $scope['sql_tenant'] . "trip_date >= '{$fromEsc}' AND trip_date <= '{$toEsc}' AND delete_book='' AND cost='0' AND status != 'booked'";
if ($scope['sql_route'] !== '') {
	$bookWhere = $scope['sql_route'] . $bookWhere;
	$focWhere = $scope['sql_route'] . $focWhere;
}

$sq = $db->query("SELECT trip_date, SUM(cost) AS cost, COUNT(*) AS c FROM booking WHERE {$bookWhere} GROUP BY trip_date");
if ($sq) {
	while ($r = $sq->fetch_assoc()) {
		$d = (string) ($r['trip_date'] ?? '');
		if (!isset($salesByDay[$d])) {
			continue;
		}
		$salesByDay[$d] = (float) ($r['cost'] ?? 0);
		$seatsByDay[$d] = (int) ($r['c'] ?? 0);
	}
}

$tq = $db->query("SELECT trip_date, COUNT(*) AS c FROM manifest WHERE trip_date >= '{$fromEsc}' AND trip_date <= '{$toEsc}' GROUP BY trip_date");
if ($tq) {
	while ($r = $tq->fetch_assoc()) {
		$d = (string) ($r['trip_date'] ?? '');
		if (isset($tripsByDay[$d])) {
			$tripsByDay[$d] = (int) ($r['c'] ?? 0);
		}
	}
}

$fq = $db->query("SELECT trip_date, COUNT(*) AS c FROM booking WHERE {$focWhere} GROUP BY trip_date");
if ($fq) {
	while ($r = $fq->fetch_assoc()) {
		$d = (string) ($r['trip_date'] ?? '');
		if (isset($focByDay[$d])) {
			$focByDay[$d] = (int) ($r['c'] ?? 0);
		}
	}
}

$sales = [];
$seats = [];
$trips = [];
$foc = [];
foreach ($days as $day) {
	$sales[] = $salesByDay[$day];
	$seats[] = $seatsByDay[$day];
	$trips[] = $tripsByDay[$day];
	$foc[] = $focByDay[$day];
}

$charts = [
	['id' => 'sparkline-chart-1', 'data' => $sales, 'color' => '#038edc'],
	['id' => 'sparkline-chart-2', 'data' => $seats, 'color' => '#51d28c'],
	['id' => 'sparkline-chart-3', 'data' => $trips, 'color' => '#5b73e8'],
	['id' => 'sparkline-chart-4', 'data' => $foc, 'color' => '#f7cc53'],
];
?>
<script>
(function () {
  if (typeof ApexCharts === "undefined") return;
  var specs = <?php echo json_encode($charts, JSON_UNESCAPED_UNICODE); ?>;
  specs.forEach(function (spec) {
    var host = document.getElementById(spec.id);
    if (!host) return;
    host.innerHTML = "";
    new ApexCharts(host, {
      series: [{ data: spec.data }],
      chart: { type: "area", height: 72, sparkline: { enabled: true }, animations: { speed: 400 } },
      stroke: { curve: "smooth", width: 2 },
      fill: { type: "gradient", gradient: { opacityFrom: 0.35, opacityTo: 0.05 } },
      colors: [spec.color]
    }).render();
  });
})();
</script>
