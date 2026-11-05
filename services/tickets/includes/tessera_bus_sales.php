<?php
/**
 * Bus income / bus sales report helpers (T-P1-02).
 * Classic: bus_sales_report.php + fetch_bus_sales.php
 */
declare(strict_types=1);

if (!function_exists('tessera_bus_sales_vehicles')) {
	/**
	 * @return list<string>
	 */
	function tessera_bus_sales_vehicles(mysqli $db): array
	{
		$tenant = function_exists('tessera_sql_tenant_eq') ? tessera_sql_tenant_eq() : '1=1';
		$out = [];
		$q = mysqli_query(
			$db,
			"SELECT DISTINCT bus FROM booking WHERE {$tenant} AND bus IS NOT NULL AND bus != '' ORDER BY bus ASC LIMIT 500"
		);
		while ($q && ($row = mysqli_fetch_assoc($q))) {
			$name = trim((string) ($row['bus'] ?? ''));
			if ($name !== '') {
				$out[] = $name;
			}
		}
		if ($out === []) {
			$vq = mysqli_query($db, "SELECT name FROM vehicle WHERE {$tenant} ORDER BY name ASC LIMIT 500");
			if (!$vq) {
				$vq = mysqli_query($db, 'SELECT name FROM vehicle ORDER BY name ASC LIMIT 500');
			}
			while ($vq && ($row = mysqli_fetch_assoc($vq))) {
				$name = trim((string) ($row['name'] ?? ''));
				if ($name !== '') {
					$out[] = $name;
				}
			}
		}
		return $out;
	}
}

if (!function_exists('tessera_bus_sales_payload')) {
	/**
	 * @return array{items:list<array<string,mixed>>,total:int,summary:array<string,mixed>,vehicle:string,date_from:string,date_to:string}
	 */
	function tessera_bus_sales_payload(
		mysqli $db,
		string $vehicle,
		string $dateFrom,
		string $dateTo,
		string $q = '',
		int $page = 1,
		int $perPage = 100
	): array {
		if (!preg_match('/^\d{4}-\d{2}-\d{2}$/', $dateFrom)) {
			$dateFrom = date('Y-m-d');
		}
		if (!preg_match('/^\d{4}-\d{2}-\d{2}$/', $dateTo)) {
			$dateTo = date('Y-m-d');
		}
		if ($dateFrom > $dateTo) {
			$tmp = $dateFrom;
			$dateFrom = $dateTo;
			$dateTo = $tmp;
		}

		$vehicle = trim($vehicle);
		$page = max(1, $page);
		$perPage = max(1, min(200, $perPage));
		$offset = ($page - 1) * $perPage;

		$tenant = function_exists('tessera_sql_tenant_eq') ? tessera_sql_tenant_eq() : '1=1';
		$fromEsc = mysqli_real_escape_string($db, $dateFrom);
		$toEsc = mysqli_real_escape_string($db, $dateTo);

		$where = "{$tenant} AND trip_date >= '{$fromEsc}' AND trip_date <= '{$toEsc}'
			AND cost != 0 AND (delete_book='' OR delete_book IS NULL)";
		if ($vehicle !== '') {
			$vEsc = mysqli_real_escape_string($db, $vehicle);
			$where .= " AND bus='{$vEsc}'";
		}

		$q = trim($q);
		if ($q !== '') {
			$like = mysqli_real_escape_string($db, '%' . $q . '%');
			$where .= " AND (bus LIKE '{$like}' OR start_location LIKE '{$like}' OR ending_location LIKE '{$like}'
				OR firstname LIKE '{$like}' OR lastname LIKE '{$like}' OR issuer LIKE '{$like}'
				OR seat_no LIKE '{$like}' OR CAST(id AS CHAR) LIKE '{$like}')";
		}

		$total = 0;
		$sumCost = 0.0;
		$cq = mysqli_query($db, "SELECT COUNT(*) AS c, COALESCE(SUM(cost),0) AS s FROM booking WHERE {$where}");
		if ($cq && ($cr = mysqli_fetch_assoc($cq))) {
			$total = (int) ($cr['c'] ?? 0);
			$sumCost = (float) ($cr['s'] ?? 0);
		}

		$items = [];
		$lq = mysqli_query(
			$db,
			"SELECT id, bus, seat_no, firstname, lastname, cost, book_date, book_time, trip_date,
			        start_location, ending_location, issuer
			 FROM booking WHERE {$where}
			 ORDER BY trip_date DESC, id DESC
			 LIMIT {$perPage} OFFSET {$offset}"
		);
		while ($lq && ($row = mysqli_fetch_assoc($lq))) {
			$items[] = [
				'id' => (int) ($row['id'] ?? 0),
				'bus' => (string) ($row['bus'] ?? ''),
				'seat_no' => (string) ($row['seat_no'] ?? ''),
				'fullname' => trim((string) ($row['firstname'] ?? '') . ' ' . (string) ($row['lastname'] ?? '')),
				'cost' => (float) ($row['cost'] ?? 0),
				'book_date' => (string) ($row['book_date'] ?? ''),
				'book_time' => (string) ($row['book_time'] ?? ''),
				'trip_date' => (string) ($row['trip_date'] ?? ''),
				'start_location' => (string) ($row['start_location'] ?? ''),
				'ending_location' => (string) ($row['ending_location'] ?? ''),
				'issuer' => (string) ($row['issuer'] ?? ''),
			];
		}

		return [
			'items' => $items,
			'total' => $total,
			'summary' => [
				'tickets' => $total,
				'total_sales' => $sumCost,
			],
			'vehicle' => $vehicle,
			'date_from' => $dateFrom,
			'date_to' => $dateTo,
		];
	}
}
