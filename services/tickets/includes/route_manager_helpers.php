<?php
/**
 * Route manager - route keys, labels, and table guards (import model + Tessera routes).
 */

if (!function_exists('tessera_db_has_column')) {
	function tessera_db_has_column(mysqli $db, string $table, string $column): bool {
		static $cache = [];
		$table = preg_replace('/[^a-zA-Z0-9_]/', '', $table);
		$column = preg_replace('/[^a-zA-Z0-9_]/', '', $column);
		$key = $table . '.' . $column;
		if (array_key_exists($key, $cache)) {
			return $cache[$key];
		}
		$q = mysqli_query(
			$db,
			"SHOW COLUMNS FROM `{$table}` LIKE '" . mysqli_real_escape_string($db, $column) . "'"
		);
		$cache[$key] = ($q && mysqli_num_rows($q) > 0);
		return $cache[$key];
	}
}

if (!function_exists('tessera_routes_fare_column')) {
	/** Production uses fare_price (015 naming); legacy dumps use fair_price. */
	function tessera_routes_fare_column(mysqli $db): string {
		if (tessera_db_has_column($db, 'routes', 'fare_price')) {
			return 'fare_price';
		}
		if (tessera_db_has_column($db, 'routes', 'fair_price')) {
			return 'fair_price';
		}
		return 'fare_price';
	}
}

if (!function_exists('tessera_route_manager_tables_ready')) {
	function tessera_route_manager_tables_ready(mysqli $db): bool {
		static $ready = null;
		if ($ready !== null) {
			return $ready;
		}
		$q = mysqli_query($db, "SHOW TABLES LIKE 'route_schedules'");
		$ready = ($q && mysqli_num_rows($q) > 0);
		return $ready;
	}
}

if (!function_exists('tessera_route_manager_key')) {
	/** Stable varchar key used in route_schedules / route_fair (import style). */
	function tessera_route_manager_key(array $route): string {
		$acronym = trim((string) ($route['route_acronym'] ?? ''));
		if ($acronym !== '') {
			return $acronym;
		}
		$routeName = trim((string) ($route['route_name'] ?? ''));
		if ($routeName !== '') {
			$slug = preg_replace('/[^A-Za-z0-9]+/', '', $routeName);
			if ($slug !== '') {
				return strtoupper(substr($slug, 0, 12));
			}
		}
		return 'R' . (int) ($route['id'] ?? 0);
	}
}

if (!function_exists('tessera_route_manager_label')) {
	function tessera_route_manager_label(array $route): string {
		$fullname = trim((string) ($route['fullname'] ?? ''));
		if ($fullname !== '') {
			return $fullname;
		}
		$routeName = trim((string) ($route['route_name'] ?? ''));
		if ($routeName !== '') {
			return $routeName;
		}
		$start = trim((string) ($route['start_route'] ?? ''));
		$end = trim((string) ($route['end_route'] ?? ''));
		if ($start !== '' || $end !== '') {
			return trim($start . ' to ' . $end);
		}
		return 'Route #' . (int) ($route['id'] ?? 0);
	}
}

if (!function_exists('tessera_route_manager_path')) {
	function tessera_route_manager_path(array $route): string {
		$start = trim((string) ($route['start_route'] ?? ''));
		$end = trim((string) ($route['end_route'] ?? ''));
		if ($start === '' && $end === '') {
			return '';
		}
		return $start . ' -> ' . $end;
	}
}

if (!function_exists('tessera_route_manager_format_time')) {
	function tessera_route_manager_format_time(?string $time): string {
		if ($time === null || $time === '') {
			return ' - ';
		}
		$ts = strtotime($time);
		if ($ts === false) {
			return $time;
		}
		return date('g:i A', $ts);
	}
}

if (!function_exists('tessera_route_manager_week_bounds')) {
	/** @return array{0: DateTimeImmutable, 1: DateTimeImmutable} Monday - Sunday of week containing $ref */
	function tessera_route_manager_week_bounds(?string $ref = null): array {
		$refDate = $ref ? new DateTimeImmutable($ref) : new DateTimeImmutable('today');
		$monday = $refDate->modify('monday this week');
		$sunday = $monday->modify('+6 days');
		return [$monday, $sunday];
	}
}

if (!function_exists('tessera_route_manager_day_codes')) {
	function tessera_route_manager_day_codes(): array {
		return ['Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat', 'Sun'];
	}
}
