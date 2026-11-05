<?php
/**
 * GET /accounts/api/console/job_cards.php — Job cards (A-P1-01).
 */
declare(strict_types=1);

require_once __DIR__ . '/_boot.php';
require_once dirname(__DIR__, 2) . '/includes/tessera_accounts_setup.php';

[$db, $username] = tessera_accounts_console_boot('GET, OPTIONS');
tessera_console_require_method('GET');
tessera_accounts_setup_finish(tessera_accounts_setup_job_cards($db, $username, $_GET));
