<?php
/**
 * Accounts print drill-downs (A-P2). Live vehicle_account rows, same books as the reports.
 */
declare(strict_types=1);

if (!function_exists('tessera_accounts_prints_doc')) {
	/**
	 * @param list<array{label:string,value:string}> $meta
	 * @param list<string> $columns
	 * @param list<list<string>> $rows
	 * @return array<string,mixed>
	 */
	function tessera_accounts_prints_doc(string $kind, string $title, array $meta, array $columns, array $rows, string $notes = ''): array
	{
		$name = 'Operator';
		if (function_exists('tessera_tenant_row')) {
			$row = tessera_tenant_row();
			if (is_array($row) && trim((string) ($row['display_name'] ?? '')) !== '') {
				$name = (string) $row['display_name'];
			}
		}
		return [
			'kind' => $kind,
			'title' => $title,
			'company' => ['name' => $name, 'logo_url' => ''],
			'meta' => $meta,
			'columns' => $columns,
			'rows' => $rows,
			'row_ids' => [],
			'notes' => $notes,
			'empty' => $rows === [] && $meta === [],
			'selectable' => false,
		];
	}
}

if (!function_exists('tessera_accounts_prints_money')) {
	function tessera_accounts_prints_money(float $value): string
	{
		return number_format($value, 0, '.', ',');
	}
}

if (!function_exists('tessera_accounts_prints_category')) {
	/**
	 * Vehicles that carry a category (or every category when none is named).
	 *
	 * @param array<string,mixed> $filters
	 * @return array<string,mixed>
	 */
	function tessera_accounts_prints_category(mysqli $db, string $username, array $filters): array
	{
		$applied = tessera_accounts_reports_applied($db, $filters);
		$target = $applied['currency'];
		$fx = tessera_accounts_reports_fx($db);
		$where = tessera_accounts_reports_scope($db, $username, $filters);
		$picked = tessera_accounts_reports_category_names($db, $filters);
		$category = trim((string) ($filters['category'] ?? ''));
		if ($category === '' && $picked !== null && ($picked['names'][0] ?? '') !== '' && ($picked['mode'] ?? '') === 'category') {
			$category = (string) $picked['names'][0];
		}
		$extra = '';
		if ($category !== '') {
			$extra = " AND TRIM(category)='" . $db->real_escape_string($category) . "'";
		}
		$sql = "SELECT bus, route_name, IFNULL(NULLIF(currency,''),'{$db->real_escape_string($target)}') AS currency,
				SUM(expenses) AS expenses, SUM(income) AS income, COUNT(*) AS line_count
			FROM vehicle_account
			WHERE {$where} {$extra}
			GROUP BY bus, route_name, currency
			ORDER BY bus ASC";
		$folded = [];
		foreach (tessera_accounts_reports_rows($db, $sql) as $row) {
			$bus = trim((string) ($row['bus'] ?? ''));
			if ($bus === '') {
				continue;
			}
			$key = $bus . "\n" . (string) ($row['route_name'] ?? '');
			$rate = tessera_accounts_reports_rate((string) ($row['currency'] ?? ''), $target, $fx);
			if (!isset($folded[$key])) {
				$folded[$key] = [
					'bus' => $bus,
					'route' => (string) ($row['route_name'] ?? ''),
					'expenses' => 0.0,
					'income' => 0.0,
					'lines' => 0,
				];
			}
			$folded[$key]['expenses'] += tessera_accounts_reports_num($row['expenses'] ?? 0) * $rate;
			$folded[$key]['income'] += tessera_accounts_reports_num($row['income'] ?? 0) * $rate;
			$folded[$key]['lines'] += (int) ($row['line_count'] ?? 0);
		}
		$rows = [];
		foreach ($folded as $item) {
			$rows[] = [
				$item['bus'],
				$item['route'],
				(string) $item['lines'],
				tessera_accounts_prints_money($item['income']),
				tessera_accounts_prints_money($item['expenses']),
			];
		}
		$meta = [
			['label' => 'Period', 'value' => (string) ($applied['period_label'] ?? '')],
			['label' => 'Currency', 'value' => $target],
			['label' => 'Category', 'value' => $category !== '' ? $category : 'All categories'],
		];
		$notes = $rows === [] ? 'No vehicles posted to this category in the period.' : count($rows) . ' vehicles';
		return tessera_accounts_prints_doc('category', 'Category filter', $meta, ['Vehicle', 'Route', 'Lines', 'Income', 'Expenses'], $rows, $notes);
	}
}

