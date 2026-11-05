<?php
/**
 * GET /cargo/api/console/register_luggage_bootstrap.php — routes, dial codes, today summary (C-P0-01).
 */
declare(strict_types=1);

require_once __DIR__ . '/_boot.php';

[$db, $username] = tessera_cargo_console_boot('GET, OPTIONS');
tessera_console_require_method('GET');

$payload = tessera_cargo_register_bootstrap($db, $username);
tessera_console_ok($payload);
