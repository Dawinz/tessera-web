<?php
/**
 * GET /cargo/api/console/charges_filters.php — routes + users for My charges (C-P0-06).
 */
declare(strict_types=1);

require_once __DIR__ . '/_boot.php';
require_once dirname(__DIR__, 2) . '/includes/tessera_cargo_charges.php';

[$db, $username] = tessera_cargo_console_boot('GET, OPTIONS');
tessera_console_require_method('GET');

tessera_console_ok(tessera_cargo_charges_filters($db, $username));