if (!function_exists('tessera_accounts_prints_vehicles')) {
	/**
	 * Particulars on one vehicle inside a category.
	 *
	 * @param array<string,mixed> $filters
	 * @return array<string,mixed>
	 */
	function tessera_accounts_prints_vehicles(mysqli $db, string $username, array $filters): array
	{
		$applied = tessera_accounts_reports_applied($db, $filters);
		$vehicle = trim((string) ($filters['vehicle'] ?? ''));
		$category = trim((string) ($filters['category'] ?? ''));
		if ($vehicle === '') {
			return tessera_accounts_prints_doc('vehicles', 'Category vehicle list', [], ['Particular', 'Lines', 'Amount'], [], 'Choose a vehicle.');
		}
		$filters['vehicle'] = $vehicle;
		$target = $applied['currency'];
		$fx = tessera_accounts_reports_fx($db);
		$where = tessera_accounts_reports_scope($db, $username, $filters);
		$extra = $category !== '' ? " AND TRIM(category)='" . $db->real_escape_string($category) . "'" : '';
		$sql = "SELECT TRIM(name) AS particular, TRIM(category) AS category_name,
				IFNULL(NULLIF(currency,''),'{$db->real_escape_string($target)}') AS currency,
				SUM(CASE WHEN status='Income' THEN income ELSE expenses END) AS amount, COUNT(*) AS line_count
			FROM vehicle_account WHERE {$where} {$extra}
			GROUP BY particular, category_name, currency
			ORDER BY particular ASC";
		$folded = [];
		foreach (tessera_accounts_reports_rows($db, $sql) as $row) {
			$name = trim((string) ($row['particular'] ?? ''));
			if ($name === '') {
				$name = trim((string) ($row['category_name'] ?? ''));
			}
			if ($name === '') {
				continue;
			}
			$rate = tessera_accounts_reports_rate((string) ($row['currency'] ?? ''), $target, $fx);
			if (!isset($folded[$name])) {
				$folded[$name] = ['name' => $name, 'category' => (string) ($row['category_name'] ?? ''), 'amount' => 0.0, 'lines' => 0];
			}
			$folded[$name]['amount'] += tessera_accounts_reports_num($row['amount'] ?? 0) * $rate;
			$folded[$name]['lines'] += (int) ($row['line_count'] ?? 0);
		}
		$rows = [];
		foreach ($folded as $item) {
			$rows[] = [$item['name'], $item['category'], (string) $item['lines'], tessera_accounts_prints_money($item['amount'])];
		}
		$meta = [
			['label' => 'Vehicle', 'value' => $vehicle],
			['label' => 'Category', 'value' => $category !== '' ? $category : 'All'],
			['label' => 'Period', 'value' => (string) ($applied['period_label'] ?? '')],
			['label' => 'Currency', 'value' => $target],
		];
		$notes = $rows === [] ? 'No particulars for this vehicle in the period.' : '';
		return tessera_accounts_prints_doc('vehicles', 'Category vehicle list', $meta, ['Particular', 'Category', 'Lines', 'Amount'], $rows, $notes);
	}
}

