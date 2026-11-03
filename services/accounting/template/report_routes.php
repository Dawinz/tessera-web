<?php require_once __DIR__ . '/includes/auth_bootstrap.php'; ?>
<?php
// Route Profitability. SAMPLE DATA: see includes/report_sample_data.php.
include 'includes/report_sample_data.php';

$groups = rp_group_trips(rp_sample_trips(), 'route');
$totals = rp_sum($groups, array('trips', 'ticket', 'cargo', 'revenue', 'expenses', 'net'));

$rows = array();
foreach ($groups as $group) {
	$rows[] = array(
		'route' => $group['name'],
		'trips' => $group['trips'],
		'ticket' => $group['ticket'],
		'cargo' => $group['cargo'],
		'revenue' => $group['revenue'],
		'expenses' => $group['expenses'],
		'net' => $group['net'],
		'revenue_per_trip' => $group['revenue'] / $group['trips'],
		'margin' => rp_percent_of($group['net'], $group['revenue']),
		'share' => rp_percent_of($group['net'], $totals['net']),
	);
}
$totals['revenue_per_trip'] = $totals['revenue'] / $totals['trips'];
$totals['margin'] = rp_percent_of($totals['net'], $totals['revenue']);

$report = array(
	'group' => 'Fleet Reports',
	'title' => 'Route Profitability',
	'description' => 'Which routes make the most money once trip costs are paid.',
	'filters' => rp_period_filters(array(
		array('type' => 'select', 'id' => 'vehicle', 'label' => 'Vehicle', 'options' => rp_vehicle_options()),
	)),
	'kpis' => array(
		array('label' => 'Routes', 'value' => count($rows), 'format' => 'number', 'tone' => 'primary', 'icon' => 'fa-route', 'note' => $totals['trips'] . ' trips'),
		array('label' => 'Revenue', 'value' => $totals['revenue'], 'tone' => 'primary', 'icon' => 'fa-coins', 'note' => 'Tickets and cargo'),
		array('label' => 'Trip profit', 'value' => $totals['net'], 'tone' => 'success', 'icon' => 'fa-chart-line', 'note' => 'Before office expenses, ' . number_format($totals['margin'], 1) . '% margin'),
		array('label' => 'Top route', 'value' => $rows[0]['share'], 'format' => 'percent', 'tone' => 'success', 'icon' => 'fa-trophy', 'note' => $rows[0]['route'] . ' share of profit'),
	),
	'blocks' => array(
		array(
			'type' => 'table',
			'title' => 'By route',
			'columns' => array(
				array('key' => 'route', 'label' => 'Route'),
				array('key' => 'trips', 'label' => 'Trips', 'format' => 'number'),
				array('key' => 'ticket', 'label' => 'Tickets', 'format' => 'money'),
				array('key' => 'cargo', 'label' => 'Cargo', 'format' => 'money'),
				array('key' => 'revenue', 'label' => 'Revenue', 'format' => 'money'),
				array('key' => 'expenses', 'label' => 'Costs', 'format' => 'money'),
				array('key' => 'net', 'label' => 'Net', 'format' => 'money'),
				array('key' => 'revenue_per_trip', 'label' => 'Revenue per trip', 'format' => 'money'),
				array('key' => 'margin', 'label' => 'Margin', 'format' => 'percent'),
				array('key' => 'share', 'label' => 'Share of profit', 'format' => 'share'),
			),
			'rows' => $rows,
			'footer' => $totals,
		),
	),
);

include 'includes/report_page.php';
