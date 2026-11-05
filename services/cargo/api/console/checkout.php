<?php
/**
 * GET /cargo/api/console/checkout.php — loaded parcels for destination checkout (C-P0-05).
 * Query: q, route, checked (pending|out|empty), page, per_page
 */
declare(strict_types=1);

require_once __DIR__ . '/_boot.php';
require_once dirname(__DIR__, 2) . '/includes/tessera_cargo_checkout.php';

[$db, $username] = tessera_cargo_console_boot('GET, OPTIONS');
tessera_console_require_method('GET');

$pager = tessera_console_pagination(50, 100);
$filters = [
	'q' => (string) ($_GET['q'] ?? ''),
	'route' => (string) ($_GET['route'] ?? ''),
	'checked' => (string) ($_GET['checked'] ?? ''),
];

$payload = tessera_cargo_checkout_list($db, $filters, $pager['page'], $pager['per_page']);

tessera_console_ok([
	'items' => $payload['items'],
	'total' => $payload['total'],
	'filters' => $filters,
], [
	'page' => $pager['page'],
	'per_page' => $pager['per_page'],
]);
