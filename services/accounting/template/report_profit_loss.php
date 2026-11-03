<?php require_once __DIR__ . '/includes/auth_bootstrap.php'; ?>
<?php
// Profit & Loss Statement. SAMPLE DATA: see includes/report_sample_data.php.
include 'includes/report_sample_data.php';

function rp_pl_figures($trips, $overheads) {
	$f = array('ticket' => 0, 'cargo_paid' => 0, 'cargo_to_pay' => 0, 'costs' => array());
	foreach (rp_sample_expense_categories() as $category => $share) {
		$f['costs'][$category] = 0;
	}
	foreach ($trips as $trip) {
		$f['ticket'] += $trip['ticket'];
		$f['cargo_paid'] += $trip['cargo_paid'];
		$f['cargo_to_pay'] += $trip['cargo_to_pay'];
		foreach ($trip['expenses'] as $category => $amount) {
			$f['costs'][$category] += $amount;
		}
	}
	$f['revenue'] = $f['ticket'] + $f['cargo_paid'] + $f['cargo_to_pay'];
	$f['direct'] = array_sum($f['costs']);
	$f['gross'] = $f['revenue'] - $f['direct'];
	$f['overheads'] = $overheads;
	$f['overheads_total'] = array_sum($overheads);
	$f['net'] = $f['gross'] - $f['overheads_total'];
	return $f;
}

$now = rp_pl_figures(rp_sample_trips(), rp_sample_overheads());
$before = rp_pl_figures(rp_sample_trips(2608, 0.93, '2026-08'), rp_sample_overheads(0.97));

$statement = array(
	array('heading', 'Revenue'),
	array('line', 'Ticket sales', $now['ticket']),
	array('line', 'Cargo revenue - prepaid', $now['cargo_paid']),
	array('line', 'Cargo revenue - pay on delivery', $now['cargo_to_pay']),
	array('subtotal', 'Total revenue', $now['revenue']),
	array('heading', 'Direct trip costs'),
);
foreach ($now['costs'] as $category => $amount) {
	$statement[] = array('line', $category, $amount);
}
$statement[] = array('subtotal', 'Total direct trip costs', $now['direct']);
$statement[] = array('subtotal', 'Gross profit', $now['gross']);
$statement[] = array('heading', 'Operating expenses');
foreach ($now['overheads'] as $name => $amount) {
	$statement[] = array('line', $name, $amount);
}
$statement[] = array('subtotal', 'Total operating expenses', $now['overheads_total']);
$statement[] = array('total', 'Net profit', $now['net']);

$compare = array();
foreach (array(
	'revenue' => 'Total revenue',
	'direct' => 'Direct trip costs',
	'gross' => 'Gross profit',
	'overheads_total' => 'Operating expenses',
	'net' => 'Net profit',
) as $key => $label) {
	$compare[] = array(
		'line' => $label,
		'now' => $now[$key],
		'before' => $before[$key],
		'change' => $now[$key] - $before[$key],
		'change_pct' => rp_percent_of($now[$key] - $before[$key], $before[$key]),
	);
}

$report = array(
	'group' => 'Financial Reports',
	'title' => 'Profit & Loss Statement',
	'description' => 'Revenue from tickets and cargo, less trip costs and office expenses, for the selected period.',
	'filters' => rp_period_filters(array(
		array('type' => 'select', 'id' => 'route', 'label' => 'Route', 'options' => rp_route_options()),
		array('type' => 'select', 'id' => 'currency', 'label' => 'Report currency', 'options' => rp_currency_options()),
	)),
	'kpis' => array(
		array('label' => 'Total revenue', 'value' => $now['revenue'], 'tone' => 'primary', 'icon' => 'fa-coins', 'note' => 'Tickets and cargo'),
		array('label' => 'Direct trip costs', 'value' => $now['direct'], 'tone' => 'danger', 'icon' => 'fa-gas-pump', 'note' => number_format(rp_percent_of($now['direct'], $now['revenue']), 1) . '% of revenue'),
		array('label' => 'Gross profit', 'value' => $now['gross'], 'tone' => 'success', 'icon' => 'fa-chart-line', 'note' => number_format(rp_percent_of($now['gross'], $now['revenue']), 1) . '% gross margin'),
		array('label' => 'Net profit', 'value' => $now['net'], 'tone' => $now['net'] >= 0 ? 'success' : 'danger', 'icon' => 'fa-balance-scale', 'note' => number_format(rp_percent_of($now['net'], $now['revenue']), 1) . '% net margin'),
	),
	'blocks' => array(
		array('type' => 'statement', 'title' => 'Statement for 1 - 30 Sep 2026', 'width' => 'half', 'rows' => $statement),
		array(
			'type' => 'table',
			'title' => 'Compared with previous period',
			'note' => 'Aug 2026',
			'width' => 'half',
			'columns' => array(
				array('key' => 'line', 'label' => 'Line'),
				array('key' => 'now', 'label' => 'Sep 2026', 'format' => 'money'),
				array('key' => 'before', 'label' => 'Aug 2026', 'format' => 'money'),
				array('key' => 'change', 'label' => 'Change', 'format' => 'money'),
				array('key' => 'change_pct', 'label' => 'Change %', 'format' => 'percent'),
			),
			'rows' => $compare,
		),
	),
);

include 'includes/report_page.php';
