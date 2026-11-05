<?php
/**
 * T-P3-07 Tickets parcel helper merges into Cargo luggage.
 */
declare(strict_types=1);
$hub = [
	'id' => 'tds-tk-parcel-move',
	'slice' => 'T-P3-07',
	'title' => 'Parcel lookup',
	'crumb' => 'Tickets',
	'section' => 'Cargo',
	'lead' => 'Vehicle and parcel lookup now lives with Cargo luggage.',
	'paragraphs' => ['Find a code on All luggages, then open its status. This Tickets helper is no longer a separate desk.'],
	'base' => '/tickets/',
	'links' => [
		['href' => '/operator/app.php?module=cargo&page=all_luggages.php', 'label' => 'Open Cargo luggages', 'primary' => true, 'id' => 'tds-tk-parcel-go'],
		['href' => '/operator/app.php?module=cargo&page=view.php', 'label' => 'Luggage status', 'id' => 'tds-tk-parcel-status'],
	],
];
require dirname(__DIR__) . '/_hub.php';
