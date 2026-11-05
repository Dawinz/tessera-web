<?php
/**
 * POST /accounts/api/console/particular_delete.php — Delete a particular (A-P1-13).
 */
declare(strict_types=1);

require_once __DIR__ . '/_boot.php';
require_once dirname(__DIR__, 2) . '/includes/tessera_accounts_setup.php';

[$db, $username] = tessera_accounts_console_boot('POST, OPTIONS');
tessera_console_require_method('POST');
tessera_accounts_setup_finish(tessera_accounts_setup_particular_delete($db, $username, tessera_accounts_setup_read_body()));
