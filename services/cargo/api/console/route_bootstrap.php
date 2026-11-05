<?php
/**
 * GET /cargo/api/console/route_bootstrap.php — locations and currencies (C-P1-14).
 */
declare(strict_types=1);

require_once __DIR__ . '/_boot.php';
require_once dirname(__DIR__, 2) . '/includes/tessera_cargo_setup.php';

[$db] = tessera_cargo_console_boot('GET, OPTIONS');
tessera_console_require_method('GET');
tessera_cargo_setup_finish(tessera_cargo_setup_route_bootstrap($db, $_GET));
