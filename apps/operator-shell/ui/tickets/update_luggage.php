<?php
/**
 * T-P3-04 Tickets update-luggage merges into Cargo.
 */
declare(strict_types=1);
$u = (int) ($_GET['u'] ?? $_GET['id'] ?? 0);
$href = '/operator/app.php?module=cargo&page=update_luggage.php' . ($u > 0 ? '&u=' . $u : '');
$hub = [
	'id' => 'tds-tk-luggage-edit-move',
	'slice' => 'T-P3-04',
	'title' => 'Update luggage',
	'crumb' => 'Tickets',
	'section' => 'Cargo',
	'lead' => 'Luggage edits are done in Cargo.',
	'paragraphs' => ['Sender, receiver, amounts, and status are on the Cargo update screen.'],
	'base' => '/tickets/',
	'links' => [
		['href' => $href, 'label' => 'Open Cargo update', 'primary' => true, 'id' => 'tds-tk-luggage-edit-go'],
	],
];
require dirname(__DIR__) . '/_hub.php';
