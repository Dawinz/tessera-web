<?php
/** GET /tickets/api/console/charge_summary.php — seller×route totals (T-P1-08). */
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

$payload = tessera_charge_summary_payload(
	$db,
	(string) ($_GET['date_from'] ?? date('Y-m-d')),
	(string) ($_GET['date_to'] ?? date('Y-m-d'))
);
tessera_console_ok($payload);
