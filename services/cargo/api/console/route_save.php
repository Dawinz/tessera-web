<?php
/**
 * POST /cargo/api/console/route_save.php — create or update a cargo route (C-P1-14).
 */
declare(strict_types=1);

require_once __DIR__ . '/_boot.php';
require_once dirname(__DIR__, 2) . '/includes/tessera_cargo_setup.php';

[$db] = tessera_cargo_console_boot('POST, OPTIONS');
tessera_console_require_method('POST');
tessera_cargo_setup_finish(tessera_cargo_setup_route_save($db, tessera_cargo_setup_read_body()));
