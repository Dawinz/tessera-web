<?php
/**
 * GET /accounts/api/console/pnl_report.php — profit & loss statement (A-P0-06).
 * Query: date_from, date_to, route?
 */
declare(strict_types=1);

require_once __DIR__ . '/_boot.php';
require_once dirname(__DIR__, 2) . '/includes/tessera_accounts_pnl.php';

[$db, $username] = tessera_accounts_console_boot('GET, OPTIONS');
tessera_console_require_method('GET');

$dateFrom = trim((string) ($_GET['date_from'] ?? ''));
$dateTo = trim((string) ($_GET['date_to'] ?? ''));
$route = trim((string) ($_GET['route'] ?? ''));

if ($dateFrom === '') {
	$dateFrom = date('Y-m-01');
}
if ($dateTo === '') {
	$dateTo = date('Y-m-t');
}

$payload = tessera_accounts_pnl_report($db, $username, $dateFrom, $dateTo, $route);

tessera_console_ok($payload);
