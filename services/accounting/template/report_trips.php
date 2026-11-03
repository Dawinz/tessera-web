<?php require_once __DIR__ . '/includes/auth_bootstrap.php'; ?>
<?php
// Trip Profitability. SAMPLE DATA: see includes/report_sample_data.php.
include 'includes/report_sample_data.php';

$trips = rp_sample_trips();

$rows = array();
foreach ($trips as $trip) {
	$revenue = rp_trip_revenue($trip);
	$net = $revenue - $trip['expenses_total'];
	$margin = rp_percent_of($net, $revenue);
	if ($net < 0) {
		$status = array('Loss', 'danger');
	} elseif ($margin < 30) {
		$status = array('Low margin', 'warning');
	} else {
		$status = array('Profitable', 'success');
	}
	$rows[] = array(
		'code' => $trip['code'],
		'date' => date('j M Y', strtotime($trip['date'])),
		'route' => $trip['route'],
		'vehicle' => $trip['vehicle'],
		'ticket' => $trip['ticket'],
		'cargo' => $trip['cargo_paid'] + $trip['cargo_to_pay'],
		'revenue' => $revenue,
		'expenses' => $trip['expenses_total'],
		'net' => $net,
		'margin' => $margin,
		'status' => $status,
	);
}
$totals = rp_sum($rows, array('ticket', 'cargo', 'revenue', 'expenses', 'net'));
$totals['margin'] = rp_percent_of($totals['net'], $totals['revenue']);
$low = count(array_filter($rows, function ($row) {
	return $row['status'][0] != 'Profitable';
}));

$report = array(
	'group' => 'Fleet Reports',
	'title' => 'Trip Profitability',
	'description' => 'Revenue, costs and margin for every trip on the manifest.',
	'filters' => rp_period_filters(array(
		array('type' => 'select', 'id' => 'route', 'label' => 'Route', 'options' => rp_route_options()),
		array('type' => 'select', 'id' => 'vehicle', 'label' => 'Vehicle', 'options' => rp_vehicle_options()),
		array('type' => 'search', 'id' => 'search', 'label' => 'Search', 'placeholder' => 'Trip code or vehicle'),
	)),
	'kpis' => array(
		array('label' => 'Trips', 'value' => count($rows), 'format' => 'number', 'tone' => 'primary', 'icon' => 'fa-route', 'note' => '1 - 30 Sep 2026'),
		array('label' => 'Revenue', 'value' => $totals['revenue'], 'tone' => 'primary', 'icon' => 'fa-coins', 'note' => 'Tickets and cargo'),
		array('label' => 'Trip profit', 'value' => $totals['net'], 'tone' => 'success', 'icon' => 'fa-chart-line', 'note' => 'Before office expenses, ' . number_format($totals['margin'], 1) . '% average margin'),
		array('label' => 'Trips to review', 'value' => $low, 'format' => 'number', 'tone' => 'warning', 'icon' => 'fa-exclamation-triangle', 'note' => 'Loss or margin under 30%'),
	),
	'blocks' => array(
		array(
			'type' => 'table',
			'title' => 'Trips',
			'columns' => array(
				array('key' => 'code', 'label' => 'Code'),
				array('key' => 'date', 'label' => 'Trip date'),
				array('key' => 'route', 'label' => 'Route'),
				array('key' => 'vehicle', 'label' => 'Vehicle'),
				array('key' => 'ticket', 'label' => 'Tickets', 'format' => 'money'),
				array('key' => 'cargo', 'label' => 'Cargo', 'format' => 'money'),
				array('key' => 'revenue', 'label' => 'Revenue', 'format' => 'money'),
				array('key' => 'expenses', 'label' => 'Costs', 'format' => 'money'),
				array('key' => 'net', 'label' => 'Net', 'format' => 'money'),
				array('key' => 'margin', 'label' => 'Margin', 'format' => 'percent'),
				array('key' => 'status', 'label' => 'Status', 'format' => 'badge'),
			),
			'rows' => $rows,
			'footer' => $totals,
		),
	),
);

include 'includes/report_page.php';
