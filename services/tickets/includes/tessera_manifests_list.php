<?php
/**
 * Shared Manifest list helpers (T-P0-04).
 */
declare(strict_types=1);

if (!function_exists('tessera_manifests_filter_options')) {
	/**
	 * @return array{routes:list<array{id:string,label:string}>,buses:list<string>}
	 */
	function tessera_manifests_filter_options(mysqli $db, string $username): array
	{
		$routes = [];
		$userEsc = mysqli_real_escape_string($db, $username);
		$aq = mysqli_query(
			$db,
			"SELECT DISTINCT route_id, start_route, end_route FROM assigned_routes WHERE username='$userEsc' ORDER BY start_route, end_route"
		);
		if ($aq && mysqli_num_rows($aq) > 0) {
			while ($row = mysqli_fetch_assoc($aq)) {
				$routes[] = [
					'id' => (string) ($row['route_id'] ?? ''),
					'label' => trim((string) ($row['start_route'] ?? '') . ' to ' . (string) ($row['end_route'] ?? '')),
				];
			}
		} else {
			$mq = mysqli_query(
				$db,
				"SELECT DISTINCT route_id, start_location, end_location FROM manifest
				 WHERE route_id IS NOT NULL AND route_id <> 0
				 ORDER BY start_location, end_location LIMIT 500"
			);
			if ($mq) {
				while ($row = mysqli_fetch_assoc($mq)) {
					$routes[] = [
						'id' => (string) ($row['route_id'] ?? ''),
						'label' => trim((string) ($row['start_location'] ?? '') . ' to ' . (string) ($row['end_location'] ?? '')),
					];
				}
			}
		}

		$buses = [];
		$vq = mysqli_query($db, "SELECT name FROM vehicle WHERE name IS NOT NULL AND name <> '' ORDER BY name ASC LIMIT 500");
		if ($vq) {
			while ($row = mysqli_fetch_assoc($vq)) {
				$buses[] = (string) $row['name'];
			}
		}
		if ($buses === []) {
			$bq = mysqli_query(
				$db,
				"SELECT DISTINCT bus AS name FROM manifest WHERE bus IS NOT NULL AND bus <> '' ORDER BY bus ASC LIMIT 500"
			);
			if ($bq) {
				while ($row = mysqli_fetch_assoc($bq)) {
					$buses[] = (string) $row['name'];
				}
			}
		}

		return ['routes' => $routes, 'buses' => $buses];
	}
}

if (!function_exists('tessera_manifests_list_query')) {
	/**
	 * @param array{
	 *   q?:string,
	 *   route_id?:string,
	 *   bus?:string,
	 *   trip_date_from?:string,
	 *   trip_date_to?:string
	 * } $filters
	 * @return array{where:string,params_note:array<string,mixed>}
	 */
	function tessera_manifests_list_query(mysqli $db, array $filters): array
	{
		$parts = ['1=1'];
		$note = [];

		$routeId = trim((string) ($filters['route_id'] ?? ''));
		if ($routeId !== '' && ctype_digit($routeId)) {
			$parts[] = 'route_id=' . (int) $routeId;
			$note['route_id'] = $routeId;
		}

		$bus = trim((string) ($filters['bus'] ?? ''));
		if ($bus !== '') {
			$esc = mysqli_real_escape_string($db, $bus);
			$parts[] = "bus='$esc'";
			$note['bus'] = $bus;
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

		$q = trim((string) ($filters['q'] ?? ''));
		if ($q !== '') {
			$esc = mysqli_real_escape_string($db, $q);
			$parts[] = "(trip_date LIKE '%$esc%' OR bus LIKE '%$esc%' OR start_location LIKE '%$esc%'
				OR end_location LIKE '%$esc%' OR driver LIKE '%$esc%' OR conductor LIKE '%$esc%'
				OR CAST(id AS CHAR) LIKE '%$esc%')";
			$note['q'] = $q;
		}

		return [
			'where' => implode(' AND ', $parts),
			'params_note' => $note,
		];
	}
}

