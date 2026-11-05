<?php
/**
 * GET /tickets/api/console/home.php — Tickets Home dashboard payload (T-P0-06).
 * Query: route (__all__ or route name)
 */
declare(strict_types=1);

require_once dirname(__DIR__, 2) . '/includes/tessera_console_api.php';
require_once dirname(__DIR__, 2) . '/includes/tessera_rbac.php';
require_once dirname(__DIR__, 2) . '/includes/tessera_home_dashboard.php';

tessera_console_boot('GET, OPTIONS');
tessera_console_require_method('GET');
$username = tessera_console_require_session();

require_once dirname(__DIR__, 2) . '/connect.php';
if (!isset($db) || !($db instanceof mysqli) || $db->connect_error) {
	tessera_console_error('db_unavailable', 'Database unavailable', 503);
}
tessera_console_require_capability($db, $username, 'tickets');

$route = (string) ($_GET['route'] ?? '__all__');
$payload = tessera_home_dashboard_payload($db, $route);

tessera_console_ok($payload);
