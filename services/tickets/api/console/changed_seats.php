<?php
/** GET /tickets/api/console/changed_seats.php — seat change audit (T-P1-07). */
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

$pager = tessera_console_pagination(100, 200);
$payload = tessera_changed_seats_payload($db, (string) ($_GET['q'] ?? ''), $pager['page'], $pager['per_page']);
tessera_console_ok($payload, ['page' => $pager['page'], 'per_page' => $pager['per_page']]);
