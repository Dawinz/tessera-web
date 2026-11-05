<?php
/**
 * POST /tickets/api/console/booking_delete.php
 * Body JSON: { id, reason }
 * Soft-deletes a completed/half booking (or hard-deletes a still-reserved seat).
 * Mirrors classic delete_seat.php rules for Administrator / Super Administrator.
 */
declare(strict_types=1);

require_once dirname(__DIR__, 2) . '/includes/tessera_console_api.php';
require_once dirname(__DIR__, 2) . '/includes/tessera_rbac.php';
require_once dirname(__DIR__, 2) . '/includes/tessera_tenant.php';

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
$reasonPlain = trim((string) ($payload['reason'] ?? ''));
if ($id < 1) {
	tessera_console_error('validation_error', 'Booking id is required', 422, ['fields' => ['id' => 'required']]);
}
if ($reasonPlain === '') {
	tessera_console_error('validation_error', 'Add a short delete reason', 422, ['fields' => ['reason' => 'required']]);
}

$role = '';
$uq = mysqli_query($db, "SELECT role FROM users WHERE username='" . mysqli_real_escape_string($db, $username) . "' LIMIT 1");
if ($uq && ($ur = mysqli_fetch_assoc($uq))) {
	$role = (string) ($ur['role'] ?? '');
}
if ($role !== 'Administrator' && $role !== 'Super Administrator') {
	tessera_console_error('forbidden', 'Only administrators can delete bookings', 403);
}

$bq = mysqli_query($db, 'SELECT * FROM booking WHERE id=' . $id . ' AND ' . tessera_sql_tenant_eq() . ' LIMIT 1');
if (!$bq || !($row = mysqli_fetch_assoc($bq))) {
	tessera_console_error('not_found', 'Booking not found', 404);
}

$deleteBook = (string) ($row['delete_book'] ?? '');
if ($deleteBook !== '' && $deleteBook !== null) {
	tessera_console_error('conflict', 'Booking is already deleted', 409);
}

$status = (string) ($row['status'] ?? '');
$locker = (string) ($row['locker'] ?? '');
$cost = (float) ($row['cost'] ?? 0);
$tripDate = (string) ($row['trip_date'] ?? '');
$bus = (string) ($row['bus'] ?? '');
$issuer = (string) ($row['issuer'] ?? '');
$seatNo = (string) ($row['seat_no'] ?? '');
$firstname = (string) ($row['firstname'] ?? '');
$lastname = (string) ($row['lastname'] ?? '');
$routeId = (string) ($row['route_id'] ?? '');
$phone = (string) ($row['phone_no'] ?? '');
$bookDate = (string) ($row['book_date'] ?? '');
$bookTime = (string) ($row['book_time'] ?? '');
$userFullname = trim($firstname . ' ' . $lastname);
$seatBookedDate = trim($bookDate . ' at ' . $bookTime);
$reason = base64_encode($reasonPlain);

date_default_timezone_set('Africa/Dar_es_Salaam');
$deletedTime = date('m/d/Y') . ' at ' . date('h:i A');

if ($status === 'locked' && $locker !== '' && $locker !== $username && $role !== 'Super Administrator') {
	tessera_console_error('conflict', 'This seat is locked by ' . $locker, 409);
}

/* Reserved seats: hard delete */
if ($status === 'booked') {
	$db->begin_transaction();
	try {
		if (!mysqli_query($db, 'DELETE FROM booking WHERE id=' . $id . ' AND ' . tessera_sql_tenant_eq())) {
			throw new RuntimeException('delete');
		}
		$idEsc = mysqli_real_escape_string($db, (string) $id);
		$seatEsc = mysqli_real_escape_string($db, $seatNo);
		$issuerEsc = mysqli_real_escape_string($db, $issuer);
		$delEsc = mysqli_real_escape_string($db, $deletedTime);
		$userEsc = mysqli_real_escape_string($db, $userFullname);
		$whoEsc = mysqli_real_escape_string($db, $username);
		$phoneEsc = mysqli_real_escape_string($db, $phone);
		$bookedEsc = mysqli_real_escape_string($db, $seatBookedDate);
		$tripEsc = mysqli_real_escape_string($db, $tripDate);
		$reasonEsc = mysqli_real_escape_string($db, $reason);
		mysqli_query(
			$db,
			"INSERT INTO deleted (seat_id,seat_no,username,delete_date,ticket_user,ticket_deleter,status,location,phone_no,seat_insert_time,trip_date,reason)
			 VALUES ('$idEsc','$seatEsc','$issuerEsc','$delEsc','$userEsc','$whoEsc','deleted','','$phoneEsc','$bookedEsc','$tripEsc','$reasonEsc')"
		);
		$db->commit();
	} catch (Throwable $e) {
		$db->rollback();
		tessera_console_error('server_error', 'Could not delete reservation', 500);
	}
	tessera_console_ok(['id' => $id, 'mode' => 'hard_delete']);
}

