<?php
/** C-P1-09 Multiple user charges. */
declare(strict_types=1);
$charge = [
	'kind' => 'multi',
	'root' => 'tds-user-multi-root',
	'slice' => 'C-P1-09',
	'title' => 'Multiple user charges',
	'lead' => 'Daily charge totals for every seller on your routes.',
	'crumb' => 'Charges',
	'classic' => 'total_charge_summary.php',
];
require __DIR__ . '/_charge_page.php';
