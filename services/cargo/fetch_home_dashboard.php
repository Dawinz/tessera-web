<?php
/**
 * Single JSON payload for Cargo Home KPIs (one monthly_charges scan).
 * Chart fragments stay separate for now; KPIs no longer fan out 4×.
 */
require_once __DIR__ . '/includes/tessera_staff_boot.php';
include 'connect.php';
date_default_timezone_set('Africa/Dar_es_Salaam');

header('Content-Type: application/json; charset=utf-8');
header('Cache-Control: no-store');

$routeRaw = (string) ($_GET['route'] ?? '');
$route = $routeRaw !== '' ? base64_decode($routeRaw, true) : '';
if ($route === false || $route === '') {
	$route = $routeRaw;
}
$routeEsc = mysqli_real_escape_string($db, (string) $route);
$month = date('m-Y');

$sumPaid = 0.0;
$sumToBe = 0.0;
$sumNoToBe = 0;
$sumNoPaid = 0;
$sumNoDue = 0;

$q = mysqli_query(
	$db,
	"SELECT COALESCE(SUM(paid),0) AS paid,
	        COALESCE(SUM(to_be),0) AS to_be,
	        COALESCE(SUM(no_to_be),0) AS no_to_be,
	        COALESCE(SUM(no_paid),0) AS no_paid,
	        COALESCE(SUM(no_due),0) AS no_due
	 FROM monthly_charges
	 WHERE date_insert = '{$month}' AND location='{$routeEsc}'"
);
if ($q && ($r = mysqli_fetch_assoc($q))) {
	$sumPaid = (float) ($r['paid'] ?? 0);
	$sumToBe = (float) ($r['to_be'] ?? 0);
	$sumNoToBe = (int) ($r['no_to_be'] ?? 0);
	$sumNoPaid = (int) ($r['no_paid'] ?? 0);
	$sumNoDue = (int) ($r['no_due'] ?? 0);
}

$total = $sumPaid + $sumToBe;
$luggageCount = $sumNoToBe + $sumNoPaid + $sumNoDue;

echo json_encode([
	'ok' => true,
	'route' => $route,
	'kpis' => [
		'total' => $total,
		'total_label' => number_format($total, 2) . ' <span class="sr-kpi-currency">TZS</span>',
		'paid' => $sumPaid,
		'paid_label' => number_format($sumPaid, 2) . ' <span class="sr-kpi-currency">TZS</span>',
		'to_be' => $sumToBe,
		'to_be_label' => number_format($sumToBe, 2) . ' <span class="sr-kpi-currency">TZS</span>',
		'luggage' => $luggageCount,
		'luggage_label' => number_format($luggageCount),
	],
], JSON_UNESCAPED_UNICODE);
