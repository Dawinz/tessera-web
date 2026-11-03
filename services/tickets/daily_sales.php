<?php
require_once __DIR__ . '/includes/tessera_staff_boot.php';
require_once __DIR__ . '/includes/home_dashboard_route.php';
ini_set('display_errors', '0');
error_reporting(E_ERROR | E_PARSE);
include 'connect.php';
date_default_timezone_set('Africa/Dar_es_Salaam');

$scope = tessera_home_route_scope($db, (string) ($_GET['route'] ?? ''));
$year = date('Y');
$month_one = date('m');

$series = [];
$categories = [];
$whereBase = $scope['sql_tenant'] . "month='$month_one' AND year='$year' AND delete_book='' AND status != 'booked'";
if ($scope['sql_route'] !== '') {
	$whereBase = $scope['sql_route'] . $whereBase;
}

$dayQ = $db->query("SELECT day, SUM(cost) AS cost FROM booking WHERE {$whereBase} GROUP BY day ORDER BY CAST(day AS UNSIGNED) ASC");
if ($dayQ) {
	while ($row = $dayQ->fetch_assoc()) {
		$series[] = (float) ($row['cost'] ?? 0);
		$categories[] = (string) ($row['day'] ?? '');
	}
}

if (!$series) {
	$series = [0];
	$categories = [' - '];
}

$series_js = implode(',', array_map(static fn ($v) => json_encode((float) $v), $series));
$cats_js = implode(',', array_map('json_encode', $categories));
?>
<div id="line_chart_basic" class="apex-charts" dir="ltr"></div>
<script>
(function () {
  var el = document.querySelector("#line_chart_basic");
  if (!el || typeof ApexCharts === "undefined") return;
  new ApexCharts(el, {
    series: [{ name: "Daily sales", data: [<?php echo $series_js; ?>] }],
    chart: { height: 350, type: "area", zoom: { enabled: false }, toolbar: { show: false }, foreColor: "#d5e4ee" },
    dataLabels: { enabled: false },
    stroke: { curve: "smooth", width: 3 },
    fill: { type: "gradient", gradient: { opacityFrom: 0.35, opacityTo: 0.05 } },
    colors: ["#5b73e8"],
    grid: { borderColor: "rgba(255,255,255,0.06)" },
    markers: { size: 4, strokeWidth: 2 },
    xaxis: { categories: [<?php echo $cats_js; ?>], title: { text: "Day of month" } },
    yaxis: { labels: { formatter: function (v) { return Number(v).toLocaleString(); } } }
  }).render();
})();
</script>
