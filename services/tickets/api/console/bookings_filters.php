<?php
/**
 * GET /tickets/api/console/bookings_filters.php — Book list filter options (T-P0-03).
 */
declare(strict_types=1);

require_once dirname(__DIR__, 2) . '/includes/tessera_console_api.php';
require_once dirname(__DIR__, 2) . '/includes/tessera_rbac.php';
require_once dirname(__DIR__, 2) . '/includes/tessera_tenant.php';
require_once dirname(__DIR__, 2) . '/includes/tessera_bookings_list.php';

tessera_console_boot('GET, OPTIONS');
tessera_console_require_method('GET');
$username = tessera_console_require_session();

require_once dirname(__DIR__, 2) . '/connect.php';
if (!isset($db) || !($db instanceof mysqli) || $db->connect_error) {
	tessera_console_error('db_unavailable', 'Database unavailable', 503);
}
tessera_console_require_capability($db, $username, 'tickets');

$opts = tessera_bookings_filter_options($db);

tessera_console_ok([
	'starts' => $opts['starts'],
	'ends' => $opts['ends'],
	'buses' => $opts['buses'],
	'issuers' => $opts['issuers'],
	'status_presets' => [
		['id' => 'all', 'label' => 'All'],
		['id' => 'complete', 'label' => 'Complete'],
		['id' => 'booked', 'label' => 'Reserved'],
		['id' => 'half_complete', 'label' => 'Sub routes'],
		['id' => 'due', 'label' => 'Due'],
	],
]);
