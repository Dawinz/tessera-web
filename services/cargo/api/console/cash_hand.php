<?php
/** GET /cargo/api/console/cash_hand.php — cargo cash-in-hand history (C-P3-02). */
declare(strict_types=1);

require_once __DIR__ . '/_boot.php';
require_once dirname(__DIR__, 2) . '/includes/tessera_cargo_leftovers.php';

[$db] = tessera_cargo_console_boot('GET, OPTIONS');
tessera_console_require_method('GET');

tessera_console_ok(tessera_cargo_cash_hand_list($db));
