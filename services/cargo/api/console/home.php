<?php
/**
 * GET /cargo/api/console/home.php — Cargo home KPIs (C-P1-01).
 * Query: route
 */
declare(strict_types=1);

require_once __DIR__ . '/_boot.php';
require_once dirname(__DIR__, 2) . '/includes/tessera_cargo_ops.php';

[$db, $username] = tessera_cargo_console_boot('GET, OPTIONS');
tessera_console_require_method('GET');

$payload = tessera_cargo_ops_home($db, $username, (string) ($_GET['route'] ?? ''));
tessera_console_ok($payload);
