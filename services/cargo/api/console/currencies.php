<?php
/**
 * GET /cargo/api/console/currencies.php — cargo currency list (C-P1-13).
 */
declare(strict_types=1);

require_once __DIR__ . '/_boot.php';
require_once dirname(__DIR__, 2) . '/includes/tessera_cargo_setup.php';

[$db] = tessera_cargo_console_boot('GET, OPTIONS');
tessera_console_require_method('GET');
tessera_cargo_setup_finish(tessera_cargo_setup_currencies($db, $_GET));
