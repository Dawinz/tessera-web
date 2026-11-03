<?php require_once __DIR__ . '/includes/auth_bootstrap.php'; ?>
<?php
// Purchases by Vehicle: maintenance spend per vehicle. SAMPLE DATA: see includes/report_sample_data.php.
include 'includes/report_sample_data.php';

$purchases = rp_sample_purchases();
$trips_per_vehicle = array();
foreach (rp_sample_trips() as $trip) {
	$trips_per_vehicle[$trip['vehicle']] = (isset($trips_per_vehicle[$trip['vehicle']]) ? $trips_per_vehicle[$trip['vehicle']] : 0) + 1;
}

$by_vehicle = array();
foreach ($purchases as $purchase) {
	$vehicle = $purchase['vehicle'];
	if (!isset($by_vehicle[$vehicle])) {
		$by_vehicle[$vehicle] = array('vehicle' => $vehicle, 'invoices' => 0, 'amount' => 0, 'balance' => 0, 'items' => array());
	}
	$by_vehicle[$vehicle]['invoices']++;
	$by_vehicle[$vehicle]['amount'] += $purchase['amount'];
	$by_vehicle[$vehicle]['balance'] += $purchase['balance'];
	foreach ($purchase['lines'] as $line) {
		$by_vehicle[$vehicle]['items'][$line['item']] = (isset($by_vehicle[$vehicle]['items'][$line['item']]) ? $by_vehicle[$vehicle]['items'][$line['item']] : 0) + $line['amount'];
	}
}
$total = array_sum(array_column($by_vehicle, 'amount'));

$rows = array();
foreach ($by_vehicle as $vehicle => $row) {
	arsort($row['items']);
	$trips = isset($trips_per_vehicle[$vehicle]) ? $trips_per_vehicle[$vehicle] : 0;
	$rows[] = array(
		'vehicle' => $vehicle,
		'invoices' => $row['invoices'],
		'trips' => $trips,
		'amount' => $row['amount'],
		'per_trip' => $trips ? $row['amount'] / $trips : 0,
		'balance' => $row['balance'],
		'top_item' => key($row['items']),
		'share' => rp_percent_of($row['amount'], $total),
	);
}
usort($rows, function ($a, $b) {
	return $b['amount'] <=> $a['amount'];
});
$totals = rp_sum($rows, array('invoices', 'trips', 'amount', 'balance'));
$totals['per_trip'] = $totals['trips'] ? $totals['amount'] / $totals['trips'] : 0;

$report = array(
	'group' => 'Purchases',
	'title' => 'Purchases by Vehicle',
	'description' => 'What each vehicle cost in parts and workshop work, and the maintenance cost per trip.',
	'filters' => rp_period_filters(array(
		array('type' => 'select', 'id' => 'supplier', 'label' => 'Supplier', 'options' => rp_supplier_options()),
		array('type' => 'search', 'id' => 'search', 'label' => 'Search', 'placeholder' => 'Vehicle'),
	)),
	'kpis' => array(
		array('label' => 'Total purchases', 'value' => $totals['amount'], 'tone' => 'primary', 'icon' => 'fa-shopping-cart', 'note' => count($rows) . ' vehicles'),
		array('label' => 'Highest spend', 'value' => $rows[0]['amount'], 'tone' => 'danger', 'icon' => 'fa-bus', 'note' => $rows[0]['vehicle'] . ', ' . $rows[0]['invoices'] . ' invoices'),
		array('label' => 'Maintenance per trip', 'value' => $totals['per_trip'], 'tone' => 'warning', 'icon' => 'fa-tools', 'note' => 'Across ' . $totals['trips'] . ' trips'),
	),
	'blocks' => array(
		array(
			'type' => 'table',
			'title' => 'By vehicle',
			'note' => 'Highest spend first',
			'columns' => array(
				array('key' => 'vehicle', 'label' => 'Vehicle'),
				array('key' => 'invoices', 'label' => 'Invoices', 'format' => 'number'),
				array('key' => 'trips', 'label' => 'Trips', 'format' => 'number'),
				array('key' => 'amount', 'label' => 'Purchases', 'format' => 'money'),
				array('key' => 'per_trip', 'label' => 'Per trip', 'format' => 'money'),
				array('key' => 'balance', 'label' => 'Unpaid', 'format' => 'money'),
				array('key' => 'top_item', 'label' => 'Biggest item'),
				array('key' => 'share', 'label' => 'Share of purchases', 'format' => 'share'),
			),
			'rows' => $rows,
			'footer' => $totals,
		),
	),
);

include 'includes/report_page.php';