if (!function_exists('tessera_manifest_relative_label')) {
	function tessera_manifest_relative_label(string $tripDate): array
	{
		$tz = new DateTimeZone('Africa/Dar_es_Salaam');
		$today = new DateTimeImmutable('today', $tz);
		$date = DateTimeImmutable::createFromFormat('!Y-m-d', $tripDate, $tz);
		if (!$date) {
			return ['label' => $tripDate, 'state' => 'unknown', 'display' => $tripDate];
		}
		$diff = (int) $today->diff($date)->format('%r%a');
		if ($diff === 0) {
			$rel = 'Today';
			$state = 'today';
		} elseif ($diff === 1) {
			$rel = 'Tomorrow';
			$state = 'upcoming';
		} elseif ($diff === -1) {
			$rel = 'Yesterday';
			$state = 'past';
		} elseif ($diff > 1) {
			$rel = 'in ' . $diff . ' days';
			$state = 'upcoming';
		} else {
			$rel = abs($diff) . ' days ago';
			$state = 'past';
		}
		return [
			'label' => $rel,
			'state' => $state,
			'display' => $date->format('D, j M Y'),
		];
	}
}

if (!function_exists('tessera_manifest_row_to_item')) {
	/**
	 * @param array<string,mixed> $row
	 * @return array<string,mixed>
	 */
	function tessera_manifest_row_to_item(array $row): array
	{
		$id = (int) ($row['id'] ?? 0);
		$routeDayId = (int) ($row['route_day_id'] ?? 0);
		$tripDate = (string) ($row['trip_date'] ?? '');
		$routeId = (string) ($row['route_id'] ?? '');
		$bus = (string) ($row['bus'] ?? '');
		$start = (string) ($row['start_location'] ?? '');
		$end = (string) ($row['end_location'] ?? '');
		$amount = (float) ($row['amount'] ?? 0);
		$road = (float) ($row['road_collection'] ?? 0);
		$rel = tessera_manifest_relative_label($tripDate);

		$bookParams = http_build_query([
			'route_day_id' => $routeDayId,
			'trip_date' => $tripDate,
			'route_id' => $routeId,
		]);

		return [
			'id' => $id,
			'bus' => $bus,
			'trip_date' => $tripDate,
			'trip_date_display' => $rel['display'],
			'trip_relative' => $rel['label'],
			'trip_state' => $rel['state'],
			'start_time' => (string) ($row['start_time'] ?? ''),
			'start_location' => $start,
			'end_location' => $end,
			'route_label' => trim($start . ' → ' . $end),
			'driver' => (string) ($row['driver'] ?? ''),
			'conductor' => (string) ($row['conductor'] ?? ''),
			'seats' => (int) ($row['seats'] ?? 0),
			'amount' => $amount,
			'road_collection' => $road,
			'gross' => $amount + $road,
			'route_day_id' => $routeDayId,
			'route_id' => $routeId,
			'status' => (string) ($row['status'] ?? ''),
			'canceled' => (int) ($row['canceled'] ?? 0),
			'book_url' => '/operator/app.php?module=tickets&page=schedule_book.php&' . $bookParams,
			'pdf_url' => '/tickets/pdf/index.php?id=' . $routeDayId . '&trip_date=' . rawurlencode($tripDate) . '&manifest=' . $id,
			'sales_url' => '/tickets/sales_summary.php?id=' . $routeDayId . '&trip_date=' . rawurlencode($tripDate) . '&manifest=' . $id,
			'pos_url' => '/tickets/print_pos.php?id=' . $routeDayId . '&trip_date=' . rawurlencode($tripDate),
			'passengers_url' => '/tickets/passenger_manifest.php?id=' . $routeDayId
				. '&trip_date=' . rawurlencode($tripDate)
				. '&route_id=' . rawurlencode($routeId)
				. '&bus=' . rawurlencode($bus),
			'can_delete' => (int) ($row['seats'] ?? 0) === 0,
		];
	}
}
