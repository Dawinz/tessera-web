<?php
/** C-P1-08 User charge summary. */
declare(strict_types=1);
$charge = [
	'kind' => 'summary',
	'root' => 'tds-user-summary-root',
	'slice' => 'C-P1-08',
	'title' => 'User charge summary',
	'lead' => 'Daily paid and to-be-paid totals for one seller.',
	'crumb' => 'Charges',
	'classic' => 'summary_store_charge.php',
];
require __DIR__ . '/_charge_page.php';
