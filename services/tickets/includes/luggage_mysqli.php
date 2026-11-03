<?php
/**
 * Cargo DB connection for Tickets luggage / parcel_info / assigned_routes.
 */
require_once __DIR__ . '/sr_env.php';
require_once __DIR__ . '/tessera_tenant.php';

if (!function_exists('luggage_mysqli')) {
	function luggage_mysqli(): mysqli {
		static $conn = null;
		if ($conn instanceof mysqli) {
			if (@$conn->ping()) {
				return $conn;
			}
			$conn = null;
		}

		$cfg = sr_db_config('cargo');
		$conn = @mysqli_connect($cfg['host'], $cfg['user'], $cfg['pass'], $cfg['name'], $cfg['port']);
		if (!$conn) {
			throw new RuntimeException('Cargo database unavailable');
		}
		mysqli_set_charset($conn, 'utf8mb4');

		return $conn;
	}
}

if (!function_exists('luggage_route_label')) {
	function luggage_route_label(array $row): string {
		if (!empty($row['route_full_name'])) {
			return (string) $row['route_full_name'];
		}
		if (!empty($row['route_name'])) {
			return (string) $row['route_name'];
		}
		$start = trim((string) ($row['start_route'] ?? ''));
		$end = trim((string) ($row['end_route'] ?? ''));
		if ($start !== '' && $end !== '') {
			return $start . ' to ' . $end;
		}

		return $start !== '' ? $start : 'Route';
	}
}

if (!function_exists('luggage_assigned_routes_for_user')) {
	/** @return list<array<string, mixed>> */
	function luggage_assigned_routes_for_user(mysqli $db, string $username): array {
		$user = mysqli_real_escape_string($db, $username);
		$hasPreference = false;
		$colQ = @mysqli_query($db, "SHOW COLUMNS FROM assigned_routes LIKE 'preference'");
		if ($colQ && mysqli_num_rows($colQ) > 0) {
			$hasPreference = true;
		}

		$where = "username='{$user}'";
		if ($hasPreference) {
			$where .= " AND preference='1'";
		}

		$sql = "SELECT * FROM assigned_routes WHERE {$where} ORDER BY id DESC";
		$result = @mysqli_query($db, $sql);
		if (!$result) {
			return [];
		}

		$rows = [];
		while ($row = mysqli_fetch_assoc($result)) {
			$rows[] = $row;
		}

		return $rows;
	}
}