if (!function_exists('tessera_accounts_prints_particular')) {
	/**
	 * One vehicle, one particular, trip by trip.
	 *
	 * @param array<string,mixed> $filters
	 * @return array<string,mixed>
	 */
	function tessera_accounts_prints_particular(mysqli $db, string $username, array $filters): array
	{
		$applied = tessera_accounts_reports_applied($db, $filters);
		$vehicle = trim((string) ($filters['vehicle'] ?? ''));
		$particular = trim((string) ($filters['particular'] ?? $filters['name'] ?? ''));
		if ($vehicle === '' || $particular === '') {
			return tessera_accounts_prints_doc('particular', 'Vehicle particular', [], ['Date', 'Route', 'Detail', 'Amount'], [], 'Vehicle and particular are required.');
		}
		$target = $applied['currency'];
		$fx = tessera_accounts_reports_fx($db);
		$where = tessera_accounts_reports_scope($db, $username, $filters);
		$name = $db->real_escape_string($particular);
		$sql = "SELECT trip_date, route_name, detail, status, income, expenses,
				IFNULL(NULLIF(currency,''),'{$db->real_escape_string($target)}') AS currency
			FROM vehicle_account
			WHERE {$where} AND TRIM(name)='{$name}'
			ORDER BY trip_date DESC, id DESC LIMIT 200";
		$rows = [];
		$total = 0.0;
		foreach (tessera_accounts_reports_rows($db, $sql) as $row) {
			$rate = tessera_accounts_reports_rate((string) ($row['currency'] ?? ''), $target, $fx);
			$amount = ((string) ($row['status'] ?? '') === 'Income'
				? tessera_accounts_reports_num($row['income'] ?? 0)
				: tessera_accounts_reports_num($row['expenses'] ?? 0)) * $rate;
			$total += $amount;
			$rows[] = [
				(string) ($row['trip_date'] ?? ''),
				(string) ($row['route_name'] ?? ''),
				(string) ($row['detail'] ?? ''),
				tessera_accounts_prints_money($amount),
			];
		}
		$meta = [
			['label' => 'Vehicle', 'value' => $vehicle],
			['label' => 'Particular', 'value' => $particular],
			['label' => 'Period', 'value' => (string) ($applied['period_label'] ?? '')],
			['label' => 'Total', 'value' => tessera_accounts_prints_money($total) . ' ' . $target],
		];
		$notes = $rows === [] ? 'No lines for this particular.' : '';
		return tessera_accounts_prints_doc('particular', 'Vehicle particular', $meta, ['Date', 'Route', 'Detail', 'Amount'], $rows, $notes);
	}
}

if (!function_exists('tessera_accounts_prints_trip_dates')) {
	/**
	 * Particulars grouped by trip date for one vehicle.
	 *
	 * @param array<string,mixed> $filters
	 * @return array<string,mixed>
	 */
	function tessera_accounts_prints_trip_dates(mysqli $db, string $username, array $filters): array
	{
		$applied = tessera_accounts_reports_applied($db, $filters);
		$vehicle = trim((string) ($filters['vehicle'] ?? ''));
		if ($vehicle === '') {
			return tessera_accounts_prints_doc('trip_date', 'Trip-date particulars', [], ['Date', 'Particular', 'Amount'], [], 'Choose a vehicle.');
		}
		$target = $applied['currency'];
		$fx = tessera_accounts_reports_fx($db);
		$where = tessera_accounts_reports_scope($db, $username, $filters);
		$sql = "SELECT trip_date, TRIM(name) AS particular, TRIM(category) AS category_name,
				IFNULL(NULLIF(currency,''),'{$db->real_escape_string($target)}') AS currency,
				SUM(CASE WHEN status='Income' THEN income ELSE expenses END) AS amount
			FROM vehicle_account WHERE {$where}
			GROUP BY trip_date, particular, category_name, currency
			ORDER BY trip_date DESC, particular ASC LIMIT 400";
		$folded = [];
		foreach (tessera_accounts_reports_rows($db, $sql) as $row) {
			$date = (string) ($row['trip_date'] ?? '');
			$name = trim((string) ($row['particular'] ?? ''));
			if ($name === '') {
				$name = trim((string) ($row['category_name'] ?? ''));
			}
			$key = $date . "\n" . $name;
			$rate = tessera_accounts_reports_rate((string) ($row['currency'] ?? ''), $target, $fx);
			if (!isset($folded[$key])) {
				$folded[$key] = ['date' => $date, 'name' => $name, 'amount' => 0.0];
			}
			$folded[$key]['amount'] += tessera_accounts_reports_num($row['amount'] ?? 0) * $rate;
		}
		$rows = [];
		foreach ($folded as $item) {
			$rows[] = [$item['date'], $item['name'], tessera_accounts_prints_money($item['amount'])];
		}
		$meta = [
			['label' => 'Vehicle', 'value' => $vehicle],
			['label' => 'Period', 'value' => (string) ($applied['period_label'] ?? '')],
			['label' => 'Currency', 'value' => $target],
		];
		$notes = $rows === [] ? 'No trip dates in this period.' : '';
		return tessera_accounts_prints_doc('trip_date', 'Trip-date particulars', $meta, ['Trip date', 'Particular', 'Amount'], $rows, $notes);
	}
}

