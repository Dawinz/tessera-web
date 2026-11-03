<?php
// Live figures for the report pages. Ticket sales come from ticket.booking,
// cargo collections from cargo.parcel_info, and trip expenses from
// ticket.expenses and cargo.expenses. Office costs and withdrawals come
// from account.office_expenses and account.cash. The same month is used
// by every report so the totals agree.

include_once __DIR__ . '/report_helpers.php';
require_once __DIR__ . '/tessera_tenant.php';

if (!function_exists('rp_sample_trips')) {

	function rp_sample_routes() {
		return array(
			'Dar es salaam to Lubumbashi' => 'DL',
			'Dar es salaam to Harare' => 'DH',
			'Dar es salaam to Kampala' => 'DK',
			'Nairobi to Dar es salaam' => 'ND',
			'Dar es salaam to Johannesburg' => 'DJ',
		);
	}

	function rp_sample_vehicles() {
		return array('5727AX05', '1492AB04', '5726AX05', '1629AC14', '0816AB25', '9462AB05', '1633AC14', '1630AC14');
	}

	function rp_sample_expense_categories() {
		// category => share of a trip's running cost
		return array(
			'Fuel' => 0.34,
			'Border & permits' => 0.22,
			'Wages & salaries' => 0.15,
			'Passenger meals' => 0.06,
			'Repair & maintenance' => 0.09,
			'Tolls & road charges' => 0.05,
			'Security' => 0.03,
			'Insurance' => 0.04,
			'Cargo handling' => 0.02,
		);
	}

	function rp_live_month($month = null) {
		if ($month !== null && preg_match('/^\d{4}-\d{2}$/', (string) $month)) {
			$GLOBALS['rp_live_month'] = $month;
		}
		if (empty($GLOBALS['rp_live_month'])) {
			$GLOBALS['rp_live_month'] = '2026-10';
		}
		return $GLOBALS['rp_live_month'];
	}

	function rp_live_db() {
		global $db;
		return ($db instanceof mysqli) ? $db : null;
	}

	function rp_live_rows($sql) {
		$conn = rp_live_db();
		if (!$conn) {
			return array();
		}
		$res = mysqli_query($conn, $sql);
		if (!$res) {
			return array();
		}
		$rows = array();
		while ($row = mysqli_fetch_assoc($res)) {
			$rows[] = $row;
		}
		return $rows;
	}

	function rp_month_where($dateColumn, $monthColumn, $yearColumn, $month) {
		$conn = rp_live_db();
		$like = mysqli_real_escape_string($conn, $month . '%');
		$year = mysqli_real_escape_string($conn, substr($month, 0, 4));
		$stamp = strtotime($month . '-01');
		$names = array((string) (int) substr($month, 5, 2), date('m', $stamp), date('M', $stamp), date('F', $stamp));
		$quoted = array();
		foreach ($names as $name) {
			$quoted[] = "'" . mysqli_real_escape_string($conn, $name) . "'";
		}
		return '(' . $dateColumn . " LIKE '" . $like . "' OR (" . $yearColumn . " IN ('" . $year . "', " . (int) $year . ") AND " . $monthColumn . ' IN (' . implode(',', $quoted) . ')))';
	}

	function rp_blank_trip($date, $vehicle, $route) {
		return array(
			'code' => substr(str_replace('-', '', (string) $date), 2) . substr((string) $vehicle, -4),
			'route' => $route,
			'vehicle' => $vehicle,
			'date' => $date,
			'ticket' => 0,
			'cargo_paid' => 0,
			'cargo_to_pay' => 0,
			'expenses' => array(),
			'expenses_total' => 0,
			'unpaid' => 0,
		);
	}

	function rp_sample_trips($seed = 2609, $scale = 1.0, $month = '2026-10') {
		unset($seed, $scale);
		$month = rp_live_month($month);
		$conn = rp_live_db();
		if (!$conn) {
			return array();
		}
		$prefix = mysqli_real_escape_string($conn, $month);
		$groups = array();
		$tickets = rp_live_rows(
			"SELECT b.trip_date AS d, IFNULL(NULLIF(b.bus, ''), 'Unassigned') AS vehicle, " .
			"IFNULL(NULLIF(r.route_name, ''), 'Unassigned route') AS route, SUM(b.cost) AS ticket " .
			"FROM ticket.booking b LEFT JOIN ticket.routes r ON r.route_id = b.route_id " .
			"WHERE b.trip_date LIKE '" . $prefix . "%' AND b.status NOT IN ('deleted', 'cancelled') AND " . tessera_sql_tenant_eq('b') . " " .
			"GROUP BY d, vehicle, route"
		);
		foreach ($tickets as $row) {
			$key = $row['d'] . '|' . $row['vehicle'] . '|' . $row['route'];
			$groups[$key] = rp_blank_trip($row['d'], $row['vehicle'], $row['route']);
			$groups[$key]['ticket'] = (float) $row['ticket'];
		}
		$cargo = rp_live_rows(
			"SELECT date_insert AS d, IFNULL(NULLIF(bus, ''), 'Unassigned') AS vehicle, " .
			"IFNULL(NULLIF(account, ''), 'Unassigned route') AS route, SUM(paid) AS cargo_paid, SUM(to_be) AS cargo_to_pay " .
			"FROM cargo.parcel_info WHERE date_insert LIKE '" . $prefix . "%' GROUP BY d, vehicle, route"
		);
		foreach ($cargo as $row) {
			$key = $row['d'] . '|' . $row['vehicle'] . '|' . $row['route'];
			if (!isset($groups[$key])) {
				$groups[$key] = rp_blank_trip($row['d'], $row['vehicle'], $row['route']);
			}
			$groups[$key]['cargo_paid'] += (float) $row['cargo_paid'];
			$groups[$key]['cargo_to_pay'] += (float) $row['cargo_to_pay'];
		}
		$where = rp_month_where('date', 'month', 'year', $month);
		foreach (array('ticket' => 'Tickets', 'cargo' => 'Cargo') as $schema => $label) {
			$rows = rp_live_rows(
				"SELECT IFNULL(NULLIF(name, ''), '" . $label . "') AS name, SUM(amount+0) AS amt " .
				"FROM " . $schema . ".expenses WHERE " . $where . " GROUP BY name"
			);
			foreach ($rows as $row) {
				$amount = (float) $row['amt'];
				if ($amount == 0) {
					continue;
				}
				$category = $label . ': ' . $row['name'];
				if (!$groups) {
					$groups['expenses'] = rp_blank_trip($month . '-01', 'Unassigned', $label . ' expenses');
				}
				$first = array_key_first($groups);
				if (!isset($groups[$first]['expenses'][$category])) {
					$groups[$first]['expenses'][$category] = 0;
				}
				$groups[$first]['expenses'][$category] += $amount;
				$groups[$first]['expenses_total'] += $amount;
			}
		}
		return array_values($groups);
	}

	function rp_trip_revenue($trip) {
		return $trip['ticket'] + $trip['cargo_paid'] + $trip['cargo_to_pay'];
	}

	function rp_sample_overheads($scale = 1.0) {
		unset($scale);
		$month = rp_live_month();
		if (!rp_live_db()) {
			return array();
		}
		$rows = rp_live_rows(
			"SELECT IFNULL(NULLIF(name, ''), IFNULL(NULLIF(detail, ''), 'Office')) AS name, SUM(amount) AS amt " .
			"FROM account.office_expenses WHERE " . rp_month_where('date', 'month', 'year', $month) . " GROUP BY name"
		);
		$out = array();
		foreach ($rows as $row) {
			if ((float) $row['amt'] != 0) {
				$out[$row['name']] = (float) $row['amt'];
			}
		}
		return $out;
	}

	function rp_sample_withdrawals() {
		$month = rp_live_month();
		if (!rp_live_db()) {
			return array();
		}
		$rows = rp_live_rows(
			"SELECT date, opening_stock AS amount FROM account.cash WHERE " . rp_month_where('date', 'month', 'year', $month)
		);
		$out = array();
		foreach ($rows as $row) {
			$out[] = array(
				'date' => $row['date'] !== '' ? $row['date'] : $month . '-01',
				'amount' => (float) $row['amount'],
			);
		}
		return $out;
	}

	// Currencies in use (currency.php, currency_value.php), value of 1 unit in TSh.
	function rp_sample_currencies() {
		$rows = rp_live_rows("SELECT name, symbol, value_no FROM account.currency");
		$out = array();
		foreach ($rows as $row) {
			$code = trim((string) $row['symbol']) !== '' ? $row['symbol'] : $row['name'];
			$out[$code] = array('name' => $row['name'], 'rate' => (float) $row['value_no']);
		}
		if (!$out) {
			$out['TSh'] = array('name' => 'Tanzanian Shilling', 'rate' => 1);
		}
		return $out;
	}

	// Garage purchases (job cards, jobcard.php) for September 2026. When
	// connecting, read job_card (the invoice) and job_card_entires (its lines).
	function rp_sample_purchase_items() {
		// item => array(unit price range in TSh)
		return array(
			'Tyre 295/80 R22.5' => array(780000, 920000),
			'Engine oil 20L' => array(185000, 215000),
			'Brake pads (set)' => array(240000, 310000),
			'Battery 200Ah' => array(520000, 610000),
			'Fuel filter' => array(45000, 62000),
			'Air filter' => array(70000, 95000),
			'Spring leaf' => array(160000, 210000),
			'Clutch plate' => array(650000, 780000),
			'Wheel alignment' => array(80000, 120000),
			'Workshop labour' => array(150000, 400000),
		);
	}

	function rp_sample_suppliers() {
		return array('City Auto Spares', 'Highway Tyre Centre', 'Coast Lubricants', 'Main Workshop (in-house)', 'Border Truck Parts');
	}

	function rp_sample_purchases() {
		$month = rp_live_month();
		if (!rp_live_db()) {
			return array();
		}
		$rows = rp_live_rows(
			"SELECT id, name, date, amount, item, status FROM account.job_card WHERE " .
			rp_month_where('date', 'month', 'year', $month)
		);
		$purchases = array();
		foreach ($rows as $row) {
			$amount = (float) $row['amount'];
			$paid = (strcasecmp((string) $row['status'], 'paid') === 0) ? $amount : 0;
			$item = trim((string) $row['item']) !== '' ? $row['item'] : 'Purchase';
			$purchases[] = array(
				'invoice' => 'JC-' . $row['id'],
				'date' => trim((string) $row['date']) !== '' ? $row['date'] : $month . '-01',
				'vehicle' => trim((string) $row['name']) !== '' ? $row['name'] : 'Unassigned',
				'supplier' => 'Job card',
				'lines' => array(array('item' => $item, 'qty' => 1, 'price' => $amount, 'amount' => $amount)),
				'amount' => $amount,
				'paid' => $paid,
				'balance' => $amount - $paid,
			);
		}
		return $purchases;
	}

	function rp_purchase_status($purchase) {
		if ($purchase['balance'] <= 0) {
			return array('Paid', 'success');
		}
		return $purchase['paid'] > 0 ? array('Part paid', 'warning') : array('Unpaid', 'danger');
	}

	function rp_supplier_options() {
		$options = array('' => 'All suppliers');
		foreach (rp_sample_suppliers() as $supplier) {
			$options[$supplier] = $supplier;
		}
		return $options;
	}

	// Filter options shared by the reports.
	function rp_route_options() {
		$options = array('' => 'All routes');
		foreach (array_keys(rp_sample_routes()) as $route) {
			$options[$route] = $route;
		}
		return $options;
	}

	function rp_vehicle_options() {
		$options = array('' => 'All vehicles');
		foreach (rp_sample_vehicles() as $vehicle) {
			$options[$vehicle] = $vehicle;
		}
		return $options;
	}

	function rp_currency_options() {
		$options = array();
		foreach (rp_sample_currencies() as $code => $currency) {
			$options[$code] = $code . ' - ' . $currency['name'];
		}
		return $options;
	}

	function rp_period_filters($extra = array()) {
		$month = rp_live_month();
		return array_merge(array(
			array('type' => 'date', 'id' => 'start_date', 'label' => 'From', 'value' => $month . '-01'),
			array('type' => 'date', 'id' => 'end_date', 'label' => 'To', 'value' => date('Y-m-t', strtotime($month . '-01'))),
		), $extra);
	}

	// Totals per group (route, vehicle) from the trips.
	function rp_group_trips($trips, $key) {
		$groups = array();
		foreach ($trips as $trip) {
			$name = $trip[$key];
			if (!isset($groups[$name])) {
				$groups[$name] = array('name' => $name, 'trips' => 0, 'ticket' => 0, 'cargo' => 0, 'revenue' => 0, 'expenses' => 0);
			}
			$groups[$name]['trips']++;
			$groups[$name]['ticket'] += $trip['ticket'];
			$groups[$name]['cargo'] += $trip['cargo_paid'] + $trip['cargo_to_pay'];
			$groups[$name]['revenue'] += rp_trip_revenue($trip);
			$groups[$name]['expenses'] += $trip['expenses_total'];
		}
		foreach ($groups as $name => $group) {
			$groups[$name]['net'] = $group['revenue'] - $group['expenses'];
		}
		uasort($groups, function ($a, $b) {
			return $b['net'] <=> $a['net'];
		});
		return $groups;
	}
}
