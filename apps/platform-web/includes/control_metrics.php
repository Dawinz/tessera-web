<?php
/**
 * Live platform metrics for Tessera Control (Wave A).
 */

function control_metrics_period(?string $period): array {
	$period = $period ?: '7d';
	$map = [
		'today' => ['label' => 'Today', 'days' => 0],
		'7d' => ['label' => 'Last 7 days', 'days' => 6],
		'30d' => ['label' => 'Last 30 days', 'days' => 29],
	];
	if (!isset($map[$period])) {
		$period = '7d';
	}
	$days = $map[$period]['days'];
	if ($days === 0) {
		$from = date('Y-m-d');
	} else {
		$from = date('Y-m-d', strtotime('-' . $days . ' days'));
	}
	$to = date('Y-m-d');
	return [
		'key' => $period,
		'label' => $map[$period]['label'],
		'from' => $from,
		'to' => $to,
	];
}

function control_cargo_mysqli(): ?mysqli {
	static $cargo = null;
	static $failed = false;
	if ($failed) return null;
	if ($cargo instanceof mysqli && !$cargo->connect_error) return $cargo;
	if (!function_exists('sr_db_config')) {
		$cands = [
			__DIR__ . '/sr_env.php',
			dirname(__DIR__, 2) . '/services/tickets/includes/sr_env.php',
			'/var/www/html/tickets/includes/sr_env.php',
		];
		foreach ($cands as $c) {
			if (is_file($c)) {
				require_once $c;
				break;
			}
		}
	}
	$cfg = sr_db_config('cargo');
	$cargo = @mysqli_init();
	if (!$cargo) { $failed = true; return null; }
	@$cargo->options(MYSQLI_OPT_CONNECT_TIMEOUT, 3);
	$ok = @$cargo->real_connect($cfg['host'], $cfg['user'], $cfg['pass'], $cfg['name'], $cfg['port']);
	if (!$ok) {
		$cargo = @mysqli_init();
		if ($cargo) {
			@$cargo->options(MYSQLI_OPT_CONNECT_TIMEOUT, 3);
			$ok = @$cargo->real_connect($cfg['host'], 'root', $cfg['pass'], $cfg['name'], $cfg['port']);
		}
	}
	if (!$cargo || $cargo->connect_error) {
		$failed = true;
		return null;
	}
	$cargo->set_charset('utf8mb4');
	return $cargo;
}

/**
 * @return array{
 *   period: array,
 *   tickets: int,
 *   ticket_revenue: float,
 *   parcels: int,
 *   payment_total: int,
 *   payment_paid: int,
 *   payment_success_pct: float|null,
 *   source: string
 * }
 */
