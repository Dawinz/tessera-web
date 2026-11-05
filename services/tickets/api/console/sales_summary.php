<?php
/** GET /tickets/api/console/sales_summary.php — trip sales report (T-P1-09). Query: route_day_id, trip_date, manifest? */
declare(strict_types=1);

require_once dirname(__DIR__, 2) . '/includes/tessera_console_api.php';
require_once dirname(__DIR__, 2) . '/includes/tessera_rbac.php';
require_once dirname(__DIR__, 2) . '/includes/tessera_tenant.php';
require_once dirname(__DIR__, 2) . '/includes/tessera_tickets_p1_reports.php';

tessera_console_boot('GET, OPTIONS');
tessera_console_require_method('GET');
$username = tessera_console_require_session();
require_once dirname(__DIR__, 2) . '/connect.php';
if (!isset($db) || !($db instanceof mysqli) || $db->connect_error) {
	tessera_console_error('db_unavailable', 'Database unavailable', 503);
}
tessera_console_require_capability($db, $username, 'tickets');

$result = tessera_sales_summary_payload(
	$db,
	(int) ($_GET['route_day_id'] ?? $_GET['id'] ?? 0),
	(string) ($_GET['trip_date'] ?? ''),
	(int) ($_GET['manifest'] ?? $_GET['manifest_id'] ?? 0)
);
if (empty($result['ok'])) {
	tessera_console_error('validation_error', (string) ($result['error'] ?? 'Failed'), 422);
}
tessera_console_ok([
	'context' => $result['context'] ?? null,
	'items' => $result['items'] ?? [],
	'summary' => $result['summary'] ?? null,
]);
