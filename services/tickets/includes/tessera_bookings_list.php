<?php
/**
 * Shared Book list query helpers (T-P0-03).
 */
declare(strict_types=1);

if (!function_exists('tessera_bookings_filter_options')) {
	/**
	 * Distinct filter values for Book list dropdowns.
	 *
	 * @return array{starts:list<string>,ends:list<string>,buses:list<string>,issuers:list<string>}
	 */
	function tessera_bookings_filter_options(mysqli $db): array
	{
		$starts = [];
		$ends = [];
		$buses = [];
		$issuers = [];
		$tenant = tessera_sql_tenant_eq();

		$q = mysqli_query(
			$db,
			"SELECT DISTINCT start_route AS v FROM booking WHERE $tenant AND (delete_book='' OR delete_book IS NULL)
			 AND start_route IS NOT NULL AND start_route <> '' ORDER BY start_route ASC LIMIT 500"
		);
		if ($q) {
			while ($row = mysqli_fetch_assoc($q)) {
				$starts[] = (string) $row['v'];
			}
		}

		$q = mysqli_query(
			$db,
			"SELECT DISTINCT end_route AS v FROM booking WHERE $tenant AND (delete_book='' OR delete_book IS NULL)
			 AND end_route IS NOT NULL AND end_route <> '' ORDER BY end_route ASC LIMIT 500"
		);
		if ($q) {
			while ($row = mysqli_fetch_assoc($q)) {
				$ends[] = (string) $row['v'];
			}
		}

		$q = mysqli_query(
			$db,
			"SELECT DISTINCT bus AS v FROM booking WHERE $tenant AND (delete_book='' OR delete_book IS NULL)
			 AND bus IS NOT NULL AND bus <> '' ORDER BY bus ASC LIMIT 500"
		);
		if ($q) {
			while ($row = mysqli_fetch_assoc($q)) {
				$buses[] = (string) $row['v'];
			}
		}

		$q = mysqli_query(
			$db,
			"SELECT DISTINCT issuer AS v FROM booking WHERE $tenant AND (delete_book='' OR delete_book IS NULL)
			 AND issuer IS NOT NULL AND issuer <> '' ORDER BY issuer ASC LIMIT 500"
		);
		if ($q) {
			while ($row = mysqli_fetch_assoc($q)) {
				$issuers[] = (string) $row['v'];
			}
		}

		return [
			'starts' => $starts,
			'ends' => $ends,
			'buses' => $buses,
			'issuers' => $issuers,
		];
	}
}

if (!function_exists('tessera_bookings_list_query')) {
	/**
	 * Build WHERE + bind-safe escaped filter SQL for Book list.
	 *
	 * @param array{
	 *   q?:string,
	 *   status?:string,
	 *   due?:bool,
	 *   start_route?:string,
	 *   end_route?:string,
	 *   bus?:string,
	 *   issuer?:string,
	 *   trip_date_from?:string,
	 *   trip_date_to?:string,
	 *   foc?:bool,
	 *   transit?:bool
	 * } $filters
	 * @return array{where:string,params_note:array<string,mixed>}
	 */
	function tessera_bookings_list_query(mysqli $db, array $filters): array
	{
		$parts = [tessera_sql_tenant_eq(), "(delete_book='' OR delete_book IS NULL)"];
		$note = [];

		$status = strtolower(trim((string) ($filters['status'] ?? '')));
		if (in_array($status, ['complete', 'booked', 'half_complete', 'locked'], true)) {
			$esc = mysqli_real_escape_string($db, $status);
			$parts[] = "status='$esc'";
			$note['status'] = $status;
		}

		if (!empty($filters['due'])) {
			$parts[] = "amount_status='yes'";
			$note['due'] = true;
		}

		foreach (['start_route', 'end_route', 'bus', 'issuer'] as $col) {
			$val = trim((string) ($filters[$col] ?? ''));
			if ($val === '') {
				continue;
			}
			$esc = mysqli_real_escape_string($db, $val);
			$parts[] = "$col='$esc'";
			$note[$col] = $val;
		}

		$from = trim((string) ($filters['trip_date_from'] ?? ''));
		$to = trim((string) ($filters['trip_date_to'] ?? ''));
		if ($from !== '' && preg_match('/^\d{4}-\d{2}-\d{2}$/', $from)) {
			$esc = mysqli_real_escape_string($db, $from);
			$parts[] = "trip_date >= '$esc'";
			$note['trip_date_from'] = $from;
		}
		if ($to !== '' && preg_match('/^\d{4}-\d{2}-\d{2}$/', $to)) {
			$esc = mysqli_real_escape_string($db, $to);
			$parts[] = "trip_date <= '$esc'";
			$note['trip_date_to'] = $to;
		}

		if (!empty($filters['foc'])) {
			$parts[] = "(reason IS NOT NULL AND TRIM(reason) <> '')";
			$note['foc'] = true;
		}
		if (!empty($filters['transit'])) {
			$parts[] = "transit='checked'";
			$note['transit'] = true;
		}

		$q = trim((string) ($filters['q'] ?? ''));
		if ($q !== '') {
			$esc = mysqli_real_escape_string($db, $q);
			$parts[] = "(id LIKE '%$esc%' OR seat_no LIKE '%$esc%' OR firstname LIKE '%$esc%'
				OR lastname LIKE '%$esc%' OR phone_no LIKE '%$esc%' OR trip_date LIKE '%$esc%'
				OR bus LIKE '%$esc%' OR issuer LIKE '%$esc%')";
			$note['q'] = $q;
		}

		return [
			'where' => implode(' AND ', $parts),
			'params_note' => $note,
		];
	}
}

