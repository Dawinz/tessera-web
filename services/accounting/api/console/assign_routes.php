<?php
/**
 * GET /accounts/api/console/assign_routes.php — Staff and Accounts routes (A-P1-17).
 */
declare(strict_types=1);

require_once __DIR__ . '/_boot.php';
require_once dirname(__DIR__, 2) . '/includes/tessera_accounts_setup.php';

[$db, $username] = tessera_accounts_console_boot('GET, OPTIONS');
tessera_console_require_method('GET');
tessera_accounts_setup_finish(tessera_accounts_setup_assign_list($db, $username, $_GET));
