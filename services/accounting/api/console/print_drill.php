<?php
/**
 * GET /accounts/api/console/print_drill.php — category and vehicle print drill-downs (A-P2).
 * Query: kind=category|vehicles|particular|trip_date|vehicle plus report filters.
 */
declare(strict_types=1);

require_once __DIR__ . '/_boot.php';
require_once dirname(__DIR__, 2) . '/includes/tessera_accounts_reports.php';
require_once dirname(__DIR__, 2) . '/includes/tessera_accounts_prints.php';

[$db, $username] = tessera_accounts_console_boot('GET, OPTIONS');
tessera_console_require_method('GET');

$filters = tessera_accounts_reports_query_filters();
tessera_console_ok(tessera_accounts_prints_document($db, $username, (string) ($_GET['kind'] ?? 'category'), $filters));