if (!function_exists('tessera_bookings_row_to_item')) {
	/**
	 * @param array<string,mixed> $row
	 * @return array<string,mixed>
	 */
	function tessera_bookings_row_to_item(array $row): array
	{
		$status = (string) ($row['status'] ?? '');
		$amountStatus = (string) ($row['amount_status'] ?? '');
		$bookStatus = (string) ($row['book_status'] ?? '');
		$canCompleteReserve = $status === 'booked' && $bookStatus !== 'disabled';
		$canClearDue = $amountStatus === 'yes';
		$canDelete = in_array($status, ['complete', 'booked', 'half_complete', 'locked'], true);

		$label = match ($status) {
			'complete' => 'Complete',
			'booked' => 'Reserved',
			'half_complete' => 'Sub route',
			'locked' => 'Locked',
			default => $status !== '' ? ucfirst($status) : 'Unknown',
		};
		if ($amountStatus === 'yes') {
			$label = 'Due';
		}

		$badge = match (true) {
			$amountStatus === 'yes' => 'due',
			$status === 'complete' => 'paid',
			$status === 'booked' => 'warn',
			$status === 'half_complete' => 'info',
			default => 'neutral',
		};

		$firstname = (string) ($row['firstname'] ?? '');
		$lastname = (string) ($row['lastname'] ?? '');
		$fullname = trim($firstname . ' ' . $lastname);

		return [
			'id' => (int) ($row['id'] ?? 0),
			'seat_no' => (string) ($row['seat_no'] ?? ''),
			'fullname' => $fullname,
			'firstname' => $firstname,
			'lastname' => $lastname,
			'phone' => (string) ($row['phone_no'] ?? ''),
			'trip_date' => (string) ($row['trip_date'] ?? ''),
			'bus' => (string) ($row['bus'] ?? ''),
			'issuer' => (string) ($row['issuer'] ?? ''),
			'cost' => (float) ($row['cost'] ?? 0),
			'currency' => (string) ($row['initial'] ?? $row['currency'] ?? ''),
			'status' => $status,
			'status_label' => $label,
			'badge' => $badge,
			'amount_status' => $amountStatus,
			'amount_left' => (float) ($row['amount_left'] ?? 0),
			'start_route' => (string) ($row['start_route'] ?? ''),
			'end_route' => (string) ($row['end_route'] ?? ''),
			'route_id' => (string) ($row['route_id'] ?? ''),
			'route_day_id' => (int) ($row['route_day_id'] ?? 0),
			'print_id' => (string) ($row['print_id'] ?? ''),
			'gender' => (string) ($row['gender'] ?? ''),
			'can_complete_reserve' => $canCompleteReserve,
			'can_clear_due' => $canClearDue,
			'can_delete' => $canDelete,
			'print_url' => '/tickets/print_ticket_table.php?id=' . (int) ($row['id'] ?? 0),
			'transfer_url' => '/tickets/transfer.php?route_day_id='
				. (int) ($row['route_day_id'] ?? 0)
				. '&trip_date=' . rawurlencode((string) ($row['trip_date'] ?? ''))
				. '&route_id=' . rawurlencode((string) ($row['route_id'] ?? ''))
				. '&bus=' . rawurlencode((string) ($row['bus'] ?? ''))
				. '&booking_id=' . (int) ($row['id'] ?? 0),
			'manifest_url' => '/tickets/passenger_manifest_whole.php?id='
				. (int) ($row['route_day_id'] ?? 0)
				. '&trip_date=' . rawurlencode((string) ($row['trip_date'] ?? ''))
				. '&route_id=' . rawurlencode((string) ($row['route_id'] ?? ''))
				. '&bus=' . rawurlencode((string) ($row['bus'] ?? ''))
				. '&manifest_id=' . (int) ($row['id'] ?? 0),
		];
	}
}
