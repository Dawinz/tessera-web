<?php require_once __DIR__ . '/includes/auth_bootstrap.php'; ?>
<?php
// Purchase History: every purchase invoice (job card). SAMPLE DATA: see includes/report_sample_data.php.
include 'includes/report_sample_data.php';

$purchases = rp_sample_purchases();

$rows = array();
foreach ($purchases as $purchase) {
	$first = $purchase['lines'][0]['item'];
	$more = count($purchase['lines']) - 1;
	$rows[] = array(
		'invoice' => $purchase['invoice'],
		'date' => date('j M Y', strtotime($purchase['date'])),
		'vehicle' => $purchase['vehicle'],
		'supplier' => $purchase['supplier'],
		'items' => $first . ($more > 0 ? ' + ' . $more . ' more' : ''),
		'amount' => $purchase['amount'],
		'paid' => $purchase['paid'],
		'balance' => $purchase['balance'],
		'status' => rp_purchase_status($purchase),
	);
}
$rows = array_reverse($rows);
$totals = rp_sum($rows, array('amount', 'paid', 'balance'));
$unpaid = count(array_filter($rows, function ($row) {
	return $row['balance'] > 0;
}));

$report = array(
	'group' => 'Purchases',
	'title' => 'Purchase History',
	'description' => 'Every purchase invoice (job card) for spare parts, tyres, oil and workshop work, newest first.',
	'filters' => rp_period_filters(array(
		array('type' => 'select', 'id' => 'vehicle', 'label' => 'Vehicle', 'options' => rp_vehicle_options()),
		array('type' => 'select', 'id' => 'supplier', 'label' => 'Supplier', 'options' => rp_supplier_options()),
		array('type' => 'select', 'id' => 'status', 'label' => 'Payment', 'options' => array('' => 'All invoices', 'paid' => 'Paid', 'part' => 'Part paid', 'unpaid' => 'Unpaid')),
		array('type' => 'search', 'id' => 'search', 'label' => 'Search', 'placeholder' => 'Invoice, vehicle or item'),
	)),
	'kpis' => array(
		array('label' => 'Total purchases', 'value' => $totals['amount'], 'tone' => 'primary', 'icon' => 'fa-shopping-cart', 'note' => count($rows) . ' invoices'),
		array('label' => 'Paid', 'value' => $totals['paid'], 'tone' => 'success', 'icon' => 'fa-check-circle', 'note' => number_format(rp_percent_of($totals['paid'], $totals['amount']), 1) . '% of purchases'),
		array('label' => 'Still owed to suppliers', 'value' => $totals['balance'], 'tone' => 'danger', 'icon' => 'fa-file-invoice-dollar', 'note' => $unpaid . ' invoices not fully paid'),
		array('label' => 'Average invoice', 'value' => $totals['amount'] / count($rows), 'tone' => 'warning', 'icon' => 'fa-receipt', 'note' => 'Per job card'),
	),
	'blocks' => array(
		array(
			'type' => 'table',
			'title' => 'Invoices',
			'note' => 'Newest first',
			'columns' => array(
				array('key' => 'invoice', 'label' => 'Invoice'),
				array('key' => 'date', 'label' => 'Date'),
				array('key' => 'vehicle', 'label' => 'Vehicle'),
				array('key' => 'supplier', 'label' => 'Supplier'),
				array('key' => 'items', 'label' => 'Items'),
				array('key' => 'amount', 'label' => 'Amount', 'format' => 'money'),
				array('key' => 'paid', 'label' => 'Paid', 'format' => 'money'),
				array('key' => 'balance', 'label' => 'Balance', 'format' => 'money'),
				array('key' => 'status', 'label' => 'Status', 'format' => 'badge'),
			),
			'rows' => $rows,
			'footer' => $totals,
		),
	),
);

include 'includes/report_page.php';
