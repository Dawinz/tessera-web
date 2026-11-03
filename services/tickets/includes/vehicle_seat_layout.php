<?php
/**
 * Vehicle seat layout helpers - row patterns (ii-ii) + optional extra seats.
 */

if (!function_exists('tessera_vehicle_has_layout_column')) {
	function tessera_vehicle_has_layout_column(mysqli $db): bool {
		static $cached = null;
		if ($cached !== null) {
			return $cached;
		}
		$q = $db->query("SHOW COLUMNS FROM vehicle LIKE 'seat_layout_json'");
		$cached = ($q && $q->num_rows > 0);
		return $cached;
	}
}

if (!function_exists('tessera_default_seat_patterns')) {
	/**
	 * @return list<string>
	 */
	function tessera_default_seat_patterns(int $seats): array {
		$map = [
			49 => ['ii-ii', 'ii-ii', 'ii-ii', 'ii-ii', 'ii-ii', 'ii-ii', 'ii-ii', 'ii-ii', 'ii-ii', 'ii-ii', 'ii-ii', 'iiiii'],
			47 => ['ii-ii', 'ii-ii', 'ii-ii', 'ii-ii', 'ii-ii', 'ii-ii', 'ii-ii', 'ii-ii', 'ii-ii', 'ii-ii', 'iiiii'],
			57 => ['ii-ii', 'ii-ii', 'ii-ii', 'ii-ii', 'ii-ii', 'ii-ii', 'ii-ii', 'ii-ii', 'ii-ii', 'ii-ii', 'ii-ii', 'ii-ii', 'ii-ii', 'iiiii'],
			59 => ['ii-ii', 'ii-ii', 'ii-ii', 'ii-ii', 'ii-ii', 'ii-ii', 'ii-ii', 'ii-ii', 'ii-ii', 'ii-ii', 'ii-ii', 'ii-ii', 'ii-ii', 'iiiii'],
			45 => ['ii-ii', 'ii-ii', 'ii-ii', 'ii-ii', 'ii-ii', 'ii-ii', 'ii-ii', 'ii-ii', 'ii-ii', 'ii-ii', 'iiiii'],
			41 => ['ii-ii', 'ii-ii', 'ii-ii', 'ii-ii', 'ii-ii', 'ii-ii', 'ii-ii', 'ii-ii', 'ii-ii', 'iiiii'],
			37 => ['i-ii', 'i-ii', 'i-ii', 'i-ii', 'i-ii', 'i-ii', 'i-ii', 'i-ii', 'i-ii', 'i-ii', 'iiii'],
			53 => ['ii-ii', 'ii-ii', 'ii-ii', 'ii-ii', 'ii-ii', 'ii-ii', 'ii-ii', 'ii-ii', 'ii-ii', 'ii-ii', 'ii-ii', 'ii-ii', 'iiiii'],
			42 => ['i--ii', 'i--ii', 'i--ii', 'ii-ii', 'ii-ii', 'ii-ii', 'ii-ii', 'ii-ii', 'ii-ii', 'ii-ii', 'iiiii'],
		];
		if (isset($map[$seats])) {
			return $map[$seats];
		}
		return tessera_generate_seat_patterns($seats);
	}
}

if (!function_exists('tessera_generate_seat_patterns')) {
	/**
	 * Generic 2+2 coach layout for unknown capacities.
	 *
	 * @return list<string>
	 */
	function tessera_generate_seat_patterns(int $seats): array {
		if ($seats < 1) {
			return [];
		}
		$rows = [];
		$remaining = $seats;
		while ($remaining >= 4) {
			$rows[] = 'ii-ii';
			$remaining -= 4;
		}
		if ($remaining > 0) {
			$rows[] = str_repeat('i', $remaining);
		}
		return $rows;
	}
}

if (!function_exists('tessera_count_seats_in_patterns')) {
	function tessera_count_seats_in_patterns(array $patterns, array $extras = []): int {
		$count = 0;
		foreach ($patterns as $pattern) {
			$count += substr_count((string) $pattern, 'i');
		}
		return $count + count($extras);
	}
}

