<?php
/**
 * GET /accounts/api/console/exchange_rates.php — Exchange rates (A-P1-10).
 */
declare(strict_types=1);

require_once __DIR__ . '/_boot.php';
require_once dirname(__DIR__, 2) . '/includes/tessera_accounts_setup.php';

[$db, $username] = tessera_accounts_console_boot('GET, OPTIONS');
tessera_console_require_method('GET');
tessera_accounts_setup_finish(tessera_accounts_setup_exchange_rates($db, $username, $_GET));