function control_platform_metrics(?mysqli $ticketDb, bool $ticketOk, string $periodKey = '7d'): array {
	$period = control_metrics_period($periodKey);
	$out = [
		'period' => $period,
		'tickets' => 0,
		'ticket_revenue' => 0.0,
		'parcels' => 0,
		'payment_total' => 0,
		'payment_paid' => 0,
		'payment_success_pct' => null,
		'source' => 'unavailable',
	];

	$from = ($ticketOk && $ticketDb) ? $ticketDb->real_escape_string($period['from']) : $period['from'];
	$to = ($ticketOk && $ticketDb) ? $ticketDb->real_escape_string($period['to']) : $period['to'];

	if ($ticketOk && $ticketDb) {
		$q = @$ticketDb->query(
			"SELECT COUNT(*) AS c, COALESCE(SUM(cost),0) AS revenue
			 FROM booking
			 WHERE (delete_book='' OR delete_book='0' OR delete_book IS NULL)
			   AND book_date >= '{$from}' AND book_date <= '{$to}'"
		);
		if ($q && ($r = $q->fetch_assoc())) {
			$out['tickets'] = (int) $r['c'];
			$out['ticket_revenue'] = (float) $r['revenue'];
			$out['source'] = 'mysql';
		}

		$pq = @$ticketDb->query(
			"SELECT
				COUNT(*) AS total,
				SUM(CASE WHEN payment_status IN ('paid','success','completed','successful') THEN 1 ELSE 0 END) AS paid
			 FROM mobile_bookings
			 WHERE DATE(booked_at) >= '{$from}' AND DATE(booked_at) <= '{$to}'"
		);
		if ($pq && ($pr = $pq->fetch_assoc())) {
			$out['payment_total'] = (int) $pr['total'];
			$out['payment_paid'] = (int) $pr['paid'];
			if ($out['payment_total'] > 0) {
				$out['payment_success_pct'] = round(($out['payment_paid'] / $out['payment_total']) * 100, 1);
			}
		}
	}

	$cargo = control_cargo_mysqli();
	if ($cargo) {
		$from_c = $cargo->real_escape_string($period['from']);
		$to_c = $cargo->real_escape_string($period['to']);
		$cq = @$cargo->query(
			"SELECT COUNT(*) AS c FROM parcel_info
			 WHERE date_insert >= '{$from_c}' AND date_insert <= '{$to_c}'"
		);
		if ($cq && ($cr = $cq->fetch_assoc())) {
			$out['parcels'] = (int) $cr['c'];
			if ($out['source'] === 'unavailable') {
				$out['source'] = 'mysql';
			}
		}
	}

	return $out;
}

/**
 * Volume for an operator detail page — scoped by tenant_id when columns exist.
 */
function control_has_tenant_column(mysqli $db, string $table): bool {
	static $cache = [];
	$key = $table;
	if (array_key_exists($key, $cache)) {
		return $cache[$key];
	}
	$table_e = $db->real_escape_string($table);
	$q = @$db->query("SHOW COLUMNS FROM `{$table_e}` LIKE 'tenant_id'");
	$cache[$key] = ($q && $q->num_rows > 0);
	return $cache[$key];
}