if (!function_exists('tessera_parse_vehicle_layout_json')) {
	/**
	 * @return array{patterns: list<string>, extras: list<string>}|null
	 */
	function tessera_parse_vehicle_layout_json(?string $json): ?array {
		if ($json === null || trim($json) === '') {
			return null;
		}
		$data = json_decode($json, true);
		if (!is_array($data)) {
			return null;
		}
		$patterns = [];
		if (!empty($data['patterns']) && is_array($data['patterns'])) {
			foreach ($data['patterns'] as $row) {
				$row = preg_replace('/[^i\-]/', '', (string) $row);
				if ($row !== '') {
					$patterns[] = $row;
				}
			}
		}
		$extras = [];
		if (!empty($data['extras']) && is_array($data['extras'])) {
			foreach ($data['extras'] as $label) {
				$label = trim((string) $label);
				if ($label !== '') {
					$extras[] = $label;
				}
			}
		}
		if ($patterns === [] && $extras === []) {
			return null;
		}
		return ['patterns' => $patterns, 'extras' => $extras];
	}
}

if (!function_exists('tessera_vehicle_layout_to_json')) {
	function tessera_vehicle_layout_to_json(array $patterns, array $extras = []): string {
		return json_encode([
			'version' => 1,
			'patterns' => array_values($patterns),
			'extras' => array_values($extras),
		], JSON_UNESCAPED_UNICODE);
	}
}

if (!function_exists('tessera_vehicle_row_patterns')) {
	/**
	 * @param array<string,mixed> $vehicleRow
	 * @return list<string>
	 */
	function tessera_vehicle_row_patterns(array $vehicleRow): array {
		$seats = (int) ($vehicleRow['seats'] ?? 0);
		if (!empty($vehicleRow['seat_layout_json'])) {
			$parsed = tessera_parse_vehicle_layout_json((string) $vehicleRow['seat_layout_json']);
			if ($parsed && $parsed['patterns'] !== []) {
				return $parsed['patterns'];
			}
		}
		return tessera_default_seat_patterns($seats);
	}
}

if (!function_exists('tessera_vehicle_layout_extras')) {
	/**
	 * @param array<string,mixed> $vehicleRow
	 * @return list<string>
	 */
	function tessera_vehicle_layout_extras(array $vehicleRow): array {
		$seats = (int) ($vehicleRow['seats'] ?? 0);
		if (!empty($vehicleRow['seat_layout_json'])) {
			$parsed = tessera_parse_vehicle_layout_json((string) $vehicleRow['seat_layout_json']);
			if ($parsed) {
				return $parsed['extras'];
			}
		}
		if (in_array($seats, [45, 59], true)) {
			return ['xt1', 'xt2'];
		}
		return [];
	}
}

if (!function_exists('tessera_patterns_to_grid')) {
	/**
	 * Convert legacy patterns to editor grid rows.
	 *
	 * @return list<list<string>>
	 */
	function tessera_patterns_to_grid(array $patterns): array {
		$grid = [];
		foreach ($patterns as $pattern) {
			$row = [];
			$len = strlen((string) $pattern);
			for ($i = 0; $i < $len; $i++) {
				$ch = $pattern[$i];
				if ($ch === 'i') {
					$row[] = 'seat';
				} elseif ($ch === '-') {
					$row[] = 'aisle';
				}
			}
			if ($row !== []) {
				$grid[] = $row;
			}
		}
		return $grid;
	}
}

if (!function_exists('tessera_grid_to_patterns')) {
	/**
	 * @param list<list<string>> $grid
	 * @return list<string>
	 */
	function tessera_grid_to_patterns(array $grid): array {
		$patterns = [];
		foreach ($grid as $row) {
			if (!is_array($row)) {
				continue;
			}
			$pattern = '';
			foreach ($row as $cell) {
				if ($cell === 'aisle') {
					$pattern .= '-';
				} elseif ($cell === 'seat') {
					$pattern .= 'i';
				}
			}
			if ($pattern !== '') {
				$patterns[] = $pattern;
			}
		}
		return $patterns;
	}
}

if (!function_exists('tessera_layout_labels_for_vehicle')) {
	/**
	 * Auto seat labels (A1, A2, ...) matching load_seat_chart numbering.
	 *
	 * @return list<string>
	 */
	function tessera_layout_labels_for_vehicle(array $patterns, array $extras = []): array {
		$labels = [];
		foreach ($extras as $extra) {
			$labels[] = (string) $extra;
		}
		$alphabet = range('A', 'Z');
		foreach ($patterns as $rowIndex => $pattern) {
			$letter = $alphabet[$rowIndex] ?? ('R' . ($rowIndex + 1));
			$seatInRow = 0;
			$len = strlen((string) $pattern);
			for ($i = 0; $i < $len; $i++) {
				if ($pattern[$i] === 'i') {
					$seatInRow++;
					$labels[] = $letter . $seatInRow;
				}
			}
		}
		return $labels;
	}
}
