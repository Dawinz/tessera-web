<?php
/**
 * GET /cargo/api/console/user_charge_summary.php — daily totals for one seller (C-P1-08).
 */
declare(strict_types=1);

require_once __DIR__ . '/_boot.php';
require_once dirname(__DIR__, 2) . '/includes/tessera_cargo_user_charges.php';

[$db, $username] = tessera_cargo_console_boot('GET, OPTIONS');
tessera_console_require_method('GET');

$pager = tessera_console_pagination(50, 100);
$payload = tessera_cargo_user_charges_daily($db, $username, [
	'route' => (string) ($_GET['route'] ?? ''),
	'user' => (string) ($_GET['user'] ?? ''),
	'date_from' => (string) ($_GET['date_from'] ?? ''),
	'date_to' => (string) ($_GET['date_to'] ?? ''),
], false, $pager['page'], $pager['per_page']);

tessera_console_ok($payload, [
	'page' => $pager['page'],
	'per_page' => $pager['per_page'],
]);
