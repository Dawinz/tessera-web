<?php
/** C-P1-10 Charges — signed-in seller’s manifest lines. */
declare(strict_types=1);
$charge = [
	'kind' => 'manifest-mine',
	'root' => 'tds-manifest-charges-root',
	'slice' => 'C-P1-10',
	'title' => 'Charges',
	'lead' => 'Manifest lines you loaded, filtered by vehicle and trip dates.',
	'crumb' => 'Charges',
	'classic' => 'charges.php',
];
require __DIR__ . '/_charge_page.php';
