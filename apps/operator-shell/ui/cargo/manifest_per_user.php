<?php
/** C-P1-06 Manifest charges per user. */
declare(strict_types=1);
$charge = [
	'kind' => 'manifest-user',
	'root' => 'tds-manifest-user-root',
	'slice' => 'C-P1-06',
	'title' => 'Manifest charges per user',
	'lead' => 'Parcels one seller loaded on a vehicle for a trip date.',
	'crumb' => 'Charges',
	'classic' => 'manifest_per_user.php',
];
require __DIR__ . '/_charge_page.php';
