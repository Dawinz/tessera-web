<?php
/**
 * Map legacy request keys (pre naming-corrections) onto canonical names.
 * Safe if both keys are sent; does not overwrite a non-empty canonical value.
 * See docs/32 Naming corrections.md
 */
if (!function_exists('tessera_legacy_request_aliases')) {
	function tessera_legacy_request_aliases(): void
	{
		static $done = false;
		if ($done) {
			return;
		}
		$done = true;
		$map = [
			'bus_select' => 'bus_select',
			'bus_prev' => 'bus_prev',
			'bus_name' => 'bus_name',
			'bus_shift_t' => 'bus_shift_t',
			'bus_shift_t_m' => 'bus_shift_t_m',
			'bus_shift_t_m3' => 'bus_shift_t_m3',
			'bus_route' => 'bus_route',
			'bus_route_prev' => 'bus_route_prev',
			'bus_manifest' => 'bus_manifest',
			'bus_modal_manifest' => 'bus_modal_manifest',
			'bus_ext' => 'bus_ext',
			'bus_t' => 'bus_t',
			'bus_org' => 'bus_org',
			'bus_array' => 'bus_array',
			'bus_detail' => 'bus_detail',
			'bus_detail_sum' => 'bus_detail_sum',
			'bus_total' => 'bus_total',
			'bus_total_income' => 'bus_total_income',
			'bus_seat_holder' => 'bus_seat_holder',
			'bus_name_to_change' => 'bus_name_to_change',
			'bus_category' => 'bus_category',
			'bus_particular' => 'bus_particular',
			'bus1' => 'bus1',
			'bus3' => 'bus3',
			'bus5' => 'bus5',
			'new_bus' => 'new_bus',
			'new_bus_name' => 'new_bus_name',
			'seats_bus' => 'seats_bus',
			'select_bus' => 'select_bus',
			'select_bus_modal' => 'select_bus_modal',
			'select_bus_modal2' => 'select_bus_modal2',
			'select_bus_modal_manifest' => 'select_bus_modal_manifest',
			'checked_bus' => 'checked_bus',
			'transfer_bus' => 'transfer_bus',
			'first_bus' => 'first_bus',
			'get_bus' => 'get_bus',
			'load_route_bus' => 'load_route_bus',
		];
		foreach (['_POST', '_GET', '_REQUEST'] as $sup) {
			if (!isset($GLOBALS[$sup]) || !is_array($GLOBALS[$sup])) {
				continue;
			}
			foreach ($map as $legacy => $canonical) {
				if (!array_key_exists($legacy, $GLOBALS[$sup])) {
					continue;
				}
				$has = array_key_exists($canonical, $GLOBALS[$sup]);
				$empty = !$has || $GLOBALS[$sup][$canonical] === '' || $GLOBALS[$sup][$canonical] === null;
				if ($empty) {
					$GLOBALS[$sup][$canonical] = $GLOBALS[$sup][$legacy];
				}
			}
		}
	}
	tessera_legacy_request_aliases();
}
