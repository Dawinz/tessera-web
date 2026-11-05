<?php
/**
 * GET /cargo/api/console/luggage_status.php — luggage status + manifest legs (C-P1-05).
 * Query: id or code.
 */
declare(strict_types=1);

require_once __DIR__ . '/_boot.php';
require_once dirname(__DIR__, 2) . '/includes/tessera_cargo_ops.php';

[$db] = tessera_cargo_console_boot('GET, OPTIONS');
tessera_console_require_method('GET');

$id = (int) ($_GET['id'] ?? $_GET['u'] ?? 0);
$code = trim((string) ($_GET['code'] ?? ''));
$payload = tessera_cargo_ops_status($db, $id, $code);

tessera_console_ok($payload);
