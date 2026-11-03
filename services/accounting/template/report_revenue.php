<?php require_once __DIR__ . '/includes/auth_bootstrap.php'; ?>
<?php
// Revenue Analysis (tickets and cargo). SAMPLE DATA: see includes/report_sample_data.php.
include 'includes/report_sample_data.php';

$trips = rp_sample_trips();

$by_route = array();
foreach ($trips as $trip) {
	$route = $trip['route'];
	if (!isset($by_route[$route])) {
		$by_route[$route] = array('route' => $route, 'trips' => 0, 'ticket' => 0, 'cargo_paid' => 0, 'cargo_to_pay' => 0, 'total' => 0);
	}
	$by_route[$route]['trips']++;
	$by_route[$route]['ticket'] += $trip['ticket'];
	$by_route[$route]['cargo_paid'] += $trip['cargo_paid'];
	$by_route[$route]['cargo_to_pay'] += $trip['cargo_to_pay'];
	$by_route[$route]['total'] += rp_trip_revenue($trip);
}
$totals = rp_sum($by_route, array('trips', 'ticket', 'cargo_paid', 'cargo_to_pay', 'total'));
foreach ($by_route as $route => $row) {
	$by_route[$route]['share'] = rp_percent_of($row['total'], $totals['total']);
	$by_route[$route]['per_trip'] = $row['total'] / $row['trips'];
}
uasort($by_route, function ($a, $b) {
	return $b['total'] <=> $a['total'];
});
$totals['share'] = 100;
$totals['per_trip'] = $totals['total'] / $totals['trips'];

$mix = array(
	array('source' => 'Ticket sales', 'amount' => $totals['ticket']),
	array('source' => 'Cargo - prepaid', 'amount' => $totals['cargo_paid']),
	array('source' => 'Cargo - pay on delivery', 'amount' => $totals['cargo_to_pay']),
);
foreach ($mix as $i => $row) {
	$mix[$i]['share'] = rp_percent_of($row['amount'], $totals['total']);
}

$report = array(
	'group' => 'Financial Reports',
	'title' => 'Revenue Analysis',
	'description' => 'Where the money comes from: ticket sales and cargo (from the cargo system) by route.',
	'filters' => rp_period_filters(array(
		array('type' => 'select', 'id' => 'route', 'label' => 'Route', 'options' => rp_route_options()),
		array('type' => 'select', 'id' => 'vehicle', 'label' => 'Vehicle', 'options' => rp_vehicle_options()),
	)),
	'kpis' => array(
		array('label' => 'Total revenue', 'value' => $totals['total'], 'tone' => 'primary', 'icon' => 'fa-coins', 'note' => $totals['trips'] . ' trips'),
		array('label' => 'Ticket sales', 'value' => $totals['ticket'], 'tone' => 'success', 'icon' => 'fa-ticket-alt', 'note' => number_format(rp_percent_of($totals['ticket'], $totals['total']), 1) . '% of revenue'),
		array('label' => 'Cargo revenue', 'value' => $totals['cargo_paid'] + $totals['cargo_to_pay'], 'tone' => 'warning', 'icon' => 'fa-boxes', 'note' => number_format(rp_percent_of($totals['cargo_paid'] + $totals['cargo_to_pay'], $totals['total']), 1) . '% of revenue'),
		array('label' => 'Average per trip', 'value' => $totals['per_trip'], 'tone' => 'primary', 'icon' => 'fa-bus', 'note' => 'Revenue per trip'),
	),
	'blocks' => array(
		array(
			'type' => 'table',
			'title' => 'Revenue mix',
			'width' => 'half',
			'columns' => array(
				array('key' => 'source', 'label' => 'Source'),
				array('key' => 'amount', 'label' => 'Amount', 'format' => 'money'),
				array('key' => 'share', 'label' => 'Share', 'format' => 'share'),
			),
			'rows' => $mix,
			'footer' => array('amount' => $totals['total']),
		),
		array(
			'type' => 'table',
			'title' => 'Share of revenue by route',
			'width' => 'half',
			'columns' => array(
				array('key' => 'route', 'label' => 'Route'),
				array('key' => 'share', 'label' => 'Share', 'format' => 'share'),
			),
			'rows' => $by_route,
		),
		array(
			'type' => 'table',
			'title' => 'Revenue by route',
			'columns' => array(
				array('key' => 'route', 'label' => 'Route'),
				array('key' => 'trips', 'label' => 'Trips', 'format' => 'number'),
				array('key' => 'ticket', 'label' => 'Tickets', 'format' => 'money'),
				array('key' => 'cargo_paid', 'label' => 'Cargo prepaid', 'format' => 'money'),
				array('key' => 'cargo_to_pay', 'label' => 'Cargo on delivery', 'format' => 'money'),
				array('key' => 'total', 'label' => 'Total', 'format' => 'money'),
				array('key' => 'per_trip', 'label' => 'Per trip', 'format' => 'money'),
			),
			'rows' => $by_route,
			'footer' => $totals,
		),
	),
);

include 'includes/report_page.php';
