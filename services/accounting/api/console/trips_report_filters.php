<?php
/**
 * GET /accounts/api/console/trips_report_filters.php — routes + vehicles (A-P0-05).
 */
declare(strict_types=1);

require_once __DIR__ . '/_boot.php';
require_once dirname(__DIR__, 2) . '/includes/tessera_accounts_manifests.php';

[$db, $username] = tessera_accounts_console_boot('GET, OPTIONS');
tessera_console_require_method('GET');

tessera_console_ok(tessera_accounts_manifests_filters($db, $username));
