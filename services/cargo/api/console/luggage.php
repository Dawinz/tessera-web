<?php
/**
 * GET /cargo/api/console/luggage.php — one parcel for the update form (C-P1-04).
 * Query: id or code. Missing id returns an empty form with routes.
 */
declare(strict_types=1);

require_once __DIR__ . '/_boot.php';
require_once dirname(__DIR__, 2) . '/includes/tessera_cargo_ops.php';

[$db, $username] = tessera_cargo_console_boot('GET, OPTIONS');
tessera_console_require_method('GET');

$id = (int) ($_GET['id'] ?? $_GET['u'] ?? 0);
$code = trim((string) ($_GET['code'] ?? ''));
$row = ($id > 0 || $code !== '') ? tessera_cargo_ops_parcel_row($db, $id, $code) : null;

tessera_console_ok([
	'item' => $row ? tessera_cargo_ops_parcel_public($row) : null,
	'on_manifest' => $row ? tessera_cargo_ops_on_manifest($db, (string) ($row['code'] ?? '')) : false,
	'routes' => tessera_cargo_ops_assigned($db, $username),
	'statuses' => ['paid', 'To be Payed', 'Due payment'],
]);
