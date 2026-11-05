<?php
/**
 * Operator UI strangler flags: ui.<module>.<screen> = new | legacy
 * Doc: docs/52 Operator UI feature flags.md
 */
declare(strict_types=1);

if (!function_exists('tessera_ui_flag')) {
	/**
	 * Resolve screen mode. Query override → env → local default → legacy.
	 */
	function tessera_ui_flag(string $module, string $screen): string
	{
		$module = strtolower(trim($module));
		$screen = strtolower(trim($screen));
		$screen = preg_replace('/\.php$/', '', $screen) ?? $screen;

		$qKey = 'ui_' . $module . '_' . str_replace(['.', '-'], '_', $screen);
		$qVal = isset($_GET[$qKey]) ? strtolower((string) $_GET[$qKey]) : '';
		if ($qVal === 'new' || $qVal === 'legacy') {
			return $qVal;
		}

		/* Global force: ?ui_mode=new|legacy (debugging) */
		$global = isset($_GET['ui_mode']) ? strtolower((string) $_GET['ui_mode']) : '';
		if ($global === 'new' || $global === 'legacy') {
			return $global;
		}

		$envKey = 'TESSERA_UI_' . strtoupper($module) . '_' . strtoupper(str_replace(['.', '-'], '_', $screen));
		$env = getenv($envKey);
		if ($env === false || $env === '') {
			$env = $_ENV[$envKey] ?? $_SERVER[$envKey] ?? '';
		}
		$env = strtolower((string) $env);
		if ($env === 'new' || $env === 'legacy') {
			return $env;
		}

		/*
		 * Phase 10 (G7): modernized screens default to `new` on every host.
		 * Rollback still works via ?ui_*=legacy, ?ui_mode=legacy, or TESSERA_UI_*=legacy.
		 */
		if ($module === 'tickets' && in_array($screen, [
			'schedule', 'schedule_book', 'tickets', 'manifest', 'vehicle_manager', 'home', 'my_user_daily_charge',
			'ticket_sales_summary', 'bus_sales_report', 'buss_sales_report', 'user_ticket_sales_summary', 'my_ticket_sales_summary',
			'my_ticket_info', 'deleted_book', 'changed_seat', 'charge_summary', 'sales_summary', 'user_daily_charge',
			'schedule_manage', 'register_trip', 'register_route', 'register_sub_route', 'register_route_points',
			'register_sub_route_points', 'register_location_name', 'register_currency', 'route_contact',
			'route_transit', 'traveling_time', 'trip_list', 'route_manager', 'assign_route', 'sign_up',
			'physical_booking', 'passenger_manifest', 'passenger_manifest_whole', 'passenger_manifest_whole2',
			'transfer', 'transfer_all', 'transfer_multiple', 'transfer_selected_seats',
			'passenger_seat_chart', 'vehicle_transfer_list', 'print_pos', 'print_ticket', 'printa4',
			'print_ticket_luggage', 'print_booking_search', 'print_bulk_tickets', 'print_multiple_ticket',
			'print_multiple_ticket_manifest', 'print_ticket_table',
			'generate_invoice', 'eprofile', 'luggages', 'update_luggage', 'pinned', 'pinned_few', 'view',
			'profile', 'user_profile', 'company_info', 'roles',
		], true)) {
			return 'new';
		}
		if ($module === 'cargo' && in_array($screen, [
			'register_luggage', 'all_luggages', 'store_inventory', 'vehicle_view', 'luggage_check_out', 'my_charge', 'check_luggages',
			'home', 'my_luggages', 'deleted', 'update_luggage', 'view', 'manifest_per_user', 'store_charge',
			'summary_store_charge', 'total_charge_summary', 'charges', 'vehicle_report_new', 'vehicle_report_new_edit',
			'register_currency', 'register_route', 'register_route_points', 'register_location_name',
			'assign_route', 'sign_up', 'vehicle_registration',
			'print_ticket', 'print_ticket_mulitple', 'print_manifest', 'print_store', 'print_recieved',
			'print_pos', 'print_income',
			'adaptor', 'cash_hand', 'profile', 'accounts_report', 'individual_accounts',
			'expenses', 'expenses_view', 'expenses_report', 'expenses_category', 'fuel_expenses', 'manifest_exp',
			'general_account', 'income', 'loan', 'loan_report', 'loan_type', 'loan_insert', 'loan_insert2',
			'salary', 'salaryslip', 'salary_show', 'employees', 'edit_employee', 'employee_report', 'deploy',
			'bulk_sms_com', 'whatsapp', 'whatsapp_sending', 'whatsappapi',
		], true)) {
			return 'new';
		}
		if ($module === 'accounts' && in_array($screen, [
			'manifest', 'compose_vehicle', 'daily', 'withdraw', 'report_trips', 'report_profit_loss',
			'jobcard', 'jobcard_detail', 'jobcard_entries', 'garage_particular', 'item',
			'register_routes', 'register_sub_route', 'constants', 'currency', 'currency_value',
			'route_currency', 'particular', 'particular_detail', 'particular_single_page',
			'vehicle_category', 'vehicle_category_details', 'assign_route',
			'vehicle_report', 'account_vehicles', 'account_filter', 'report_cash_flow',
			'report_revenue', 'report_expenses', 'report_payables', 'report_currency',
			'category_filter', 'category_vehicle_list', 'single_vehicle_particular',
			'vehicle_account_trip_date', 'vehicle_report_vehicle',
		], true)) {
			return 'new';
		}

		return 'legacy';
	}
}

if (!function_exists('tessera_ui_is_new')) {
	function tessera_ui_is_new(string $module, string $screen): bool
	{
		return tessera_ui_flag($module, $screen) === 'new';
	}
}
