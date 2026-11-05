<?php
/**
 * GET /accounts/api/console/trips_report.php — trip profitability list (A-P0-05).
 * Query: q, route, vehicle, date_from, date_to, page, per_page
 */
declare(strict_types=1);

require_once __DIR__ . '/_boot.php';
require_once dirname(__DIR__, 2) . '/includes/tessera_accounts_trips.php';

[$db, $username] = tessera_accounts_console_boot('GET, OPTIONS');
tessera_console_require_method('GET');

$pager = tessera_console_pagination(25, 100);
$filters = [
	'q' => (string) ($_GET['q'] ?? ''),
	'route' => (string) ($_GET['route'] ?? ''),
	'vehicle' => (string) ($_GET['vehicle'] ?? ''),
	'date_from' => (string) ($_GET['date_from'] ?? ''),
	'date_to' => (string) ($_GET['date_to'] ?? ''),
];

$payload = tessera_accounts_trips_list($db, $username, $filters, $pager['page'], $pager['per_page']);

tessera_console_ok([
	'items' => $payload['items'],
	'total' => $payload['total'],
	'summary' => $payload['summary'],
	'filters' => $filters,
], [
	'page' => $pager['page'],
	'per_page' => $pager['per_page'],
]);
