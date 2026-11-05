<?php
/**
 * GET /cargo/api/console/deleted_luggages.php — luggage change/delete audit (C-P1-03).
 */
declare(strict_types=1);

require_once __DIR__ . '/_boot.php';
require_once dirname(__DIR__, 2) . '/includes/tessera_cargo_ops.php';

[$db] = tessera_cargo_console_boot('GET, OPTIONS');
tessera_console_require_method('GET');

$pager = tessera_console_pagination(50, 100);
$payload = tessera_cargo_ops_deleted($db, (string) ($_GET['q'] ?? ''), $pager['page'], $pager['per_page']);

tessera_console_ok([
	'items' => $payload['items'],
	'total' => $payload['total'],
], [
	'page' => $pager['page'],
	'per_page' => $pager['per_page'],
]);
