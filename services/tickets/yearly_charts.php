<?php
/**
 * Home dashboard - yearly ticket sales chart fragment.
 */
require_once __DIR__ . '/includes/tessera_staff_boot.php';
require_once __DIR__ . '/includes/home_dashboard_route.php';
ini_set('display_errors', '0');
error_reporting(E_ERROR | E_PARSE);
include 'connect.php';
date_default_timezone_set('Africa/Dar_es_Salaam');

$scope = tessera_home_route_scope($db, (string) ($_GET['route'] ?? ''));
$year = date('Y');
$titleSuffix = $scope['all'] ? 'all routes' : ($scope['route_name'] !== '' ? $scope['route_name'] : 'selected route');

$byMonth = array_fill(1, 12, 0.0);
$where = $scope['sql_tenant'] . "year='{$year}' AND delete_book=''";
if ($scope['sql_route'] !== '') {
	$where = $scope['sql_route'] . $where;
}
$sql = $db->query("SELECT month, SUM(cost) AS cost FROM booking WHERE {$where} GROUP BY month");
if ($sql) {
	while ($data = $sql->fetch_assoc()) {
		$m = (int) ($data['month'] ?? 0);
		if ($m >= 1 && $m <= 12) {
			$byMonth[$m] = (float) ($data['cost'] ?? 0);
		}
	}
}

$series = [];
$categories = [];
for ($x = 1; $x <= 12; $x++) {
	$series[] = $byMonth[$x];
	$categories[] = date('F', mktime(0, 0, 0, $x, 10));
}

$series_js = implode(',', array_map(static function ($v) {
	return json_encode((float) $v);
}, $series));
$cats_js = implode(',', array_map('json_encode', $categories));
$titleSuffix_e = htmlspecialchars($titleSuffix, ENT_QUOTES, 'UTF-8');
?>
<div id="column_chart_datalabel" class="apex-charts" dir="ltr"></div>
<script>
(function () {
  var el = document.querySelector("#column_chart_datalabel");
  if (!el || typeof ApexCharts === "undefined") return;
  new ApexCharts(el, {
    chart: { height: 350, type: "bar", toolbar: { show: false }, foreColor: "#d5e4ee" },
    plotOptions: { bar: { borderRadius: 4, columnWidth: "55%", dataLabels: { position: "top" } } },
    dataLabels: {
      enabled: true,
      formatter: function (v) { return Number(v).toLocaleString() + " Tsh"; },
      offsetY: -18,
      style: { fontSize: "11px", colors: ["#d5e4ee"] }
    },
    series: [{ name: "Sales", data: [<?php echo $series_js; ?>] }],
    colors: ["#038edc"],
    grid: { borderColor: "rgba(255,255,255,0.06)" },
    xaxis: {
      categories: [<?php echo $cats_js; ?>],
      axisBorder: { show: false },
      axisTicks: { show: false }
    },
    yaxis: {
      labels: {
        formatter: function (v) { return Number(v).toLocaleString(); }
      }
    },
    title: {
      text: "Yearly sales - <?php echo $titleSuffix_e; ?> - <?php echo (int) $year; ?>",
      align: "left",
      style: { fontSize: "13px", fontWeight: 600, color: "#d5e4ee" }
    }
  }).render();
})();
</script>
