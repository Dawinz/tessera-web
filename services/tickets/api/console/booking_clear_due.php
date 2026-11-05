<?php
/**
 * POST /tickets/api/console/booking_clear_due.php
 * Body JSON: { id }
 * Clears due/partial payment on a booking.
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
if ((string) ($row['amount_status'] ?? '') !== 'yes') {
	tessera_console_error('conflict', 'Booking is not marked as due', 409);
}

$ok = mysqli_query(
	$db,
	"UPDATE booking SET status='complete', disable_loan='disabled', amount_paid='', amount_status='', amount_left=''
	 WHERE $tenant AND id=$id"
);
if (!$ok) {
	tessera_console_error('server_error', 'Could not clear due payment', 500);
}

$fresh = mysqli_query($db, "SELECT * FROM booking WHERE $tenant AND id=$id LIMIT 1");
$item = $fresh && ($fr = mysqli_fetch_assoc($fresh)) ? tessera_bookings_row_to_item($fr) : null;

tessera_console_ok([
	'booking' => $item,
]);
