<?php
/**
 * GET /accounts/api/console/daily_list.php — daily expenses for route/month/year (A-P0-03).
 * Query: route, month, year, q?
 */
declare(strict_types=1);

require_once __DIR__ . '/_boot.php';
require_once dirname(__DIR__, 2) . '/includes/tessera_accounts_daily.php';

[$db] = tessera_accounts_console_boot('GET, OPTIONS');
tessera_console_require_method('GET');

$route = (string) ($_GET['route'] ?? '');
$month = (string) ($_GET['month'] ?? date('F'));
$year = (int) ($_GET['year'] ?? date('Y'));
$q = (string) ($_GET['q'] ?? '');

$payload = tessera_accounts_daily_list($db, $route, $month, $year, $q);

tessera_console_ok([
	'items' => $payload['items'],
	'summary' => $payload['summary'],
	'cash' => $payload['cash'],
	'filters' => [
		'route' => $route,
		'month' => $month,
		'year' => $year,
		'q' => $q,
	],
]);
