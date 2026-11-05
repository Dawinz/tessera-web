<?php
/**
 * GET /tickets/api/console/user_ticket_sales.php — sales by seller + route + trip date (T-P1-03).
 */
declare(strict_types=1);

require_once dirname(__DIR__, 2) . '/includes/tessera_console_api.php';
require_once dirname(__DIR__, 2) . '/includes/tessera_rbac.php';
require_once dirname(__DIR__, 2) . '/includes/tessera_tenant.php';
require_once dirname(__DIR__, 2) . '/includes/tessera_user_ticket_sales.php';

tessera_console_boot('GET, OPTIONS');
tessera_console_require_method('GET');
$username = tessera_console_require_session();

require_once dirname(__DIR__, 2) . '/connect.php';
if (!isset($db) || !($db instanceof mysqli) || $db->connect_error) {
	tessera_console_error('db_unavailable', 'Database unavailable', 503);
}
tessera_console_require_capability($db, $username, 'tickets');

$pager = tessera_console_pagination(100, 200);
$payload = tessera_user_ticket_sales_payload(
	$db,
	$username,
	(string) ($_GET['booker'] ?? ''),
	(int) ($_GET['route_id'] ?? 0),
	(string) ($_GET['date_from'] ?? date('Y-m-d')),
	(string) ($_GET['date_to'] ?? date('Y-m-d')),
	(string) ($_GET['q'] ?? ''),
	$pager['page'],
	$pager['per_page'],
	'booker',
	'trip_date',
	false
);

tessera_console_ok([
	'items' => $payload['items'],
	'total' => $payload['total'],
	'summary' => $payload['summary'],
	'booker' => $payload['booker'],
	'route_id' => $payload['route_id'],
	'date_from' => $payload['date_from'],
	'date_to' => $payload['date_to'],
], [
	'page' => $pager['page'],
	'per_page' => $pager['per_page'],
]);
