<?php
/** C-P1-07 User charge (detailed). */
declare(strict_types=1);
$charge = [
	'kind' => 'detail',
	'root' => 'tds-user-charge-root',
	'slice' => 'C-P1-07',
	'title' => 'User charge',
	'lead' => 'Every parcel a seller registered in the date range, with paid and to-be-paid amounts.',
	'crumb' => 'Charges',
	'classic' => 'store_charge.php',
];
require __DIR__ . '/_charge_page.php';
