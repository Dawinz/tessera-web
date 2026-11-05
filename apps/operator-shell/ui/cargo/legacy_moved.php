<?php
/**
 * Cargo money and messaging leftovers: moved to Accounts, Settings, or the manifest.
 * Loan, salary, and employee copies are retired (K). PHP files stay until Phase 10.
 */
declare(strict_types=1);

$screen = strtolower((string) ($GLOBALS['tessera_leftover_screen'] ?? ''));

$daily = '/operator/app.php?module=accounts&page=daily.php';
$expenses = '/operator/app.php?module=accounts&page=report_expenses.php';
$revenue = '/operator/app.php?module=accounts&page=report_revenue.php';
$reports = '/operator/app.php?module=accounts&page=report_profit_loss.php';
$payables = '/operator/app.php?module=accounts&page=report_payables.php';
$staff = '/settings/staff.php';
$manifest = '/operator/app.php?module=cargo&page=vehicle_view.php';
$notes = '/settings/notifications.php';

$groups = [
	'expense' => [
		'title' => 'Expenses',
		'lead' => 'Cargo expense pages moved to Accounts.',
		'body' => 'Record office spend on daily expenses, and read the expense report for the period.',
		'links' => [
			['href' => $daily, 'label' => 'Open daily expenses', 'primary' => true, 'id' => 'tds-cg-moved-go'],
			['href' => $expenses, 'label' => 'Expense report'],
		],
	],
	'income' => [
		'title' => 'Income',
		'lead' => 'Cargo income pages moved to Accounts.',
		'body' => 'Trip and office income are on the revenue report.',
		'links' => [
			['href' => $revenue, 'label' => 'Open revenue', 'primary' => true, 'id' => 'tds-cg-moved-go'],
		],
	],
	'reports' => [
		'title' => 'Accounts report',
		'lead' => 'This Cargo accounts report moved to Accounts.',
		'body' => 'Profit and loss, cash flow, and the other financial reports are in Accounts.',
		'links' => [
			['href' => $reports, 'label' => 'Open profit and loss', 'primary' => true, 'id' => 'tds-cg-moved-go'],
		],
	],
	'loan' => [
		'title' => 'Loans',
		'lead' => 'Cargo loan pages are retired.',
		'body' => 'They are not part of the daily desk. Amounts still to pay are on Accounts payables.',
		'links' => [
			['href' => $payables, 'label' => 'Open payables', 'primary' => true, 'id' => 'tds-cg-moved-go'],
		],
	],
	'people' => [
		'title' => 'People',
		'lead' => 'Cargo salary and employee pages are retired.',
		'body' => 'Staff accounts and menu access are in Settings. Payroll is not a Cargo screen.',
		'links' => [
			['href' => $staff, 'label' => 'Open staff', 'primary' => true, 'id' => 'tds-cg-moved-go'],
		],
	],
	'sms' => [
		'title' => 'Messages',
		'lead' => 'Send luggage SMS from the vehicle manifest.',
		'body' => 'Bulk SMS and WhatsApp helper pages are not a separate desk. Channel switches are in Settings notifications.',
		'links' => [
			['href' => $manifest, 'label' => 'Open manifest', 'primary' => true, 'id' => 'tds-cg-moved-go'],
			['href' => $notes, 'label' => 'Notification settings'],
		],
	],
];

$expenseScreens = ['expenses', 'expenses_view', 'expenses_report', 'expenses_category', 'fuel_expenses', 'manifest_exp'];
$reportScreens = ['accounts_report', 'individual_accounts', 'general_account'];
$loanScreens = ['loan', 'loan_report', 'loan_type', 'loan_insert', 'loan_insert2'];
$peopleScreens = ['salary', 'salaryslip', 'salary_show', 'employees', 'edit_employee', 'employee_report', 'deploy'];
$smsScreens = ['bulk_sms_com', 'whatsapp', 'whatsapp_sending', 'whatsappapi'];

$group = 'reports';
if (in_array($screen, $expenseScreens, true)) {
	$group = 'expense';
} elseif ($screen === 'income') {
	$group = 'income';
} elseif (in_array($screen, $loanScreens, true)) {
	$group = 'loan';
} elseif (in_array($screen, $peopleScreens, true)) {
	$group = 'people';
} elseif (in_array($screen, $smsScreens, true)) {
	$group = 'sms';
} elseif (in_array($screen, $reportScreens, true)) {
	$group = 'reports';
}

$spec = $groups[$group];
$hub = [
	'id' => 'tds-cg-moved',
	'slice' => 'C-P3-moved',
	'title' => $spec['title'],
	'crumb' => 'Cargo',
	'section' => 'Moved',
	'lead' => $spec['lead'],
	'paragraphs' => [$spec['body']],
	'base' => '/cargo/',
	'move' => $screen !== '' ? $screen : $group,
	'links' => $spec['links'],
];
require dirname(__DIR__) . '/_hub.php';
