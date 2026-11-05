<?php
/** GET /tickets/api/console/hire_invoice.php — hire invoice preview (T-P3-01). */
declare(strict_types=1);

require_once dirname(__DIR__, 2) . '/includes/tessera_console_api.php';
require_once dirname(__DIR__, 2) . '/includes/tessera_rbac.php';
require_once dirname(__DIR__, 2) . '/includes/tessera_tenant.php';
require_once dirname(__DIR__, 2) . '/includes/tessera_tickets_leftovers.php';

tessera_console_boot('GET, OPTIONS');
tessera_console_require_method('GET');
$username = tessera_console_require_session();
require_once dirname(__DIR__, 2) . '/connect.php';
if (!isset($db) || !($db instanceof mysqli) || $db->connect_error) {
	tessera_console_error('db_unavailable', 'Database unavailable', 503);
}
tessera_console_require_capability($db, $username, 'tickets');

tessera_console_ok(tessera_tickets_hire_invoice([
	'bus' => (string) ($_GET['bus'] ?? ''),
	'route' => (string) ($_GET['route'] ?? ''),
	'date' => (string) ($_GET['date'] ?? ''),
	'time' => (string) ($_GET['time'] ?? ''),
	'class' => (string) ($_GET['class'] ?? ''),
	'seats' => (int) ($_GET['seats'] ?? 0),
	'fare' => (float) ($_GET['fare'] ?? 0),
]));
