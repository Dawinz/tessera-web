<?php
/**
 * Resolve trip context from schedule book/manage query parameters.
 *
 * @return array<string, mixed>|null
 */
function schedule_trip_from_request(mysqli $db): ?array
{
	$routeDayId = (int) ($_GET['route_day_id'] ?? 0);
	if ($routeDayId < 1 && isset($_GET['id']) && (string) $_GET['id'] !== '') {
		$routeDayId = (int) base64_decode((string) $_GET['id']);
	}

	$tripDate = trim((string) ($_GET['trip_date'] ?? ''));
	if ($tripDate !== '' && !preg_match('/^\d{4}-\d{2}-\d{2}$/', $tripDate)) {
		$decoded = base64_decode($tripDate, true);
		if ($decoded !== false && preg_match('/^\d{4}-\d{2}-\d{2}$/', $decoded)) {
			$tripDate = $decoded;
		} else {
			$ts = strtotime($tripDate);
			$tripDate = $ts ? date('Y-m-d', $ts) : '';
		}
	}

	if ($routeDayId < 1 || $tripDate === '' || !preg_match('/^\d{4}-\d{2}-\d{2}$/', $tripDate)) {
		return null;
	}

	$tenantSql = tessera_sql_tenant_eq('rd');
	$q = mysqli_query(
		$db,
		"SELECT rd.*, r.fullname, r.route_name, r.start_route, r.end_route, r.fare_price, r.currency_symbol, r.route_id AS main_route_id
		 FROM route_day rd
		 INNER JOIN routes r ON (r.id = rd.route_id OR r.route_id = rd.route_id) AND " . tessera_sql_tenant_eq('r') . "
		 WHERE rd.id=" . (int) $routeDayId . " AND " . $tenantSql . " LIMIT 1"
	);
	if (!$q || !($rd = mysqli_fetch_assoc($q))) {
		return null;
	}

	$bus = (string) ($rd['bus'] ?? $rd['buss'] ?? '');
	$routeId = (string) ($rd['route_id'] ?? '');
	$routeLabel = (string) ($rd['fullname'] ?? $rd['route_name'] ?? (($rd['start_route'] ?? '') . ' to ' . ($rd['end_route'] ?? '')));

	$seatsTotal = 0;
	if ($bus !== '') {
		$vq = mysqli_query($db, "SELECT seats FROM vehicle WHERE " . tessera_sql_tenant_eq() . " AND name='" . mysqli_real_escape_string($db, $bus) . "' LIMIT 1");
		if ($vq && ($v = mysqli_fetch_assoc($vq))) {
			$seatsTotal = (int) ($v['seats'] ?? 0);
		}
	}

	$booked = 0;
	$dEsc = mysqli_real_escape_string($db, $tripDate);
	$bEsc = mysqli_real_escape_string($db, $bus);
	$bq = mysqli_query(
		$db,
		"SELECT COUNT(*) AS c FROM booking WHERE " . tessera_sql_tenant_eq()
		. " AND trip_date='$dEsc' AND bus='$bEsc' AND (delete_book='' OR delete_book IS NULL)"
	);
	if ($bq && ($b = mysqli_fetch_assoc($bq))) {
		$booked = (int) ($b['c'] ?? 0);
	}

	return [
		'route_day_id' => $routeDayId,
		'trip_date' => $tripDate,
		'trip_date_display' => date('m/d/Y', strtotime($tripDate)),
		'route_id' => $routeId,
		'route_id_main' => $routeId,
		'bus' => $bus,
		'fare_price' => (float) ($rd['fare_price'] ?? 0),
		'currency_symbol' => (string) ($rd['currency_symbol'] ?? ''),
		'start_route' => (string) ($rd['start_route'] ?? ''),
		'end_route' => (string) ($rd['end_route'] ?? ''),
		'route_name' => $routeLabel,
		'departure' => (string) ($rd['departure'] ?? $rd['deperture'] ?? ''),
		'arrival' => (string) ($rd['arrival'] ?? $rd['arraival'] ?? ''),
		'seats_booked' => $booked,
		'seats_total' => $seatsTotal,
		'status' => (string) ($rd['status'] ?? ''),
		'weekday' => date('l', strtotime($tripDate)),
	];
}
