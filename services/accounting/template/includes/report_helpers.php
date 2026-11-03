<?php
// Formatting and totals helpers for the report pages (report_*.php).

if (!function_exists('rp_e')) {

	function rp_e($value) {
		return htmlspecialchars((string)$value, ENT_QUOTES, 'UTF-8');
	}

	function rp_sum($rows, $keys) {
		$totals = array();
		foreach ($keys as $key) {
			$totals[$key] = 0;
			foreach ($rows as $row) {
				$totals[$key] += isset($row[$key]) ? $row[$key] : 0;
			}
		}
		return $totals;
	}

	function rp_percent_of($part, $whole) {
		return $whole != 0 ? $part / $whole * 100 : 0;
	}

	// Accounting style: negatives in brackets.
	function rp_money($value) {
		$text = number_format(abs($value));
		return $value < 0 ? '<span class="rp-neg">(' . $text . ')</span>' : $text;
	}

	function rp_cell($value, $format) {
		switch ($format) {
			case 'money':
				return rp_money($value);
			case 'number':
				return number_format($value);
			case 'percent':
				$text = number_format($value, 1) . '%';
				return $value < 0 ? '<span class="rp-neg">' . $text . '</span>' : $text;
			case 'share':
				$width = max(0, min(100, $value));
				return '<div class="rp-share"><span class="rp-share-bar"><i style="width:' . $width . '%"></i></span>' . number_format($value, 1) . '%</div>';
			case 'badge':
				return '<span class="rp-badge is-' . rp_e($value[1]) . '">' . rp_e($value[0]) . '</span>';
			default:
				return rp_e($value);
		}
	}

	function rp_kpi_value($kpi) {
		$format = isset($kpi['format']) ? $kpi['format'] : 'money';
		if ($format == 'percent') {
			return number_format($kpi['value'], 1) . '%';
		}
		if ($format == 'number') {
			return number_format($kpi['value']);
		}
		return rp_money($kpi['value']);
	}
}
