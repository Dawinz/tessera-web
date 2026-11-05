<?php
/**
 * GET /accounts/api/console/report_vehicle_grouped.php — vehicles by category (A-P1-19).
 * Query: q, route, currency, date_from, date_to, vehicle_category
 */
declare(strict_types=1);

require_once __DIR__ . '/_boot.php';
require_once dirname(__DIR__, 2) . '/includes/tessera_accounts_reports.php';

[$db, $username] = tessera_accounts_console_boot('GET, OPTIONS');
tessera_console_require_method('GET');

tessera_console_ok(tessera_accounts_reports_vehicle_grouped($db, $username, tessera_accounts_reports_query_filters()));
