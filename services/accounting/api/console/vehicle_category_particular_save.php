<?php
/**
 * POST /accounts/api/console/vehicle_category_particular_save.php — Attach a vehicle to a category (A-P1-16).
 */
declare(strict_types=1);

require_once __DIR__ . '/_boot.php';
require_once dirname(__DIR__, 2) . '/includes/tessera_accounts_setup.php';

[$db, $username] = tessera_accounts_console_boot('POST, OPTIONS');
tessera_console_require_method('POST');
tessera_accounts_setup_finish(tessera_accounts_setup_vehicle_category_particular_save($db, $username, tessera_accounts_setup_read_body()));
