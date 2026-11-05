<?php
/**
 * POST /tickets/api/console/book.php
 * Body JSON: {
 *   route_day_id, trip_date, route_id,
 *   sub_route_id?: int|string,  // optional segment (sub-route)
 *   passengers: [{
 *     seat, firstname, lastname, phone, gender?,
 *     foc?: bool, foc_reason?: string,
 *     transit?: bool, transit_reason?: string
 *   }]
 * }
 * Paid main-route booking by default. Zero-fare (FOC) / transit / sub-route supported.
 */
declare(strict_types=1);

require_once dirname(__DIR__, 2) . '/includes/tessera_console_api.php';
require_once dirname(__DIR__, 2) . '/includes/tessera_rbac.php';
require_once dirname(__DIR__, 2) . '/includes/schedule_trip_from_request.php';
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

$_GET['route_day_id'] = (string) ($payload['route_day_id'] ?? '');
$_GET['trip_date'] = (string) ($payload['trip_date'] ?? '');
$_GET['route_id'] = (string) ($payload['route_id'] ?? '');

$trip = schedule_trip_from_request($db);
if ($trip === null) {
	tessera_console_error('not_found', 'Trip not found', 404);
}

$passengers = $payload['passengers'] ?? null;
if (!is_array($passengers) || $passengers === []) {
	tessera_console_error('validation_error', 'Select at least one seat with passenger details', 422, [
		'fields' => ['passengers' => 'required'],
	]);
}

date_default_timezone_set('Africa/Dar_es_Salaam');
$dateInsert = date('Y-m-d');
$dateTimeInsert = date('h:i A');
$newTime = date('h:i A', strtotime('+1 hours'));
$tenantId = (int) tessera_current_tenant_id();
$printId = 'p' . bin2hex(random_bytes(12)) . date('Hi');

$routeDayId = (int) $trip['route_day_id'];
$tripDate = (string) $trip['trip_date'];
$routeId = (string) $trip['route_id'];
$bus = (string) $trip['bus'];
$fare = (float) ($trip['fare_price'] ?? 0);
$startRoute = (string) $trip['start_route'];
$endRoute = (string) $trip['end_route'];
$currencySymbol = (string) ($trip['currency_symbol'] ?? '');
$subRouteIdStore = '';

$subRouteKey = trim((string) ($payload['sub_route_id'] ?? ''));
if ($subRouteKey !== '' && $subRouteKey !== '0') {
	$subEsc = mysqli_real_escape_string($db, $subRouteKey);
	$pk = (int) ($trip['routes_pk'] ?? 0);
	$mainNo = (int) ($trip['main_route_id'] ?? 0);
	$routeIdInt = (int) $routeId;
	$sq = mysqli_query(
		$db,
		"SELECT id, sub_route_id, start_route, end_route, fare_price, currency_symbol, route_name
		 FROM sub_route
		 WHERE (id='$subEsc' OR sub_route_id='$subEsc')
		   AND (
		     main_route_no IN ($pk, $mainNo, $routeIdInt)
		     OR main_route_no='" . mysqli_real_escape_string($db, (string) ($trip['main_route_id'] ?? '')) . "'
		   )
		 LIMIT 1"
	);
	if (!$sq || !($sub = mysqli_fetch_assoc($sq))) {
		tessera_console_error('validation_error', 'Sub-route segment not found for this trip', 422, [
			'fields' => ['sub_route_id' => 'invalid'],
		]);
	}
	$startRoute = (string) ($sub['start_route'] ?? $startRoute);
	$endRoute = (string) ($sub['end_route'] ?? $endRoute);
	$fare = (float) ($sub['fare_price'] ?? $fare);
	if (!empty($sub['currency_symbol'])) {
		$currencySymbol = (string) $sub['currency_symbol'];
	}
	$subRouteIdStore = (string) (($sub['sub_route_id'] ?? '') !== '' && ($sub['sub_route_id'] ?? '') !== null
		? $sub['sub_route_id']
		: ($sub['id'] ?? ''));
}

$day = date('d', strtotime($tripDate));
$month = date('m', strtotime($tripDate));
$year = date('Y', strtotime($tripDate));

$currency = '';
$initial = $currencySymbol;
$cq = mysqli_query($db, "SELECT currency FROM routes WHERE route_id='" . mysqli_real_escape_string($db, $routeId) . "' OR id='" . mysqli_real_escape_string($db, $routeId) . "' LIMIT 1");
if ($cq && ($cr = mysqli_fetch_assoc($cq))) {
	$currency = (string) ($cr['currency'] ?? '');
}
if ($currency !== '') {
	$iq = mysqli_query($db, "SELECT initial FROM currency WHERE country='" . mysqli_real_escape_string($db, $currency) . "' LIMIT 1");
	if ($iq && ($ir = mysqli_fetch_assoc($iq))) {
		$initial = (string) ($ir['initial'] ?? $initial);
	}
}

$booked = [];
$errors = [];