function control_tenant_metrics(?mysqli $ticketDb, bool $ticketOk, array $tenant, string $periodKey = '7d'): array {
	$period = control_metrics_period($periodKey);
	$tenantId = (int) ($tenant['id'] ?? 0);
	$out = [
		'period' => $period,
		'tickets' => 0,
		'ticket_revenue' => 0.0,
		'parcels' => 0,
		'payment_total' => 0,
		'payment_paid' => 0,
		'payment_success_pct' => null,
		'source' => 'unavailable',
		'scoped' => 'pending_tenant_id',
		'note' => 'Per-tenant volume needs tenant_id on booking / parcel rows.',
	];

	if (!$ticketOk || !$ticketDb || $tenantId <= 0) {
		return $out;
	}

	$dedicated = (($tenant['db_mode'] ?? '') === 'dedicated');
	$usingDedicatedTicket = false;
	if ($dedicated && !empty($tenant['db_ticket']) && function_exists('sr_mysqli_platform')) {
		$dedicatedTicket = sr_mysqli_platform((string) $tenant['db_ticket']);
		if ($dedicatedTicket && !$dedicatedTicket->connect_error) {
			$ticketDb = $dedicatedTicket;
			$ticketOk = true;
			$usingDedicatedTicket = true;
		}
	}

	$from = $ticketDb->real_escape_string($period['from']);
	$to = $ticketDb->real_escape_string($period['to']);
	$hasBookingTenant = control_has_tenant_column($ticketDb, 'booking');
	$hasMobileTenant = control_has_tenant_column($ticketDb, 'mobile_bookings');
	$bookingWhere = $usingDedicatedTicket
		? "(delete_book='' OR delete_book='0' OR delete_book IS NULL) AND book_date >= '{$from}' AND book_date <= '{$to}'"
		: "tenant_id = {$tenantId} AND (delete_book='' OR delete_book='0' OR delete_book IS NULL) AND book_date >= '{$from}' AND book_date <= '{$to}'";
	$mobileWhere = $usingDedicatedTicket
		? "DATE(booked_at) >= '{$from}' AND DATE(booked_at) <= '{$to}'"
		: "tenant_id = {$tenantId} AND DATE(booked_at) >= '{$from}' AND DATE(booked_at) <= '{$to}'";

	if ($usingDedicatedTicket || $hasBookingTenant) {
		$q = @$ticketDb->query(
			"SELECT COUNT(*) AS c, COALESCE(SUM(cost),0) AS revenue
			 FROM booking
			 WHERE {$bookingWhere}"
		);
		if ($q && ($r = $q->fetch_assoc())) {
			$out['tickets'] = (int) $r['c'];
			$out['ticket_revenue'] = (float) $r['revenue'];
			$out['source'] = 'mysql';
			$out['scoped'] = 'tenant';
			$out['note'] = '';
		}
	}

	if ($usingDedicatedTicket || $hasMobileTenant) {
		$pq = @$ticketDb->query(
			"SELECT
				COUNT(*) AS total,
				SUM(CASE WHEN payment_status IN ('paid','success','completed','successful') THEN 1 ELSE 0 END) AS paid
			 FROM mobile_bookings
			 WHERE {$mobileWhere}"
		);
		if ($pq && ($pr = $pq->fetch_assoc())) {
			$out['payment_total'] = (int) $pr['total'];
			$out['payment_paid'] = (int) $pr['paid'];
			if ($out['payment_total'] > 0) {
				$out['payment_success_pct'] = round(($out['payment_paid'] / $out['payment_total']) * 100, 1);
			}
			$out['source'] = 'mysql';
			$out['scoped'] = 'tenant';
			$out['note'] = '';
		}
	}

	/* Parcels: dedicated operator DB, or shared cargo scoped by tenant_id. */
	$cargo = null;
	$usingDedicatedCargo = false;
	if ($dedicated && !empty($tenant['db_cargo']) && function_exists('sr_mysqli_platform')) {
		$dedicatedCargo = sr_mysqli_platform((string) $tenant['db_cargo']);
		if ($dedicatedCargo && !$dedicatedCargo->connect_error) {
			$cargo = $dedicatedCargo;
			$usingDedicatedCargo = true;
		}
	} else {
		$cargo = control_cargo_mysqli();
	}
	if ($cargo) {
		$from_c = $cargo->real_escape_string($period['from']);
		$to_c = $cargo->real_escape_string($period['to']);
		$hasParcelTenant = control_has_tenant_column($cargo, 'parcel_info');
		if ($usingDedicatedCargo || $hasParcelTenant) {
			$parcelWhere = $usingDedicatedCargo
				? "date_insert >= '{$from_c}' AND date_insert <= '{$to_c}'"
				: "tenant_id = {$tenantId} AND date_insert >= '{$from_c}' AND date_insert <= '{$to_c}'";
			$cq = @$cargo->query(
				"SELECT COUNT(*) AS c FROM parcel_info
				 WHERE {$parcelWhere}"
			);
			if ($cq && ($cr = $cq->fetch_assoc())) {
				$out['parcels'] = (int) $cr['c'];
				$out['source'] = 'mysql';
				$out['scoped'] = 'tenant';
				$out['note'] = '';
			}
		} else {
			$cq = @$cargo->query(
				"SELECT COUNT(*) AS c FROM parcel_info
				 WHERE date_insert >= '{$from_c}' AND date_insert <= '{$to_c}'"
			);
			if ($cq && ($cr = $cq->fetch_assoc())) {
				$isFlagship = ((int) ($tenant['id'] ?? 0) === 1) || (($tenant['slug'] ?? '') === 'sr-classic');
				if ($isFlagship) {
					$out['parcels'] = (int) $cr['c'];
					if ($out['note'] === '' && $out['scoped'] === 'tenant') {
						$out['note'] = 'Parcels still fleet-wide until cargo tenant_id (H2).';
					}
				}
			}
		}
	}

	if ($out['scoped'] === 'pending_tenant_id' && !$hasBookingTenant && !$hasMobileTenant) {
		$fleet = control_platform_metrics($ticketDb, $ticketOk, $periodKey);
		$isFlagship = ((int) ($tenant['id'] ?? 0) === 1) || (($tenant['slug'] ?? '') === 'sr-classic');
		if ($isFlagship) {
			$fleet['scoped'] = 'fleet';
			$fleet['note'] = 'Fleet-wide figures (apply migration 007 for per-tenant tickets).';
			return $fleet;
		}
	}

	return $out;
}

