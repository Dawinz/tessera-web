<?php
/**
 * GET /accounts/api/console/report_expense.php — expenses vs previous period (A-P1-23).
 * Query: q, route, vehicle, currency, date_from, date_to
 */
declare(strict_types=1);

require_once __DIR__ . '/_boot.php';
require_once dirname(__DIR__, 2) . '/includes/tessera_accounts_reports.php';

[$db, $username] = tessera_accounts_console_boot('GET, OPTIONS');
tessera_console_require_method('GET');

tessera_console_ok(tessera_accounts_reports_expense($db, $username, tessera_accounts_reports_query_filters()));
