<?php require_once __DIR__ . '/includes/auth_bootstrap.php'; ?>
<?php
// Cash Flow Statement and daily cash book. SAMPLE DATA: see includes/report_sample_data.php.
include 'includes/report_sample_data.php';

$month = rp_live_month();
$days_in_month = (int) date('t', strtotime($month . '-01'));
$opening_rows = rp_live_rows("SELECT opening_stock FROM account.cash WHERE date < '" . $month . "-01' ORDER BY date DESC LIMIT 1");
$opening = $opening_rows ? (float) $opening_rows[0]['opening_stock'] : 0;
$trips = rp_sample_trips(2609, 1.0, $month);
$overheads_total = array_sum(rp_sample_overheads());
$withdrawals = rp_sample_withdrawals();

$days = array();
for ($day = 1; $day <= $days_in_month; $day++) {
	$date = sprintf('%s-%02d', $month, $day);
	$days[$date] = array('date' => $date, 'trip_in' => 0, 'trip_out' => 0, 'office_out' => 0, 'withdrawn' => 0);
}
foreach ($trips as $trip) {
	if (!isset($days[$trip['date']])) {
		continue;
	}
	$days[$trip['date']]['trip_in'] += rp_trip_revenue($trip);
	$days[$trip['date']]['trip_out'] += $trip['expenses_total'] - $trip['unpaid'];
}
if ($overheads_total != 0 && isset($days[$month . '-01'])) {
	$days[$month . '-01']['office_out'] = $overheads_total;
}
foreach ($withdrawals as $withdrawal) {
	$days[$withdrawal['date']]['withdrawn'] += $withdrawal['amount'];
}

$rows = array();
$balance = $opening;
foreach ($days as $row) {
	$in = $row['trip_in'];
	$out = $row['trip_out'] + $row['office_out'] + $row['withdrawn'];
	$balance += $in - $out;
	$rows[] = array(
		'date' => date('D, j M', strtotime($row['date'])),
		'cash_in' => $in,
		'trip_costs' => $row['trip_out'],
		'office' => $row['office_out'],
		'withdrawn' => $row['withdrawn'],
		'net' => $in - $out,
		'balance' => $balance,
	);
}
$totals = rp_sum($rows, array('cash_in', 'trip_costs', 'office', 'withdrawn', 'net'));
$totals['balance'] = $balance;
$cash_out = $totals['trip_costs'] + $totals['office'] + $totals['withdrawn'];

$ticket = $cargo_paid = $cargo_to_pay = 0;
foreach ($trips as $trip) {
	$ticket += $trip['ticket'];
	$cargo_paid += $trip['cargo_paid'];
	$cargo_to_pay += $trip['cargo_to_pay'];
}

$report = array(
	'group' => 'Financial Reports',
	'title' => 'Cash Flow Statement',
	'description' => 'Cash received and paid out, with the running cash balance for each day.',
	'filters' => rp_period_filters(array(
		array('type' => 'select', 'id' => 'currency', 'label' => 'Currency', 'options' => rp_currency_options()),
	)),
	'kpis' => array(
		array('label' => 'Opening balance', 'value' => $opening, 'tone' => 'primary', 'icon' => 'fa-wallet', 'note' => '1 Sep 2026'),
		array('label' => 'Cash in', 'value' => $totals['cash_in'], 'tone' => 'success', 'icon' => 'fa-arrow-down', 'note' => 'Tickets and cargo'),
		array('label' => 'Cash out', 'value' => $cash_out, 'tone' => 'danger', 'icon' => 'fa-arrow-up', 'note' => 'Costs, office, withdrawals'),
		array('label' => 'Closing balance', 'value' => $balance, 'tone' => $balance >= 0 ? 'success' : 'danger', 'icon' => 'fa-piggy-bank', 'note' => '30 Sep 2026'),
	),
	'blocks' => array(
		array('type' => 'statement', 'title' => 'Summary', 'width' => 'half', 'rows' => array(
			array('subtotal', 'Opening cash balance', $opening),
			array('heading', 'Cash received'),
			array('line', 'Ticket sales', $ticket),
			array('line', 'Cargo - prepaid', $cargo_paid),
			array('line', 'Cargo - collected on delivery', $cargo_to_pay),
			array('subtotal', 'Total cash received', $totals['cash_in']),
			array('heading', 'Cash paid out'),
			array('line', 'Trip costs paid', $totals['trip_costs']),
			array('line', 'Office and daily expenses', $totals['office']),
			array('line', 'Cash withdrawals', $totals['withdrawn']),
			array('subtotal', 'Total cash paid out', $cash_out),
			array('subtotal', 'Net cash flow', $totals['net']),
			array('total', 'Closing cash balance', $balance),
		)),
		array(
			'type' => 'table',
			'title' => 'Withdrawals',
			'width' => 'half',
			'columns' => array(
				array('key' => 'date', 'label' => 'Date'),
				array('key' => 'amount', 'label' => 'Amount', 'format' => 'money'),
			),
			'rows' => array_map(function ($w) {
				return array('date' => date('D, j M Y', strtotime($w['date'])), 'amount' => $w['amount']);
			}, $withdrawals),
			'footer' => rp_sum($withdrawals, array('amount')),
		),
		array(
			'type' => 'table',
			'title' => 'Daily cash book',
			'columns' => array(
				array('key' => 'date', 'label' => 'Date'),
				array('key' => 'cash_in', 'label' => 'Cash in', 'format' => 'money'),
				array('key' => 'trip_costs', 'label' => 'Trip costs', 'format' => 'money'),
				array('key' => 'office', 'label' => 'Office', 'format' => 'money'),
				array('key' => 'withdrawn', 'label' => 'Withdrawals', 'format' => 'money'),
				array('key' => 'net', 'label' => 'Net', 'format' => 'money'),
				array('key' => 'balance', 'label' => 'Balance', 'format' => 'money'),
			),
			'rows' => $rows,
			'footer' => $totals,
		),
	),
);

include 'includes/report_page.php';
