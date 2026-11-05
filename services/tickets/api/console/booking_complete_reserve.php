<?php
/**
 * POST /tickets/api/console/booking_complete_reserve.php
 * Body JSON: { id }
 * Converts a reserved booking to complete (or half_complete for sub-routes).
 */
declare(strict_types=1);

require_once dirname(__DIR__, 2) . '/includes/tessera_console_api.php';
require_once dirname(__DIR__, 2) . '/includes/tessera_rbac.php';
require_once dirname(__DIR__, 2) . '/includes/tessera_tenant.php';
require_once dirname(__DIR__, 2) . '/includes/tessera_bookings_list.php';

tessera_console_boot('POST, OPTIONS');
tessera_console_require_method('POST');
$username = tessera_console_require_session();

require_once dirname(__DIR__, 2) . '/connect.php';
if (!isset($db) || !($db instanceof mysqli) || $db->connect_error) {
	tessera_console_error('db_unavailable', 'Database unavailable', 503);
}
tessera_console_require_capability($db, $username, 'tickets');

$raw = file_get_contents('php://input');
$payload = json_decode($raw ?: '[]', true);
if (!is_array($payload)) {
	tessera_console_error('validation_error', 'Invalid JSON body', 422);
}

$id = (int) ($payload['id'] ?? 0);
if ($id < 1) {
	tessera_console_error('validation_error', 'Booking id is required', 422, [
		'fields' => ['id' => 'required'],
	]);
}

$tenant = tessera_sql_tenant_eq();
$sel = mysqli_query($db, "SELECT * FROM booking WHERE $tenant AND id=$id LIMIT 1");
if (!$sel || !($row = mysqli_fetch_assoc($sel))) {
	tessera_console_error('not_found', 'Booking not found', 404);
}
if ((string) ($row['status'] ?? '') !== 'booked') {
	tessera_console_error('conflict', 'Only reserved bookings can be completed', 409);
}

$start = mysqli_real_escape_string($db, (string) ($row['start_route'] ?? ''));
$end = mysqli_real_escape_string($db, (string) ($row['end_route'] ?? ''));
$fare = 0.0;
$newStatus = 'complete';

$rq = mysqli_query($db, "SELECT fare_price FROM routes WHERE start_route='$start' AND end_route='$end' LIMIT 1");
if ($rq && ($rr = mysqli_fetch_assoc($rq))) {
	$fare = (float) ($rr['fare_price'] ?? 0);
} else {
	$sq = mysqli_query($db, "SELECT fare_price FROM sub_route WHERE start_route='$start' AND end_route='$end' LIMIT 1");
	if ($sq && ($sr = mysqli_fetch_assoc($sq))) {
		$fare = (float) ($sr['fare_price'] ?? 0);
		$newStatus = 'half_complete';
	} else {
		tessera_console_error('not_found', 'Could not resolve fare for this route', 404);
	}
}

$fareEsc = mysqli_real_escape_string($db, (string) $fare);
$ok = mysqli_query(
	$db,
	"UPDATE booking SET status='$newStatus', book_status='disabled', cost='$fareEsc' WHERE $tenant AND id=$id"
);
if (!$ok) {
	tessera_console_error('server_error', 'Could not complete reservation', 500);
}

$fresh = mysqli_query($db, "SELECT * FROM booking WHERE $tenant AND id=$id LIMIT 1");
$item = $fresh && ($fr = mysqli_fetch_assoc($fresh)) ? tessera_bookings_row_to_item($fr) : null;

tessera_console_ok([
	'booking' => $item,
]);
