<?php
/**
 * GET /tickets/api/console/home_filters.php — Home route filter options (T-P0-06).
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

$opts = tessera_home_route_options($db, $username);

tessera_console_ok([
	'routes' => $opts['routes'],
	'can_all' => $opts['can_all'],
	'default_route' => $opts['can_all'] ? '__all__' : (($opts['routes'][0]['id'] ?? '')),
	'month_label' => date('F Y'),
]);
