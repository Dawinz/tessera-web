<?php
/**
 * GET /accounts/api/console/withdraw_list.php — cash deposits list (A-P0-04).
 * Query: route?, q?, page?, per_page?
 */
declare(strict_types=1);

require_once __DIR__ . '/_boot.php';
require_once dirname(__DIR__, 2) . '/includes/tessera_accounts_withdraw.php';

[$db] = tessera_accounts_console_boot('GET, OPTIONS');
tessera_console_require_method('GET');

$pager = tessera_console_pagination(25, 100);
$route = (string) ($_GET['route'] ?? '');
$q = (string) ($_GET['q'] ?? '');

$payload = tessera_accounts_withdraw_list($db, $route, $q, $pager['page'], $pager['per_page']);

tessera_console_ok([
	'items' => $payload['items'],
	'total' => $payload['total'],
	'summary' => $payload['summary'],
	'filters' => [
		'route' => $route,
		'q' => $q,
	],
], [
	'page' => $pager['page'],
	'per_page' => $pager['per_page'],
]);
