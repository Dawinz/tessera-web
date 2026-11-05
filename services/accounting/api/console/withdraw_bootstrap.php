<?php
/**
 * GET /accounts/api/console/withdraw_bootstrap.php — main routes for cash (A-P0-04).
 */
declare(strict_types=1);

require_once __DIR__ . '/_boot.php';
require_once dirname(__DIR__, 2) . '/includes/tessera_accounts_withdraw.php';

[$db] = tessera_accounts_console_boot('GET, OPTIONS');
tessera_console_require_method('GET');

tessera_console_ok(tessera_accounts_withdraw_bootstrap($db));
