<?php require_once __DIR__ . '/includes/auth_bootstrap.php'; ?>
<?php
// Purchases by Item: spare parts and services bought. SAMPLE DATA: see includes/report_sample_data.php.
include 'includes/report_sample_data.php';

$purchases = rp_sample_purchases();

$by_item = array();
foreach ($purchases as $purchase) {
	foreach ($purchase['lines'] as $line) {
		$item = $line['item'];
		if (!isset($by_item[$item])) {
			$by_item[$item] = array('item' => $item, 'qty' => 0, 'amount' => 0, 'vehicles' => array(), 'last' => '', 'low' => PHP_INT_MAX, 'high' => 0);
		}
		$by_item[$item]['qty'] += $line['qty'];
		$by_item[$item]['amount'] += $line['amount'];
		$by_item[$item]['vehicles'][$purchase['vehicle']] = true;
		$by_item[$item]['last'] = max($by_item[$item]['last'], $purchase['date']);
		$by_item[$item]['low'] = min($by_item[$item]['low'], $line['price']);
		$by_item[$item]['high'] = max($by_item[$item]['high'], $line['price']);
	}
}
$total = array_sum(array_column($by_item, 'amount'));

$rows = array();
foreach ($by_item as $row) {
	$rows[] = array(
		'item' => $row['item'],
		'qty' => $row['qty'],
		'avg_price' => $row['amount'] / $row['qty'],
		'low' => $row['low'],
		'high' => $row['high'],
		'amount' => $row['amount'],
		'vehicles' => count($row['vehicles']),
		'last' => date('j M Y', strtotime($row['last'])),
		'share' => rp_percent_of($row['amount'], $total),
	);
}
usort($rows, function ($a, $b) {
	return $b['amount'] <=> $a['amount'];
});
$totals = rp_sum($rows, array('qty', 'amount'));
$tyres = 0;
foreach ($rows as $row) {
	if (strpos($row['item'], 'Tyre') === 0) {
		$tyres += $row['qty'];
	}
}

$report = array(
	'group' => 'Purchases',
	'title' => 'Purchases by Item',
	'description' => 'Spare parts, consumables and workshop services bought, with the price range paid for each.',
	'filters' => rp_period_filters(array(
		array('type' => 'select', 'id' => 'vehicle', 'label' => 'Vehicle', 'options' => rp_vehicle_options()),
		array('type' => 'select', 'id' => 'supplier', 'label' => 'Supplier', 'options' => rp_supplier_options()),
		array('type' => 'search', 'id' => 'search', 'label' => 'Search', 'placeholder' => 'Item'),
	)),
	'kpis' => array(
		array('label' => 'Items bought', 'value' => count($rows), 'format' => 'number', 'tone' => 'primary', 'icon' => 'fa-boxes', 'note' => number_format($totals['qty']) . ' units in total'),
		array('label' => 'Total spend', 'value' => $totals['amount'], 'tone' => 'primary', 'icon' => 'fa-shopping-cart', 'note' => 'Parts and services'),
		array('label' => 'Biggest item', 'value' => $rows[0]['share'], 'format' => 'percent', 'tone' => 'warning', 'icon' => 'fa-chart-pie', 'note' => $rows[0]['item'] . ' share of spend'),
		array('label' => 'Tyres fitted', 'value' => $tyres, 'format' => 'number', 'tone' => 'success', 'icon' => 'fa-circle-notch', 'note' => 'Units this period'),
	),
	'blocks' => array(
		array(
			'type' => 'table',
			'title' => 'By item',
			'note' => 'Highest spend first',
			'columns' => array(
				array('key' => 'item', 'label' => 'Item'),
				array('key' => 'qty', 'label' => 'Qty', 'format' => 'number'),
				array('key' => 'avg_price', 'label' => 'Average price', 'format' => 'money'),
				array('key' => 'low', 'label' => 'Lowest price', 'format' => 'money'),
				array('key' => 'high', 'label' => 'Highest price', 'format' => 'money'),
				array('key' => 'amount', 'label' => 'Total', 'format' => 'money'),
				array('key' => 'vehicles', 'label' => 'Vehicles', 'format' => 'number'),
				array('key' => 'last', 'label' => 'Last bought'),
				array('key' => 'share', 'label' => 'Share of spend', 'format' => 'share'),
			),
			'rows' => $rows,
			'footer' => $totals,
		),
	),
);

include 'includes/report_page.php';
