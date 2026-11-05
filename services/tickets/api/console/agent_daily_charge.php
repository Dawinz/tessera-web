<?php
/**
 * GET /tickets/api/console/agent_daily_charge.php — agent ticket sales for a date range (T-P0-07).
 * Query: booker?, date_from, date_to, page, per_page
 */
declare(strict_types=1);

require_once dirname(__DIR__, 2) . '/includes/tessera_console_api.php';
require_once dirname(__DIR__, 2) . '/includes/tessera_rbac.php';
require_once dirname(__DIR__, 2) . '/includes/tessera_tenant.php';
require_once dirname(__DIR__, 2) . '/includes/tessera_agent_daily_charge.php';

tessera_console_boot('GET, OPTIONS');
tessera_console_require_method('GET');
$username = tessera_console_require_session();

require_once dirname(__DIR__, 2) . '/connect.php';
if (!isset($db) || !($db instanceof mysqli) || $db->connect_error) {
	tessera_console_error('db_unavailable', 'Database unavailable', 503);
}
tessera_console_require_capability($db, $username, 'tickets');

$pager = tessera_console_pagination(100, 200);
$booker = tessera_agent_charge_resolve_booker($db, $username, (string) ($_GET['booker'] ?? ''));
$dateFrom = (string) ($_GET['date_from'] ?? date('Y-m-d'));
$dateTo = (string) ($_GET['date_to'] ?? date('Y-m-d'));

$payload = tessera_agent_charge_payload(
	$db,
	$booker,
	$dateFrom,
	$dateTo,
	$pager['page'],
	$pager['per_page']
);

tessera_console_ok([
	'items' => $payload['items'],
	'total' => $payload['total'],
	'summary' => $payload['summary'],
	'booker' => $payload['booker'],
	'date_from' => $payload['date_from'],
	'date_to' => $payload['date_to'],
	'can_pick_user' => tessera_agent_charge_can_pick_user($db, $username),
], [
	'page' => $pager['page'],
	'per_page' => $pager['per_page'],
]);
