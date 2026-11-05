<?php
/**
 * T-P3-03 Tickets luggage list merges into Cargo.
 */
declare(strict_types=1);
$hub = [
	'id' => 'tds-tk-luggage-move',
	'slice' => 'T-P3-03',
	'title' => 'Luggages',
	'crumb' => 'Tickets',
	'section' => 'Cargo',
	'lead' => 'Luggage is registered and listed in Cargo.',
	'paragraphs' => ['The Tickets copy of the luggage list now opens Cargo. Search, print, and check out from there.'],
	'base' => '/tickets/',
	'links' => [
		['href' => '/operator/app.php?module=cargo&page=all_luggages.php', 'label' => 'Open Cargo luggages', 'primary' => true, 'id' => 'tds-tk-luggage-go'],
	],
];
require dirname(__DIR__) . '/_hub.php';