if (!function_exists('tessera_accounts_prints_vehicle_report')) {
	/**
	 * One vehicle's income and expense lines (drill-down from the vehicle statement).
	 *
	 * @param array<string,mixed> $filters
	 * @return array<string,mixed>
	 */
	function tessera_accounts_prints_vehicle_report(mysqli $db, string $username, array $filters): array
	{
		$applied = tessera_accounts_reports_applied($db, $filters);
		$vehicle = trim((string) ($filters['vehicle'] ?? ''));
		if ($vehicle === '') {
			return tessera_accounts_prints_doc('vehicle', 'Vehicle report', [], ['Date', 'Category', 'Particular', 'Income', 'Expense'], [], 'Choose a vehicle.');
		}
		$statement = tessera_accounts_reports_vehicle_statement($db, $username, $filters);
		$target = $applied['currency'];
		$fx = tessera_accounts_reports_fx($db);
		$where = tessera_accounts_reports_scope($db, $username, $filters);
		$sql = "SELECT trip_date, route_name, TRIM(category) AS category_name, TRIM(name) AS particular,
				status, income, expenses, IFNULL(NULLIF(currency,''),'{$db->real_escape_string($target)}') AS currency
			FROM vehicle_account WHERE {$where}
			ORDER BY trip_date DESC, id DESC LIMIT 300";
		$rows = [];
		foreach (tessera_accounts_reports_rows($db, $sql) as $row) {
			$rate = tessera_accounts_reports_rate((string) ($row['currency'] ?? ''), $target, $fx);
			$income = tessera_accounts_reports_num($row['income'] ?? 0) * $rate;
			$expenses = tessera_accounts_reports_num($row['expenses'] ?? 0) * $rate;
			$rows[] = [
				(string) ($row['trip_date'] ?? ''),
				(string) ($row['category_name'] ?? ''),
				(string) ($row['particular'] ?? ''),
				$income > 0 ? tessera_accounts_prints_money($income) : '',
				$expenses > 0 ? tessera_accounts_prints_money($expenses) : '',
			];
		}
		$summary = $statement['summary'] ?? [];
		$meta = [
			['label' => 'Vehicle', 'value' => $vehicle],
			['label' => 'Period', 'value' => (string) ($applied['period_label'] ?? '')],
			['label' => 'Income', 'value' => tessera_accounts_prints_money((float) ($summary['income'] ?? 0))],
			['label' => 'Expenses', 'value' => tessera_accounts_prints_money((float) ($summary['expenses'] ?? 0))],
			['label' => 'Balance', 'value' => tessera_accounts_prints_money((float) ($summary['balance'] ?? 0)) . ' ' . $target],
		];
		$notes = $rows === [] ? 'No ledger lines for this vehicle.' : '';
		return tessera_accounts_prints_doc('vehicle', 'Vehicle report', $meta, ['Date', 'Category', 'Particular', 'Income', 'Expense'], $rows, $notes);
	}
}

if (!function_exists('tessera_accounts_prints_document')) {
	/**
	 * @param array<string,mixed> $filters
	 * @return array<string,mixed>
	 */
	function tessera_accounts_prints_document(mysqli $db, string $username, string $kind, array $filters): array
	{
		$kind = strtolower(trim($kind));
		return match ($kind) {
			'category' => tessera_accounts_prints_category($db, $username, $filters),
			'vehicles' => tessera_accounts_prints_vehicles($db, $username, $filters),
			'particular' => tessera_accounts_prints_particular($db, $username, $filters),
			'trip_date' => tessera_accounts_prints_trip_dates($db, $username, $filters),
			'vehicle' => tessera_accounts_prints_vehicle_report($db, $username, $filters),
			default => tessera_accounts_prints_doc($kind, 'Print', [], [], [], 'Unknown drill-down.'),
		};
	}
}
