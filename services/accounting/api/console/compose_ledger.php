<?php
/**
 * GET /accounts/api/console/compose_ledger.php — vehicle ledger lines (A-P0-02).
 * Query: route, vehicle|bus, date|trip_date, point?, q?
 */
declare(strict_types=1);

require_once __DIR__ . '/_boot.php';
require_once dirname(__DIR__, 2) . '/includes/tessera_accounts_compose.php';

[$db] = tessera_accounts_console_boot('GET, OPTIONS');
tessera_console_require_method('GET');

$route = trim((string) ($_GET['route'] ?? $_GET['route_name'] ?? ''));
$bus = trim((string) ($_GET['vehicle'] ?? $_GET['bus'] ?? ''));
$date = trim((string) ($_GET['date'] ?? $_GET['trip_date'] ?? ''));
$point = (string) ($_GET['point'] ?? '');
$q = (string) ($_GET['q'] ?? '');

if ($route === '' || $bus === '' || $date === '') {
	tessera_console_error('validation_error', 'Route, vehicle, and trip date are required', 422);
}

$payload = tessera_accounts_compose_ledger($db, $route, $bus, $date, $point, $q);

tessera_console_ok([
	'items' => $payload['items'],
	'summary' => $payload['summary'],
	'filters' => [
		'route' => $route,
		'vehicle' => $bus,
		'date' => $date,
		'point' => $point,
		'q' => $q,
	],
]);