function control_format_money(float $n): string {
	if ($n >= 1000000) {
		return number_format($n / 1000000, 2) . 'M';
	}
	if ($n >= 1000) {
		return number_format($n / 1000, 1) . 'k';
	}
	return number_format($n, 0);
}

/**
 * Day buckets for chart labels (inclusive from → to).
 *
 * @return list<array{key:string,label:string}>
 */
function control_chart_day_buckets(string $periodKey): array {
	$period = control_metrics_period($periodKey);
	$start = new DateTime($period['from']);
	$end = new DateTime($period['to']);
	$end->setTime(0, 0, 0);
	$buckets = [];
	while ($start <= $end) {
		$key = $start->format('Y-m-d');
		$buckets[] = [
			'key' => $key,
			'label' => $start->format('M j'),
		];
		$start->modify('+1 day');
	}
	return $buckets;
}

/**
 * Platform volume series for overview charts.
 *
 * @return array{labels:list<string>,tickets:list<int>,revenue:list<float>,parcels:list<int>}
 */
function control_platform_chart_series(?mysqli $ticketDb, bool $ticketOk, string $periodKey = '7d'): array {
	$buckets = control_chart_day_buckets($periodKey);
	$keys = array_column($buckets, 'key');
	$labels = array_column($buckets, 'label');
	$tickets = array_fill(0, count($keys), 0);
	$revenue = array_fill(0, count($keys), 0.0);
	$parcels = array_fill(0, count($keys), 0);
	$index = array_flip($keys);

	if ($ticketOk && $ticketDb) {
		$from = $ticketDb->real_escape_string($keys[0]);
		$to = $ticketDb->real_escape_string($keys[count($keys) - 1]);
		$q = @$ticketDb->query(
			"SELECT DATE(book_date) AS d, COUNT(*) AS c, COALESCE(SUM(cost),0) AS rev
			 FROM booking
			 WHERE (delete_book='' OR delete_book='0' OR delete_book IS NULL)
			   AND book_date >= '{$from}' AND book_date <= '{$to}'
			 GROUP BY DATE(book_date)"
		);
		if ($q) {
			while ($row = $q->fetch_assoc()) {
				$d = (string) ($row['d'] ?? '');
				if ($d !== '' && isset($index[$d])) {
					$i = $index[$d];
					$tickets[$i] = (int) ($row['c'] ?? 0);
					$revenue[$i] = (float) ($row['rev'] ?? 0);
				}
			}
		}
	}

	$cargo = control_cargo_mysqli();
	if ($cargo && $keys) {
		$from_c = $cargo->real_escape_string($keys[0]);
		$to_c = $cargo->real_escape_string($keys[count($keys) - 1]);
		$cq = @$cargo->query(
			"SELECT DATE(date_insert) AS d, COUNT(*) AS c
			 FROM parcel_info
			 WHERE date_insert >= '{$from_c}' AND date_insert <= '{$to_c}'
			 GROUP BY DATE(date_insert)"
		);
		if ($cq) {
			while ($row = $cq->fetch_assoc()) {
				$d = (string) ($row['d'] ?? '');
				if ($d !== '' && isset($index[$d])) {
					$parcels[$index[$d]] = (int) ($row['c'] ?? 0);
				}
			}
		}
	}

	return compact('labels', 'tickets', 'revenue', 'parcels');
}

/**
 * Onboarding stage counts for donut chart.
 *
 * @return list<array{stage:string,label:string,count:int}>
 */
