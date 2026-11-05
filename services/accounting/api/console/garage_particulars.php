<?php
/**
 * GET /accounts/api/console/garage_particulars.php — Garage items (A-P1-04).
 */
declare(strict_types=1);

require_once __DIR__ . '/_boot.php';
require_once dirname(__DIR__, 2) . '/includes/tessera_accounts_setup.php';

[$db, $username] = tessera_accounts_console_boot('GET, OPTIONS');
tessera_console_require_method('GET');
tessera_accounts_setup_finish(tessera_accounts_setup_garage_particulars($db, $username, $_GET));
