<?php
/**
 * GET /cargo/api/console/checkout_filters.php — routes for destination checkout (C-P0-05).
 */
declare(strict_types=1);

require_once __DIR__ . '/_boot.php';
require_once dirname(__DIR__, 2) . '/includes/tessera_cargo_checkout.php';

[$db, $username] = tessera_cargo_console_boot('GET, OPTIONS');
tessera_console_require_method('GET');

tessera_console_ok(tessera_cargo_checkout_filters($db, $username));
