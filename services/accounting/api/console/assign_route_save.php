<?php
/**
 * POST /accounts/api/console/assign_route_save.php — Tick or clear an Accounts route (A-P1-17).
 */
declare(strict_types=1);

require_once __DIR__ . '/_boot.php';
require_once dirname(__DIR__, 2) . '/includes/tessera_accounts_setup.php';

[$db, $username] = tessera_accounts_console_boot('POST, OPTIONS');
tessera_console_require_method('POST');
tessera_accounts_setup_finish(tessera_accounts_setup_assign_save($db, $username, tessera_accounts_setup_read_body()));
