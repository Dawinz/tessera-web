<?php
/**
 * GET /tickets/api/console/print_document.php — ticket and trip print payloads (T-P2).
 * Query: kind, id, ids, print_id, trip_date, q, code, starting, ending, date, route, bus, fare
 */
declare(strict_types=1);

require_once dirname(__DIR__, 2) . '/includes/tessera_console_api.php';
require_once dirname(__DIR__, 2) . '/includes/tessera_rbac.php';
require_once dirname(__DIR__, 2) . '/includes/tessera_tenant.php';
require_once dirname(__DIR__, 2) . '/includes/tessera_prints.php';

tessera_console_boot('GET, OPTIONS');
tessera_console_require_method('GET');
$username = tessera_console_require_session();

require_once dirname(__DIR__, 2) . '/connect.php';
if (!isset($db) || !($db instanceof mysqli) || $db->connect_error) {
	tessera_console_error('db_unavailable', 'Database unavailable', 503);
}
tessera_console_require_capability($db, $username, 'tickets');

$kind = (string) ($_GET['kind'] ?? 'ticket');
tessera_console_ok(tessera_prints_document($db, $username, $kind, $_GET));
