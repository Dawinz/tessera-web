<?php
require_once __DIR__ . '/includes/tessera_staff_boot.php';
/**
 * Home dashboard - route sales pie chart fragment.
 */
ini_set('display_errors', '0');
error_reporting(E_ERROR | E_PARSE);
include 'connect.php';
date_default_timezone_set('Africa/Dar_es_Salaam');

$year = date('Y');
$month_one = date('m');
$amounts = [];
$labels = [];

$tenantSql = tessera_sql_tenant_eq('b');
$sql = $db->query(
	"SELECT b.route_id, SUM(b.cost) AS cost, r.start_route, r.end_route
	 FROM booking b
	 LEFT JOIN routes r ON r.route_id = b.route_id
	 WHERE b.month='$month_one' AND b.year='$year' AND {$tenantSql}
	 GROUP BY b.route_id, r.start_route, r.end_route
	 ORDER BY cost DESC"
);
if ($sql) {
	while ($row = $sql->fetch_assoc()) {
		$amounts[] = (float) ($row['cost'] ?? 0);
		$start = trim((string) ($row['start_route'] ?? ''));
		$end = trim((string) ($row['end_route'] ?? ''));
		$labels[] = ($start !== '' || $end !== '')
			? trim($start . ' to ' . $end)
			: ('Route ' . (string) ($row['route_id'] ?? ''));
	}
}

if (!$amounts) {
	$amounts = [0];
	$labels = ['No sales this month'];
}

$amounts_js = implode(',', array_map(static function ($v) {
	return json_encode((float) $v);
}, $amounts));
$labels_js = implode(',', array_map('json_encode', $labels));
?>
<div id="simple_pie_chart" class="apex-charts" dir="ltr"></div>
<script>
(function () {
  var el = document.querySelector("#simple_pie_chart");
  if (!el || typeof ApexCharts === "undefined") return;
  var chart = new ApexCharts(el, {
    series: [<?php echo $amounts_js; ?>],
    chart: { height: 300, type: "pie", foreColor: "#d5e4ee" },
    labels: [<?php echo $labels_js; ?>],
    legend: { position: "bottom" },
    dataLabels: { dropShadow: { enabled: false } },
    colors: ["#038edc", "#51d28c", "#f7cc53", "#f34e4e", "#564ab1", "#5b73e8"]
  });
  chart.render();
})();
</script>
