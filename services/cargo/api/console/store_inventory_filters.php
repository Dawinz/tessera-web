<?php
/**
 * GET /cargo/api/console/store_inventory_filters.php — route options (C-P0-03).
 */
declare(strict_types=1);

require_once __DIR__ . '/_boot.php';
require_once dirname(__DIR__, 2) . '/includes/tessera_cargo_luggages_list.php';

[$db, $username] = tessera_cargo_console_boot('GET, OPTIONS');
tessera_console_require_method('GET');

tessera_console_ok(tessera_cargo_luggages_filters($db, $username));
