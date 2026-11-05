<?php
/**
 * GET /cargo/api/console/charges.php — My charges parcel list + totals (C-P0-06).
 * Query: q, route, user, date_from, date_to, page, per_page
 */
declare(strict_types=1);

require_once __DIR__ . '/_boot.php';
require_once dirname(__DIR__, 2) . '/includes/tessera_cargo_charges.php';

[$db, $username] = tessera_cargo_console_boot('GET, OPTIONS');
tessera_console_require_method('GET');

$pager = tessera_console_pagination(50, 100);
$filters = [
	'q' => (string) ($_GET['q'] ?? ''),
	'route' => (string) ($_GET['route'] ?? ''),
	'user' => (string) ($_GET['user'] ?? ''),
	'date_from' => (string) ($_GET['date_from'] ?? ''),
	'date_to' => (string) ($_GET['date_to'] ?? ''),
];

$payload = tessera_cargo_charges_list(
	$db,
	$username,
	$filters,
	$pager['page'],
	$pager['per_page']
);

tessera_console_ok([
	'items' => $payload['items'],
	'total' => $payload['total'],
	'summary' => $payload['summary'],
	'seller' => $payload['seller'],
	'can_pick_user' => $payload['can_pick_user'],
	'date_from' => $payload['date_from'],
	'date_to' => $payload['date_to'],
	'filters' => $filters,
], [
	'page' => $pager['page'],
	'per_page' => $pager['per_page'],
]);