function control_onboarding_pipeline_counts(array $cases): array {
	$labels = control_stage_labels();
	$openStages = ['kickoff', 'provisioning', 'migration', 'uat', 'training', 'golive', 'hypercare'];
	$counts = array_fill_keys($openStages, 0);
	foreach ($cases as $case) {
		$stage = (string) ($case['stage'] ?? '');
		if (isset($counts[$stage])) {
			$counts[$stage]++;
		}
	}
	$out = [];
	foreach ($openStages as $stage) {
		if ($counts[$stage] <= 0) {
			continue;
		}
		$out[] = [
			'stage' => $stage,
			'label' => $labels[$stage] ?? ucfirst($stage),
			'count' => $counts[$stage],
		];
	}
	return $out;
}

/**
 * Top operators by ticket volume in the selected period.
 *
 * @return list<array{id:int,name:string,slug:string,status:string,bookings:int,revenue:float}>
 */
function control_top_tenants_by_bookings(?mysqli $ticketDb, bool $ticketOk, array $tenants, string $periodKey = '7d', int $limit = 5): array {
	$limit = max(1, min(10, $limit));
	$byId = [];
	foreach ($tenants as $t) {
		$id = (int) ($t['id'] ?? 0);
		if ($id > 0) {
			$byId[$id] = $t;
		}
	}
	if (!$ticketOk || !$ticketDb || !$byId) {
		return [];
	}

	$period = control_metrics_period($periodKey);
	$from = $ticketDb->real_escape_string($period['from']);
	$to = $ticketDb->real_escape_string($period['to']);
	$rows = [];

	if (control_has_tenant_column($ticketDb, 'booking')) {
		$q = @$ticketDb->query(
			"SELECT tenant_id, COUNT(*) AS c, COALESCE(SUM(cost),0) AS rev
			 FROM booking
			 WHERE tenant_id > 0
			   AND (delete_book='' OR delete_book='0' OR delete_book IS NULL)
			   AND book_date >= '{$from}' AND book_date <= '{$to}'
			 GROUP BY tenant_id
			 ORDER BY c DESC
			 LIMIT {$limit}"
		);
		if ($q) {
			while ($row = $q->fetch_assoc()) {
				$tid = (int) ($row['tenant_id'] ?? 0);
				if ($tid <= 0 || !isset($byId[$tid])) {
					continue;
				}
				$t = $byId[$tid];
				$rows[] = [
					'id' => $tid,
					'name' => (string) ($t['display_name'] ?? $t['slug'] ?? ('#' . $tid)),
					'slug' => (string) ($t['slug'] ?? ''),
					'status' => (string) ($t['status'] ?? ''),
					'bookings' => (int) ($row['c'] ?? 0),
					'revenue' => (float) ($row['rev'] ?? 0),
				];
			}
		}
		return $rows;
	}

	/* Legacy shared DB — show aggregate flagship only when present. */
	$q = @$ticketDb->query(
		"SELECT COUNT(*) AS c, COALESCE(SUM(cost),0) AS rev
		 FROM booking
		 WHERE (delete_book='' OR delete_book='0' OR delete_book IS NULL)
		   AND book_date >= '{$from}' AND book_date <= '{$to}'"
	);
	if ($q && ($row = $q->fetch_assoc())) {
		foreach ($byId as $tid => $t) {
			$isFlagship = $tid === 1 || (($t['slug'] ?? '') === 'sr-classic');
			if (!$isFlagship) {
				continue;
			}
			$rows[] = [
				'id' => $tid,
				'name' => (string) ($t['display_name'] ?? 'Flagship'),
				'slug' => (string) ($t['slug'] ?? ''),
				'status' => (string) ($t['status'] ?? ''),
				'bookings' => (int) ($row['c'] ?? 0),
				'revenue' => (float) ($row['rev'] ?? 0),
			];
			break;
		}
	}

	return $rows;
}
