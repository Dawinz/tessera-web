<?php require_once __DIR__ . '/includes/auth_bootstrap.php'; ?>
<?php
// Vehicle Performance. SAMPLE DATA: see includes/report_sample_data.php.
include 'includes/report_sample_data.php';

$groups = rp_group_trips(rp_sample_trips(), 'vehicle');
$totals = rp_sum($groups, array('trips', 'revenue', 'expenses', 'net'));

$rows = array();
foreach ($groups as $group) {
	$rows[] = array(
		'vehicle' => $group['name'],
		'trips' => $group['trips'],
		'revenue' => $group['revenue'],
		'expenses' => $group['expenses'],
		'net' => $group['net'],
		'per_trip' => $group['net'] / $group['trips'],
		'cost_per_trip' => $group['expenses'] / $group['trips'],
		'margin' => rp_percent_of($group['net'], $group['revenue']),
		'share' => rp_percent_of($group['net'], $totals['net']),
	);
}
$totals['per_trip'] = $totals['net'] / $totals['trips'];
$totals['cost_per_trip'] = $totals['expenses'] / $totals['trips'];
$totals['margin'] = rp_percent_of($totals['net'], $totals['revenue']);
$best = $rows[0];
$worst = $rows[count($rows) - 1];

$report = array(
	'group' => 'Fleet Reports',
	'title' => 'Vehicle Performance',
	'description' => 'How much each vehicle earned and cost over the period, best first.',
	'filters' => rp_period_filters(array(
		array('type' => 'select', 'id' => 'route', 'label' => 'Route', 'options' => rp_route_options()),
		array('type' => 'search', 'id' => 'search', 'label' => 'Search', 'placeholder' => 'Vehicle'),
	)),
	'kpis' => array(
		array('label' => 'Vehicles', 'value' => count($rows), 'format' => 'number', 'tone' => 'primary', 'icon' => 'fa-bus', 'note' => $totals['trips'] . ' trips in total'),
		array('label' => 'Trip profit', 'value' => $totals['net'], 'tone' => 'success', 'icon' => 'fa-chart-line', 'note' => 'Before office expenses, ' . number_format($totals['margin'], 1) . '% margin'),
		array('label' => 'Best vehicle', 'value' => $best['net'], 'tone' => 'success', 'icon' => 'fa-trophy', 'note' => $best['vehicle'] . ', ' . $best['trips'] . ' trips'),
		array('label' => 'Lowest vehicle', 'value' => $worst['net'], 'tone' => 'warning', 'icon' => 'fa-arrow-down', 'note' => $worst['vehicle'] . ', ' . $worst['trips'] . ' trips'),
	),
	'blocks' => array(
		array(
			'type' => 'table',
			'title' => 'By vehicle',
			'columns' => array(
				array('key' => 'vehicle', 'label' => 'Vehicle'),
				array('key' => 'trips', 'label' => 'Trips', 'format' => 'number'),
				array('key' => 'revenue', 'label' => 'Revenue', 'format' => 'money'),
				array('key' => 'expenses', 'label' => 'Costs', 'format' => 'money'),
				array('key' => 'net', 'label' => 'Net', 'format' => 'money'),
				array('key' => 'per_trip', 'label' => 'Net per trip', 'format' => 'money'),
				array('key' => 'cost_per_trip', 'label' => 'Cost per trip', 'format' => 'money'),
				array('key' => 'margin', 'label' => 'Margin', 'format' => 'percent'),
				array('key' => 'share', 'label' => 'Share of profit', 'format' => 'share'),
			),
			'rows' => $rows,
			'footer' => $totals,
		),
	),
);

include 'includes/report_page.php';
