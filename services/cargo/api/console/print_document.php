<?php
/**
 * GET /cargo/api/console/print_document.php — luggage and cargo print payloads (C-P2).
 * Query: kind=ticket|multiple|manifest|store|receipt|pos|income
 */
declare(strict_types=1);

require_once __DIR__ . '/_boot.php';
require_once dirname(__DIR__, 2) . '/includes/tessera_cargo_prints.php';

[$db] = tessera_cargo_console_boot('GET, OPTIONS');
tessera_console_require_method('GET');

tessera_console_ok(tessera_cargo_prints_document($db, (string) ($_GET['kind'] ?? 'ticket'), $_GET));
