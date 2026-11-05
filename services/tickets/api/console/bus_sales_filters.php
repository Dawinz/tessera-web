<?php
/**
 * GET /tickets/api/console/bus_sales_filters.php (T-P1-02).
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

$vehicles = tessera_bus_sales_vehicles($db);

tessera_console_ok([
	'vehicles' => $vehicles,
	'default_vehicle' => $vehicles[0] ?? '',
	'default_date_from' => date('Y-m-d'),
	'default_date_to' => date('Y-m-d'),
]);
