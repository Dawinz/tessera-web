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

$values = [];
$cats = [];
$whereBase = $scope['sql_tenant'] . "month='$month_one' AND year='$year' AND delete_book=''";
if ($scope['sql_route'] !== '') {
	$whereBase = $scope['sql_route'] . $whereBase;
}

$busQ = $db->query("SELECT bus, SUM(cost) AS cost FROM booking WHERE {$whereBase} GROUP BY bus ORDER BY bus ASC");
if ($busQ) {
	while ($row = $busQ->fetch_assoc()) {
		$values[] = (float) ($row['cost'] ?? 0);
		$cats[] = (string) ($row['bus'] ?? '');
	}
}

if (!$values) {
	$values = [0];
	$cats = ['No bus sales this month'];
}

$values_js = implode(',', array_map(static fn ($v) => json_encode((float) $v), $values));
$cats_js = implode(',', array_map('json_encode', $cats));
?>
<div id="bar_chart" class="apex-charts" dir="ltr"></div>
<script>
(function () {
  var el = document.querySelector("#bar_chart");
  if (!el || typeof ApexCharts === "undefined") return;
  new ApexCharts(el, {
    chart: { height: 350, type: "bar", toolbar: { show: false }, foreColor: "#d5e4ee" },
    plotOptions: { bar: { horizontal: true, borderRadius: 4, barHeight: "65%" } },
    dataLabels: {
      enabled: true,
      formatter: function (v) { return Number(v).toLocaleString() + " Tsh"; },
      style: { fontSize: "11px" }
    },
    series: [{ name: "Income", data: [<?php echo $values_js; ?>] }],
    colors: ["#51d28c"],
    grid: { borderColor: "rgba(255,255,255,0.06)" },
    xaxis: { categories: [<?php echo $cats_js; ?>] }
  }).render();
})();
</script>
