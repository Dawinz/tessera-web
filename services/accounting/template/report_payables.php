<?php require_once __DIR__ . '/includes/auth_bootstrap.php'; ?>
<?php
// Outstanding Payables ("To be Paid" entries) with aging. SAMPLE DATA: see includes/report_sample_data.php.
include 'includes/report_sample_data.php';

$as_of = rp_live_month() . '-' . date('t', strtotime(rp_live_month() . '-01'));
$trips = array_merge(
	array_slice(rp_sample_trips(2607, 0.9, '2026-07'), 0, 12),
	rp_sample_trips(2608, 0.93, '2026-08'),
	rp_sample_trips()
);

$buckets = array(
	'0 - 30 days' => array('max' => 30, 'tone' => 'primary'),
	'31 - 60 days' => array('max' => 60, 'tone' => 'warning'),
	'61 - 90 days' => array('max' => 90, 'tone' => 'danger'),
	'Over 90 days' => array('max' => PHP_INT_MAX, 'tone' => 'danger'),
);

$rows = array();
$aging = array();
foreach ($buckets as $name => $bucket) {
	$aging[$name] = array('bucket' => $name, 'items' => 0, 'amount' => 0);
}
foreach ($trips as $trip) {
	if ($trip['unpaid'] <= 0) {
		continue;
	}
	$days = (int)((strtotime($as_of) - strtotime($trip['date'])) / 86400);
	foreach ($buckets as $name => $bucket) {
		if ($days <= $bucket['max']) {
			break;
		}
	}
	$aging[$name]['items']++;
	$aging[$name]['amount'] += $trip['unpaid'];
	$rows[] = array(
		'code' => $trip['code'],
		'date' => date('j M Y', strtotime($trip['date'])),
		'vehicle' => $trip['vehicle'],
		'route' => $trip['route'],
		'category' => 'Border & permits',
		'amount' => $trip['unpaid'],
		'days' => $days,
		'bucket' => array($name, $bucket['tone']),
	);
}
usort($rows, function ($a, $b) {
	return $b['days'] <=> $a['days'];
});
$total = array_sum(array_column($rows, 'amount'));
foreach ($aging as $name => $row) {
	$aging[$name]['share'] = rp_percent_of($row['amount'], $total);
}
$overdue = $total - $aging['0 - 30 days']['amount'];

$report = array(
	'group' => 'Financial Reports',
	'title' => 'Outstanding Payables',
	'description' => 'Trip costs recorded as "To be Paid" that are still owed, grouped by how long they have been outstanding.',
	'filters' => array(
		array('type' => 'date', 'id' => 'as_of', 'label' => 'As of', 'value' => $as_of),
		array('type' => 'select', 'id' => 'route', 'label' => 'Route', 'options' => rp_route_options()),
		array('type' => 'select', 'id' => 'vehicle', 'label' => 'Vehicle', 'options' => rp_vehicle_options()),
		array('type' => 'search', 'id' => 'search', 'label' => 'Search', 'placeholder' => 'Trip code or vehicle'),
	),
	'kpis' => array(
		array('label' => 'Total outstanding', 'value' => $total, 'tone' => 'danger', 'icon' => 'fa-file-invoice-dollar', 'note' => count($rows) . ' unpaid items'),
		array('label' => 'Overdue (over 30 days)', 'value' => $overdue, 'tone' => 'warning', 'icon' => 'fa-clock', 'note' => number_format(rp_percent_of($overdue, $total), 1) . '% of outstanding'),
		array('label' => 'Oldest item', 'value' => $rows ? $rows[0]['days'] : 0, 'format' => 'number', 'tone' => 'danger', 'icon' => 'fa-hourglass-half', 'note' => 'days outstanding'),
		array('label' => 'Due this month', 'value' => $aging['0 - 30 days']['amount'], 'tone' => 'primary', 'icon' => 'fa-calendar-check', 'note' => $aging['0 - 30 days']['items'] . ' items'),
	),
	'blocks' => array(
		array(
			'type' => 'table',
			'title' => 'Aging summary',
			'columns' => array(
				array('key' => 'bucket', 'label' => 'Outstanding for'),
				array('key' => 'items', 'label' => 'Items', 'format' => 'number'),
				array('key' => 'amount', 'label' => 'Amount', 'format' => 'money'),
				array('key' => 'share', 'label' => 'Share', 'format' => 'share'),
			),
			'rows' => $aging,
			'footer' => array('items' => count($rows), 'amount' => $total),
		),
		array(
			'type' => 'table',
			'title' => 'Unpaid items',
			'note' => 'Oldest first',
			'columns' => array(
				array('key' => 'code', 'label' => 'Trip'),
				array('key' => 'date', 'label' => 'Trip date'),
				array('key' => 'vehicle', 'label' => 'Vehicle'),
				array('key' => 'route', 'label' => 'Route'),
				array('key' => 'category', 'label' => 'Category'),
				array('key' => 'amount', 'label' => 'Amount', 'format' => 'money'),
				array('key' => 'days', 'label' => 'Days', 'format' => 'number'),
				array('key' => 'bucket', 'label' => 'Aging', 'format' => 'badge'),
			),
			'rows' => $rows,
			'footer' => array('amount' => $total),
		),
	),
);

include 'includes/report_page.php';
