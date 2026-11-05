<?php
/**
 * GET /cargo/api/console/manifests.php — paginated vehicle manifests (C-P0-04).
 * Query: q, route, vehicle, date_from, date_to, page, per_page
 */
declare(strict_types=1);

require_once __DIR__ . '/_boot.php';
require_once dirname(__DIR__, 2) . '/includes/tessera_cargo_manifests.php';

[$db, $username] = tessera_cargo_console_boot('GET, OPTIONS');
tessera_console_require_method('GET');

$pager = tessera_console_pagination(25, 100);
$filters = [
	'q' => (string) ($_GET['q'] ?? ''),
	'route' => (string) ($_GET['route'] ?? ''),
	'vehicle' => (string) ($_GET['vehicle'] ?? ''),
	'date_from' => (string) ($_GET['date_from'] ?? ''),
	'date_to' => (string) ($_GET['date_to'] ?? ''),
];

$payload = tessera_cargo_manifests_list(
	$db,
	$username,
	$filters,
	$pager['page'],
	$pager['per_page']
);

tessera_console_ok([
	'items' => $payload['items'],
	'total' => $payload['total'],
	'filters' => $filters,
], [
	'page' => $pager['page'],
	'per_page' => $pager['per_page'],
]);
