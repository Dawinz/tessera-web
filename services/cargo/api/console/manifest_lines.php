<?php
/**
 * GET /cargo/api/console/manifest_lines.php — manifest charges (C-P1-06 and C-P1-10).
 * Query: scope=user|mine, user, bus, date, date_from, date_to, q
 */
declare(strict_types=1);

require_once __DIR__ . '/_boot.php';
require_once dirname(__DIR__, 2) . '/includes/tessera_cargo_user_charges.php';

[$db, $username] = tessera_cargo_console_boot('GET, OPTIONS');
tessera_console_require_method('GET');

$pager = tessera_console_pagination(50, 100);
$payload = tessera_cargo_user_charges_manifest($db, $username, [
	'scope' => (string) ($_GET['scope'] ?? 'mine'),
	'user' => (string) ($_GET['user'] ?? ''),
	'bus' => (string) ($_GET['bus'] ?? ''),
	'date' => (string) ($_GET['date'] ?? ''),
	'date_from' => (string) ($_GET['date_from'] ?? ''),
	'date_to' => (string) ($_GET['date_to'] ?? ''),
	'q' => (string) ($_GET['q'] ?? ''),
], $pager['page'], $pager['per_page']);

tessera_console_ok($payload, [
	'page' => $pager['page'],
	'per_page' => $pager['per_page'],
]);