$db->begin_transaction();
try {
	foreach ($passengers as $idx => $pax) {
		if (!is_array($pax)) {
			throw new RuntimeException('validation');
		}
		$seat = trim((string) ($pax['seat'] ?? ''));
		$firstname = trim(str_replace("'", '^', (string) ($pax['firstname'] ?? '')));
		$lastname = trim(str_replace("'", '^', (string) ($pax['lastname'] ?? '')));
		$phone = trim((string) ($pax['phone'] ?? ''));
		$gender = strtolower(trim((string) ($pax['gender'] ?? '')));
		if (!in_array($gender, ['male', 'female', ''], true)) {
			$gender = '';
		}
		if ($seat === '' || $firstname === '' || $phone === '') {
			$errors[] = ['index' => $idx, 'code' => 'validation_error', 'message' => 'Seat, first name, and phone are required'];
			continue;
		}

		$isFoc = !empty($pax['foc']) && $pax['foc'] !== '0' && $pax['foc'] !== false;
		$isTransit = !empty($pax['transit']) && $pax['transit'] !== '0' && $pax['transit'] !== false;
		$focReason = trim((string) ($pax['foc_reason'] ?? ''));
		$transitReason = trim((string) ($pax['transit_reason'] ?? ''));

		if ($isFoc && $focReason === '') {
			$errors[] = ['index' => $idx, 'seat' => $seat, 'code' => 'validation_error', 'message' => 'Add a short note for zero-fare (FOC)'];
			continue;
		}
		if ($isTransit && $transitReason === '') {
			$errors[] = ['index' => $idx, 'seat' => $seat, 'code' => 'validation_error', 'message' => 'Add a short note for transit'];
			continue;
		}

		$seatCost = ($isFoc || $isTransit) ? 0.0 : $fare;
		$focVal = $isFoc ? 'on' : '';
		$transitVal = $isTransit ? 'checked' : '';
		$reason = $isFoc ? $focReason : ($isTransit ? $transitReason : '');

		$seatEsc = mysqli_real_escape_string($db, $seat);
		$dEsc = mysqli_real_escape_string($db, $tripDate);
		$rEsc = mysqli_real_escape_string($db, $routeId);
		$chk = mysqli_query(
			$db,
			'SELECT id, status FROM booking WHERE ' . tessera_sql_tenant_eq()
			. " AND route_day_id=$routeDayId AND trip_date='$dEsc' AND seat_no='$seatEsc' AND route_id='$rEsc'"
			. " AND (delete_book='' OR delete_book IS NULL) ORDER BY id DESC LIMIT 1"
		);
		if ($chk && mysqli_num_rows($chk) > 0) {
			$errors[] = ['index' => $idx, 'seat' => $seat, 'code' => 'seat_taken', 'message' => "Seat $seat is no longer available"];
			continue;
		}

		$fn = mysqli_real_escape_string($db, $firstname);
		$ln = mysqli_real_escape_string($db, $lastname);
		$ph = mysqli_real_escape_string($db, $phone);
		$gen = mysqli_real_escape_string($db, $gender);
		$busEsc = mysqli_real_escape_string($db, $bus);
		$userEsc = mysqli_real_escape_string($db, $username);
		$startEsc = mysqli_real_escape_string($db, $startRoute);
		$endEsc = mysqli_real_escape_string($db, $endRoute);
		$curEsc = mysqli_real_escape_string($db, $currency);
		$initEsc = mysqli_real_escape_string($db, $initial);
		$printEsc = mysqli_real_escape_string($db, $printId);
		$focEsc = mysqli_real_escape_string($db, $focVal);
		$transitEsc = mysqli_real_escape_string($db, $transitVal);
		$reasonEsc = mysqli_real_escape_string($db, $reason);
		$subEsc = mysqli_real_escape_string($db, $subRouteIdStore);
		$costEsc = mysqli_real_escape_string($db, (string) $seatCost);

		$sql = "INSERT INTO booking (
			seat_no, route_id, bus, status, exp_time, book_time, book_date, trip_date, route_day_id,
			day, month, year, issuer, timer, firstname, lastname, phone_no, validation, cost, reason,
			start_location, ending_location, gender, id_type, initial, currency, transit, booker, print_id,
			amount_left, amount_status, amount_paid, inter_number, sub_route_id, start_route, end_route,
			book_status, disable_loan, foc, locked_status, country, tenant_id
		) VALUES (
			'$seatEsc', '$rEsc', '$busEsc', 'complete', '$newTime', '$dateTimeInsert', '$dateInsert', '$dEsc', $routeDayId,
			'$day', '$month', '$year', '$userEsc', '', '$fn', '$ln', '$ph', '', '$costEsc', '$reasonEsc',
			'$startEsc', '$endEsc', '$gen', '', '$initEsc', '$curEsc', '$transitEsc', '$userEsc', '$printEsc',
			0, '', '0', '', '$subEsc', '$startEsc', '$endEsc',
			'disabled', 'disabled', '$focEsc', 'disabled', '', $tenantId
		)";
		if (!mysqli_query($db, $sql)) {
			$errors[] = ['index' => $idx, 'seat' => $seat, 'code' => 'server_error', 'message' => 'Could not save booking'];
			continue;
		}
		$booked[] = [
			'id' => (int) $db->insert_id,
			'seat' => $seat,
			'firstname' => $firstname,
			'lastname' => $lastname,
			'phone' => $phone,
			'fare' => $seatCost,
			'foc' => $isFoc,
			'transit' => $isTransit,
			'sub_route_id' => $subRouteIdStore,
			'print_id' => $printId,
		];
	}

	if ($errors !== [] && $booked === []) {
		$db->rollback();
		tessera_console_error('conflict', 'Could not book selected seats', 409, ['items' => $errors]);
	}

	$db->commit();
} catch (Throwable $e) {
	$db->rollback();
	tessera_console_error('server_error', 'Booking failed', 500);
}

tessera_console_ok([
	'bookings' => $booked,
	'print_id' => $printId,
	'trip' => $trip,
	'sub_route_id' => $subRouteIdStore,
	'warnings' => $errors,
], [], 201);
