<?php require_once __DIR__ . '/includes/auth_bootstrap.php'; ?>
<?php
// Currency Position. SAMPLE DATA: see includes/report_sample_data.php.
include 'includes/report_sample_data.php';

$currencies = rp_sample_currencies();
$trips = rp_sample_trips();

// Which currency each route pays its border/toll costs and collects
// pay-on-delivery cargo in (sample assumption).
$route_currency = array(
	'Dar es salaam to Lubumbashi' => 'ZMW',
	'Dar es salaam to Harare' => 'USD',
	'Dar es salaam to Kampala' => 'USD',
	'Nairobi to Dar es salaam' => 'KES',
	'Dar es salaam to Johannesburg' => 'ZAR',
);

$position = array();
foreach ($currencies as $code => $currency) {
	$position[$code] = array('code' => $code, 'name' => $currency['name'], 'entries' => 0, 'income' => 0, 'expenses' => 0, 'rate' => $currency['rate']);
}
foreach ($trips as $trip) {
	$foreign = $route_currency[$trip['route']];
	$foreign_costs = $trip['expenses']['Border & permits'] + $trip['expenses']['Tolls & road charges'];

	// TSh amounts are converted to the original currency at the sample rate.
	$position['TSh']['income'] += $trip['ticket'] + $trip['cargo_paid'];
	$position['TSh']['expenses'] += $trip['expenses_total'] - $foreign_costs;
	$position['TSh']['entries'] += 8;
	$position[$foreign]['income'] += $trip['cargo_to_pay'] / $currencies[$foreign]['rate'];
	$position[$foreign]['expenses'] += $foreign_costs / $currencies[$foreign]['rate'];
	$position[$foreign]['entries'] += 3;
}

$rows = array();
foreach ($position as $code => $row) {
	$net = $row['income'] - $row['expenses'];
	$rows[] = array(
		'code' => $code,
		'name' => $row['name'],
		'entries' => $row['entries'],
		'income' => round($row['income']),
		'expenses' => round($row['expenses']),
		'net' => round($net),
		'rate' => $row['rate'],
		'net_tsh' => round($net * $row['rate']),
	);
}
$total_tsh = array_sum(array_column($rows, 'net_tsh'));
foreach ($rows as $i => $row) {
	$rows[$i]['rate_text'] = $row['rate'] == 1 ? 'Base' : '1 ' . $row['code'] . ' = ' . number_format($row['rate'], 2) . ' TSh';
}

$report = array(
	'group' => 'Financial Reports',
	'title' => 'Currency Position',
	'description' => 'Income and expenses in each currency the trips use, converted to Tanzanian Shillings at the exchange rates set under Currency.',
	'filters' => rp_period_filters(array(
		array('type' => 'select', 'id' => 'route', 'label' => 'Route', 'options' => rp_route_options()),
	)),
	'kpis' => array(
		array('label' => 'Net position (TSh)', 'value' => $total_tsh, 'tone' => $total_tsh >= 0 ? 'success' : 'danger', 'icon' => 'fa-balance-scale', 'note' => 'All currencies converted'),
		array('label' => 'Currencies in use', 'value' => count($rows), 'format' => 'number', 'tone' => 'primary', 'icon' => 'fa-money-bill-wave', 'note' => 'TSh, USD, ZAR, ZMW, KES'),
		array('label' => 'USD rate', 'value' => $currencies['USD']['rate'], 'format' => 'number', 'tone' => 'warning', 'icon' => 'fa-dollar-sign', 'note' => 'TSh per 1 USD'),
	),
	'blocks' => array(
		array(
			'type' => 'table',
			'title' => 'Position by currency',
			'note' => 'Amounts in each currency\'s own units',
			'columns' => array(
				array('key' => 'code', 'label' => 'Currency'),
				array('key' => 'name', 'label' => 'Name'),
				array('key' => 'entries', 'label' => 'Entries', 'format' => 'number'),
				array('key' => 'income', 'label' => 'Income', 'format' => 'number'),
				array('key' => 'expenses', 'label' => 'Expenses', 'format' => 'number'),
				array('key' => 'net', 'label' => 'Net', 'format' => 'number'),
				array('key' => 'rate_text', 'label' => 'Exchange rate'),
				array('key' => 'net_tsh', 'label' => 'Net in TSh', 'format' => 'money'),
			),
			'rows' => $rows,
			'footer' => array('_label' => 'Total in TSh', 'net_tsh' => $total_tsh),
		),
	),
);

include 'includes/report_page.php';
