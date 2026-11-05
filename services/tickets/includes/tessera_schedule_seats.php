<?php
/**
 * Build seat-map JSON for schedule book (console API + modern UI).
 */
declare(strict_types=1);

require_once __DIR__ . '/vehicle_seat_layout.php';

if (!function_exists('tessera_schedule_seat_map')) {
	/**
	 * @param array<string,mixed> $trip from schedule_trip_from_request()
	 * @return array{rows:list<array<string,mixed>>,extras:list<array<string,mixed>>,summary:array<string,int>}
	 */
	function tessera_schedule_seat_map(mysqli $db, array $trip): array
	{
		if (!function_exists('tessera_sql_tenant_eq')) {
			require_once __DIR__ . '/tessera_tenant.php';
		}

		$routeDayId = (int) ($trip['route_day_id'] ?? 0);
		$tripDate = (string) ($trip['trip_date'] ?? '');
		$routeId = (string) ($trip['route_id'] ?? '');
		$bus = (string) ($trip['bus'] ?? '');

		$vehicleRow = [];
		$busEsc = mysqli_real_escape_string($db, $bus);
		$cols = tessera_vehicle_has_layout_column($db) ? 'seats,state,seat_layout_json' : 'seats,state';
		$vq = mysqli_query($db, "SELECT $cols FROM vehicle WHERE name='$busEsc' AND " . tessera_sql_tenant_eq() . ' LIMIT 1');
		if ($vq && ($row = mysqli_fetch_assoc($vq))) {
			$vehicleRow = $row;
		}

		$patterns = $vehicleRow !== []
			? tessera_vehicle_row_patterns($vehicleRow)
			: tessera_default_seat_patterns((int) ($trip['seats_total'] ?? 0));
		$extras = $vehicleRow !== [] ? tessera_vehicle_layout_extras($vehicleRow) : [];

		$fare = (float) ($trip['fare_price'] ?? 0);
		if (($vehicleRow['state'] ?? '') === 'vip') {
			$ridEsc = mysqli_real_escape_string($db, $routeId);
			$fq = mysqli_query($db, "SELECT vip_price FROM routes WHERE route_id='$ridEsc' OR id='$ridEsc' LIMIT 1");
			if ($fq && ($fr = mysqli_fetch_assoc($fq)) && isset($fr['vip_price'])) {
				$fare = (float) $fr['vip_price'];
			}
		}

		$statusBySeat = [];
		$dEsc = mysqli_real_escape_string($db, $tripDate);
		$rEsc = mysqli_real_escape_string($db, $routeId);
		$bq = mysqli_query(
			$db,
			'SELECT seat_no, status, id, locker_fullname, locked_reasons, amount_left FROM booking WHERE '
			. tessera_sql_tenant_eq()
			. " AND route_day_id=" . (int) $routeDayId
			. " AND trip_date='$dEsc' AND route_id='$rEsc' AND (delete_book='' OR delete_book IS NULL)"
		);
		if ($bq) {
			while ($b = mysqli_fetch_assoc($bq)) {
				$seat = (string) ($b['seat_no'] ?? '');
				if ($seat === '') {
					continue;
				}
				$statusBySeat[$seat] = [
					'status' => (string) ($b['status'] ?? ''),
					'booking_id' => (int) ($b['id'] ?? 0),
					'locker' => (string) ($b['locker_number'] ?? ''),
					'reason' => base64_decode((string) ($b['locked_reasons'] ?? '')) ?: '',
					'amount_left' => (float) ($b['amount_left'] ?? 0),
				];
			}
		}

		$summary = [
			'available' => 0,
			'taken' => 0,
			'locked' => 0,
			'selected' => 0,
			'total' => 0,
		];

		$mapStatus = static function (string $seat) use (&$statusBySeat, &$summary): array {
			$info = $statusBySeat[$seat] ?? null;
			if ($info === null) {
				$summary['available']++;
				$summary['total']++;
				return ['seat' => $seat, 'state' => 'available'];
			}
			$st = strtolower($info['status']);
			$state = 'taken';
			if ($st === 'locked') {
				$state = 'locked';
				$summary['locked']++;
			} elseif (in_array($st, ['booked', 'complete', 'half_complete'], true)) {
				$state = 'taken';
				$summary['taken']++;
			} else {
				$summary['taken']++;
			}
			$summary['total']++;
			return [
				'seat' => $seat,
				'state' => $state,
				'booking_id' => $info['booking_id'],
				'locker' => $info['locker'],
				'reason' => $info['reason'],
			];
		};

		$extraCells = [];
		foreach ($extras as $label) {
			$extraCells[] = $mapStatus((string) $label);
		}

		$alphabet = range('A', 'Z');
		$rows = [];
		foreach ($patterns as $rowIndex => $pattern) {
			$letter = $alphabet[$rowIndex] ?? ('R' . ($rowIndex + 1));
			$seatInRow = 0;
			$cells = [];
			$len = strlen((string) $pattern);
			for ($i = 0; $i < $len; $i++) {
				$ch = $pattern[$i];
				if ($ch === '-') {
					$cells[] = ['type' => 'aisle'];
					continue;
				}
				if ($ch !== 'i') {
					continue;
				}
				$seatInRow++;
				$label = $letter . $seatInRow;
				$cells[] = array_merge(['type' => 'seat'], $mapStatus($label));
			}
			if ($cells !== []) {
				$rows[] = ['label' => $letter, 'cells' => $cells];
			}
		}

		return [
			'rows' => $rows,
			'extras' => $extraCells,
			'summary' => $summary,
			'fare_price' => $fare,
			'currency_symbol' => (string) ($trip['currency_symbol'] ?? ''),
			'bus' => $bus,
		];
	}
}
