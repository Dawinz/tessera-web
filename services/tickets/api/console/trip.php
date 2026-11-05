<?php
/**
 * GET /tickets/api/console/trip.php?route_day_id=&trip_date=&route_id=
 */
declare(strict_types=1);

require_once dirname(__DIR__, 2) . '/includes/tessera_console_api.php';
require_once dirname(__DIR__, 2) . '/includes/tessera_rbac.php';
require_once dirname(__DIR__, 2) . '/includes/schedule_trip_from_request.php';

tessera_console_boot('GET, OPTIONS');
tessera_console_require_method('GET');
$username = tessera_console_require_session();

require_once dirname(__DIR__, 2) . '/connect.php';
if (!isset($db) || !($db instanceof mysqli) || $db->connect_error) {
	tessera_console_error('db_unavailable', 'Database unavailable', 503);
}
require_once dirname(__DIR__, 2) . '/includes/tessera_tenant.php';
tessera_console_require_capability($db, $username, 'tickets');

$trip = schedule_trip_from_request($db);
if ($trip === null) {
	tessera_console_error('not_found', 'Trip not found', 404);
}

$legacyRole = '';
$rq = mysqli_query($db, "SELECT role FROM users WHERE username='" . mysqli_real_escape_string($db, $username) . "' LIMIT 1");
if ($rq && ($rr = mysqli_fetch_assoc($rq))) {
	$legacyRole = (string) ($rr['role'] ?? '');
}
$isAdmin = in_array($legacyRole, ['Super Administrator', 'Administrator'], true)
	|| in_array(tessera_user_role_code($db, $username), ['owner', 'manager'], true);

tessera_console_ok([
	'trip' => $trip,
	'can_manage_template' => $isAdmin,
]);
