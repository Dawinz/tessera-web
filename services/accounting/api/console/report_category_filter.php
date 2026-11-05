<?php
/**
 * GET /accounts/api/console/report_category_filter.php — category and particular filter (A-P1-20).
 * Query: q, route, currency, date_from, date_to, mode=category|particular, ids
 */
declare(strict_types=1);

require_once __DIR__ . '/_boot.php';
require_once dirname(__DIR__, 2) . '/includes/tessera_accounts_reports.php';

[$db, $username] = tessera_accounts_console_boot('GET, OPTIONS');
tessera_console_require_method('GET');

tessera_console_ok(tessera_accounts_reports_category_filter($db, $username, tessera_accounts_reports_query_filters()));
