<?php require_once __DIR__ . '/includes/auth_bootstrap.php'; ?>
<?php
// Purchases by Supplier: spend and amounts owed per supplier. SAMPLE DATA: see includes/report_sample_data.php.
include 'includes/report_sample_data.php';

$purchases = rp_sample_purchases();

$by_supplier = array();
foreach ($purchases as $purchase) {
	$supplier = $purchase['supplier'];
	if (!isset($by_supplier[$supplier])) {
		$by_supplier[$supplier] = array('supplier' => $supplier, 'invoices' => 0, 'amount' => 0, 'paid' => 0, 'balance' => 0, 'last' => '');
	}
	$by_supplier[$supplier]['invoices']++;
	$by_supplier[$supplier]['amount'] += $purchase['amount'];
	$by_supplier[$supplier]['paid'] += $purchase['paid'];
	$by_supplier[$supplier]['balance'] += $purchase['balance'];
	$by_supplier[$supplier]['last'] = max($by_supplier[$supplier]['last'], $purchase['date']);
}
$total = array_sum(array_column($by_supplier, 'amount'));

$rows = array();
foreach ($by_supplier as $row) {
	$row['last'] = date('j M Y', strtotime($row['last']));
	$row['share'] = rp_percent_of($row['amount'], $total);
	$row['status'] = $row['balance'] > 0 ? array('Owed', 'danger') : array('Settled', 'success');
	$rows[] = $row;
}
usort($rows, function ($a, $b) {
	return $b['amount'] <=> $a['amount'];
});
$totals = rp_sum($rows, array('invoices', 'amount', 'paid', 'balance'));
$owed = array_filter($rows, function ($row) {
	return $row['balance'] > 0;
});

$report = array(
	'group' => 'Purchases',
	'title' => 'Purchases by Supplier',
	'description' => 'How much was bought from each supplier and how much is still owed to them.',
	'filters' => rp_period_filters(array(
		array('type' => 'select', 'id' => 'vehicle', 'label' => 'Vehicle', 'options' => rp_vehicle_options()),
	)),
	'kpis' => array(
		array('label' => 'Suppliers', 'value' => count($rows), 'format' => 'number', 'tone' => 'primary', 'icon' => 'fa-store', 'note' => $totals['invoices'] . ' invoices'),
		array('label' => 'Total purchases', 'value' => $totals['amount'], 'tone' => 'primary', 'icon' => 'fa-shopping-cart', 'note' => 'All suppliers'),
		array('label' => 'Owed to suppliers', 'value' => $totals['balance'], 'tone' => 'danger', 'icon' => 'fa-file-invoice-dollar', 'note' => count($owed) . ' suppliers with a balance'),
		array('label' => 'Largest supplier', 'value' => $rows[0]['share'], 'format' => 'percent', 'tone' => 'warning', 'icon' => 'fa-trophy', 'note' => $rows[0]['supplier'] . ' share'),
	),
	'blocks' => array(
		array(
			'type' => 'table',
			'title' => 'By supplier',
			'columns' => array(
				array('key' => 'supplier', 'label' => 'Supplier'),
				array('key' => 'invoices', 'label' => 'Invoices', 'format' => 'number'),
				array('key' => 'amount', 'label' => 'Purchases', 'format' => 'money'),
				array('key' => 'paid', 'label' => 'Paid', 'format' => 'money'),
				array('key' => 'balance', 'label' => 'Owed', 'format' => 'money'),
				array('key' => 'last', 'label' => 'Last invoice'),
				array('key' => 'share', 'label' => 'Share of purchases', 'format' => 'share'),
				array('key' => 'status', 'label' => 'Status', 'format' => 'badge'),
			),
			'rows' => $rows,
			'footer' => $totals,
		),
	),
);

include 'includes/report_page.php';
