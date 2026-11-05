<?php
/**
 * GET /tickets/api/console/schedule_filters.php — start/end location options.
 */
declare(strict_types=1);

require_once dirname(__DIR__, 2) . '/includes/tessera_console_api.php';
require_once dirname(__DIR__, 2) . '/includes/tessera_rbac.php';
require_once dirname(__DIR__, 2) . '/includes/tessera_schedule_events.php';

tessera_console_boot('GET, OPTIONS');
tessera_console_require_method('GET');
$username = tessera_console_require_session();

$connect = dirname(__DIR__, 2) . '/connect.php';
require_once $connect;
if (!isset($db) || !($db instanceof mysqli) || $db->connect_error) {
	tessera_console_error('db_unavailable', 'Database unavailable', 503);
}

tessera_console_require_capability($db, $username, 'tickets');

$locs = tessera_schedule_filter_locations($db, $username);

$role = tessera_user_role_code($db, $username);
$legacyRole = '';
$rq = mysqli_query($db, "SELECT role FROM users WHERE username='" . mysqli_real_escape_string($db, $username) . "' LIMIT 1");
if ($rq && ($rr = mysqli_fetch_assoc($rq))) {
	$legacyRole = (string) ($rr['role'] ?? '');
}
$isAdmin = in_array($legacyRole, ['Super Administrator', 'Administrator'], true)
	|| in_array($role, ['owner', 'manager'], true);

tessera_console_ok([
	'starts' => $locs['starts'],
	'ends' => $locs['ends'],
	'role' => $role,
	'legacy_role' => $legacyRole,
	'can_add_trip_template' => $isAdmin,
]);
