<?php
/**
 * GET /accounts/api/console/manifest_trip_dates.php — cargo trip dates for a route (A-P0-01).
 * Query: route
 */
declare(strict_types=1);

require_once __DIR__ . '/_boot.php';
require_once dirname(__DIR__, 2) . '/includes/tessera_accounts_manifests.php';

[$db, $username] = tessera_accounts_console_boot('GET, OPTIONS');
tessera_console_require_method('GET');

$route = trim((string) ($_GET['route'] ?? ''));
$dates = tessera_accounts_manifests_trip_dates($route);

tessera_console_ok([
	'route' => $route,
	'dates' => $dates,
]);
