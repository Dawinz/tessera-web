<?php require_once __DIR__ . '/includes/auth_bootstrap.php'; ?>
<?php
// Expense Analysis by category. SAMPLE DATA: see includes/report_sample_data.php.
include 'includes/report_sample_data.php';

function rp_costs_by_category($trips) {
	$costs = array();
	foreach (rp_sample_expense_categories() as $category => $share) {
		$costs[$category] = 0;
	}
	foreach ($trips as $trip) {
		foreach ($trip['expenses'] as $category => $amount) {
			$costs[$category] += $amount;
		}
	}
	return $costs;
}

$trips = rp_sample_trips();
$now = rp_costs_by_category($trips);
$before = rp_costs_by_category(rp_sample_trips(2608, 0.93, '2026-08'));
$total_now = array_sum($now);
$total_before = array_sum($before);

$rows = array();
foreach ($now as $category => $amount) {
	$rows[] = array(
		'category' => $category,
		'now' => $amount,
		'before' => $before[$category],
		'change' => $amount - $before[$category],
		'change_pct' => rp_percent_of($amount - $before[$category], $before[$category]),
		'per_trip' => $amount / count($trips),
		'share' => rp_percent_of($amount, $total_now),
	);
}
usort($rows, function ($a, $b) {
	return $b['now'] <=> $a['now'];
});

$overheads = array();
$overheads_before = rp_sample_overheads(0.97);
foreach (rp_sample_overheads() as $name => $amount) {
	$overheads[] = array('name' => $name, 'now' => $amount, 'before' => $overheads_before[$name], 'change' => $amount - $overheads_before[$name]);
}

$report = array(
	'group' => 'Financial Reports',
	'title' => 'Expense Analysis',
	'description' => 'Trip costs by category against the previous period, and office expenses.',
	'filters' => rp_period_filters(array(
		array('type' => 'select', 'id' => 'route', 'label' => 'Route', 'options' => rp_route_options()),
		array('type' => 'select', 'id' => 'vehicle', 'label' => 'Vehicle', 'options' => rp_vehicle_options()),
	)),
	'kpis' => array(
		array('label' => 'Trip costs', 'value' => $total_now, 'tone' => 'danger', 'icon' => 'fa-gas-pump', 'note' => number_format(rp_percent_of($total_now - $total_before, $total_before), 1) . '% vs Aug 2026'),
		array('label' => 'Office expenses', 'value' => array_sum(rp_sample_overheads()), 'tone' => 'warning', 'icon' => 'fa-building', 'note' => 'Daily and office costs'),
		array('label' => 'Cost per trip', 'value' => $total_now / count($trips), 'tone' => 'primary', 'icon' => 'fa-bus', 'note' => count($trips) . ' trips'),
		array('label' => 'Largest cost', 'value' => $rows[0]['share'], 'format' => 'percent', 'tone' => 'danger', 'icon' => 'fa-chart-pie', 'note' => $rows[0]['category'] . ' share of trip costs'),
	),
	'blocks' => array(
		array(
			'type' => 'table',
			'title' => 'Trip costs by category',
			'note' => 'Sep 2026 against Aug 2026',
			'columns' => array(
				array('key' => 'category', 'label' => 'Category'),
				array('key' => 'now', 'label' => 'Sep 2026', 'format' => 'money'),
				array('key' => 'before', 'label' => 'Aug 2026', 'format' => 'money'),
				array('key' => 'change', 'label' => 'Change', 'format' => 'money'),
				array('key' => 'change_pct', 'label' => 'Change %', 'format' => 'percent'),
				array('key' => 'per_trip', 'label' => 'Per trip', 'format' => 'money'),
				array('key' => 'share', 'label' => 'Share of costs', 'format' => 'share'),
			),
			'rows' => $rows,
			'footer' => array(
				'now' => $total_now,
				'before' => $total_before,
				'change' => $total_now - $total_before,
				'change_pct' => rp_percent_of($total_now - $total_before, $total_before),
				'per_trip' => $total_now / count($trips),
			),
		),
		array(
			'type' => 'table',
			'title' => 'Office and daily expenses',
			'columns' => array(
				array('key' => 'name', 'label' => 'Expense'),
				array('key' => 'now', 'label' => 'Sep 2026', 'format' => 'money'),
				array('key' => 'before', 'label' => 'Aug 2026', 'format' => 'money'),
				array('key' => 'change', 'label' => 'Change', 'format' => 'money'),
			),
			'rows' => $overheads,
			'footer' => rp_sum($overheads, array('now', 'before', 'change')),
		),
	),
);

include 'includes/report_page.php';
