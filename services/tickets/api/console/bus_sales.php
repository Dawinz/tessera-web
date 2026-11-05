<?php
/**
 * GET /tickets/api/console/bus_sales.php — bus income by vehicle + trip date (T-P1-02).
 * Query: vehicle?, date_from, date_to, q?, page, per_page
 */
declare(strict_types=1);

require_once dirname(__DIR__, 2) . '/includes/tessera_console_api.php';
require_once dirname(__DIR__, 2) . '/includes/tessera_rbac.php';
require_once dirname(__DIR__, 2) . '/includes/tessera_tenant.php';
require_once dirname(__DIR__, 2) . '/includes/tessera_bus_sales.php';

tessera_console_boot('GET, OPTIONS');
tessera_console_require_method('GET');
$username = tessera_console_require_session();

require_once dirname(__DIR__, 2) . '/connect.php';
if (!isset($db) || !($db instanceof mysqli) || $db->connect_error) {
	tessera_console_error('db_unavailable', 'Database unavailable', 503);
}
tessera_console_require_capability($db, $username, 'tickets');

$pager = tessera_console_pagination(100, 200);
$vehicle = (string) ($_GET['vehicle'] ?? $_GET['bus'] ?? '');
$dateFrom = (string) ($_GET['date_from'] ?? date('Y-m-d'));
$dateTo = (string) ($_GET['date_to'] ?? date('Y-m-d'));
$q = (string) ($_GET['q'] ?? '');

$payload = tessera_bus_sales_payload(
	$db,
	$vehicle,
	$dateFrom,
	$dateTo,
	$q,
	$pager['page'],
	$pager['per_page']
);

tessera_console_ok([
	'items' => $payload['items'],
	'total' => $payload['total'],
	'summary' => $payload['summary'],
	'vehicle' => $payload['vehicle'],
	'date_from' => $payload['date_from'],
	'date_to' => $payload['date_to'],
], [
	'page' => $pager['page'],
	'per_page' => $pager['per_page'],
]);
