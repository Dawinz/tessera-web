<?php
/**
 * GET /cargo/api/console/vehicle_report.php — trip report and checkout lines (C-P1-11 / C-P1-12).
 * Query: date, bus (or date3). Checkout itself stays on checkout_toggle.php.
 */
declare(strict_types=1);

require_once __DIR__ . '/_boot.php';
require_once dirname(__DIR__, 2) . '/includes/tessera_cargo_ops.php';

[$db] = tessera_cargo_console_boot('GET, OPTIONS');
tessera_console_require_method('GET');

$pager = tessera_console_pagination(50, 100);
$bus = (string) ($_GET['bus'] ?? $_GET['date3'] ?? '');
$payload = tessera_cargo_ops_vehicle_report(
	$db,
	(string) ($_GET['date'] ?? ''),
	$bus,
	$pager['page'],
	$pager['per_page']
);

tessera_console_ok($payload, [
	'page' => $pager['page'],
	'per_page' => $pager['per_page'],
]);