/* Past trips cannot be deleted */
$today = date('Y-m-d');
if ($tripDate !== '' && strtotime($tripDate) < strtotime($today)) {
	tessera_console_error('conflict', 'Cannot delete a booking for a past trip date', 409);
}

/* Route assignment gate (Administrators must be assigned; Super Admin always OK) */
$routeName = '';
$assigned = false;
if ($role === 'Super Administrator') {
	$assigned = true;
} else {
	$aq = mysqli_query(
		$db,
		"SELECT route_name FROM assigned_routes WHERE route_id='" . mysqli_real_escape_string($db, $routeId)
		. "' AND username='" . mysqli_real_escape_string($db, $username) . "' LIMIT 1"
	);
	if ($aq && ($ar = mysqli_fetch_assoc($aq))) {
		$assigned = true;
		$routeName = (string) ($ar['route_name'] ?? '');
	}
}
if (!$assigned) {
	tessera_console_error('forbidden', 'You are not assigned to this route', 403);
}

$db->begin_transaction();
try {
	$mq = mysqli_query(
		$db,
		"SELECT amount, canceled, seats FROM manifest WHERE bus='" . mysqli_real_escape_string($db, $bus)
		. "' AND trip_date='" . mysqli_real_escape_string($db, $tripDate) . "' LIMIT 1"
	);
	if ($mq && ($m = mysqli_fetch_assoc($mq))) {
		$newAmount = (float) ($m['amount'] ?? 0) - $cost;
		$canceled = (int) ($m['canceled'] ?? 0) + 1;
		$seats = (int) ($m['seats'] ?? 0) - 1;
		mysqli_query(
			$db,
			"UPDATE manifest SET amount='" . mysqli_real_escape_string($db, (string) $newAmount)
			. "', canceled='" . (int) $canceled . "', seats='" . (int) $seats
			. "' WHERE bus='" . mysqli_real_escape_string($db, $bus)
			. "' AND trip_date='" . mysqli_real_escape_string($db, $tripDate) . "'"
		);
	}

	if (!mysqli_query($db, "UPDATE booking SET delete_book='deleted' WHERE id=$id AND " . tessera_sql_tenant_eq())) {
		throw new RuntimeException('soft');
	}

	$idEsc = mysqli_real_escape_string($db, (string) $id);
	$seatEsc = mysqli_real_escape_string($db, $seatNo);
	$issuerEsc = mysqli_real_escape_string($db, $issuer);
	$delEsc = mysqli_real_escape_string($db, $deletedTime);
	$userEsc = mysqli_real_escape_string($db, $userFullname);
	$whoEsc = mysqli_real_escape_string($db, $username);
	$locEsc = mysqli_real_escape_string($db, $routeName);
	$phoneEsc = mysqli_real_escape_string($db, $phone);
	$bookedEsc = mysqli_real_escape_string($db, $seatBookedDate);
	$tripEsc = mysqli_real_escape_string($db, $tripDate);
	$reasonEsc = mysqli_real_escape_string($db, $reason);
	mysqli_query(
		$db,
		"INSERT INTO deleted (seat_id,seat_no,username,delete_date,ticket_user,ticket_deleter,status,location,phone_no,seat_insert_time,trip_date,reason)
		 VALUES ('$idEsc','$seatEsc','$issuerEsc','$delEsc','$userEsc','$whoEsc','deleted','$locEsc','$phoneEsc','$bookedEsc','$tripEsc','$reasonEsc')"
	);

	$db->commit();
} catch (Throwable $e) {
	$db->rollback();
	tessera_console_error('server_error', 'Could not delete booking', 500);
}

tessera_console_ok(['id' => $id, 'mode' => 'soft_delete']);
