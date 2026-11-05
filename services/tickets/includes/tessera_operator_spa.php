<?php
/**
 * Platform operator SPA - capture legacy module pages as JSON fragments.
 */
if (!function_exists('tessera_operator_spa_enabled')) {
	function tessera_operator_spa_enabled(): bool {
		$flag = getenv('TESSERA_OPERATOR_SHELL');
		if ($flag === '0' || $flag === 'false') {
			return false;
		}
		return true;
	}

	/** Map legacy redirector / alias entry pages to real shell pages. */
	function tessera_operator_spa_resolve_page(string $module, string $page): string {
		$page = basename(parse_url($page, PHP_URL_PATH) ?: $page);
		$module = strtolower($module);
		$aliases = [
			'tickets' => [
				'routes.php' => 'schedule.php',
				'routs.php' => 'schedule.php',
				'register_bus.php' => 'vehicle_manager.php',
				'register_buss.php' => 'vehicle_manager.php',
				'design_seat_chart.php' => 'vehicle_manager.php',
				'seat_selection.php' => 'schedule_book.php',
				'booking.php' => 'schedule_book.php',
				'manifest_agent.php' => 'manifest.php',
				'passanger_manifest.php' => 'passenger_manifest.php',
				'manifest_passenger.php' => 'passenger_manifest.php',
				'passanger_manifest_whole.php' => 'passenger_manifest_whole.php',
				'passanger_manifest_whole2.php' => 'passenger_manifest_whole2.php',
				'passanger_seat_chart.php' => 'passenger_seat_chart.php',
				'transfer_muliple.php' => 'transfer_multiple.php',
			],
			'cargo' => [
				'register_route_points.php' => 'register_route.php',
				'register_location_name.php' => 'register_route.php',
			],
			'accounts' => [
				'register_routs.php' => 'Register_routes.php',
				'register_routes.php' => 'Register_routes.php',
				'register_sub_route.php' => 'Register_sub_route.php',
				'job_card.php' => 'jobcard.php',
				'jobcard_entries_2.php' => 'jobcard_entries.php',
				'jobcard_entires.php' => 'jobcard_entries.php',
				'jobcard_entires_2.php' => 'jobcard_entries.php',
				'particular_signle_page.php' => 'particular_single_page.php',
				'account_vehciles.php' => 'account_vehicles.php',
				'vehcile_account_trip_date.php' => 'vehicle_account_trip_date.php',
				'compose_vehicle2.php' => 'compose_vehicle.php',
				'manifest_view.php' => 'compose_vehicle.php',
				'cash.php' => 'withdraw.php',
			],
		];
		return $aliases[$module][strtolower($page)] ?? $page;
	}

	function tessera_operator_shell_url(string $module, string $page = ''): string {
		$defaults = [
			'tickets' => 'home.php',
			'cargo' => 'home.php',
			'accounts' => 'manifest.php',
		];
		$module = strtolower($module);
		if ($page === '' || $page === '/') {
			$page = $defaults[$module] ?? 'home.php';
		}
		$page = tessera_operator_spa_resolve_page($module, $page);
		$base = function_exists('sr_public_base_url') ? sr_public_base_url() : '';
		return rtrim($base, '/') . '/operator/app.php?module=' . rawurlencode($module) . '&page=' . rawurlencode($page);
	}

	/** Redirect GET requests for full legacy pages into the operator shell. */
	function tessera_operator_shell_redirect_legacy_get(): void {
		if (!tessera_operator_spa_enabled()) {
			return;
		}
		if (($_SERVER['REQUEST_METHOD'] ?? 'GET') !== 'GET') {
			return;
		}
		if (!empty($_GET['full']) && (string) $_GET['full'] === '1') {
			return;
		}
		if (!empty($GLOBALS['tessera_operator_spa_embed']) || !empty($GLOBALS['tessera_accounts_spa_embed'])) {
			return;
		}
		if (!empty($_SERVER['HTTP_X_TESSERA_OPERATOR_SPA']) || !empty($_SERVER['HTTP_X_TESSERA_ACCOUNTS_SPA'])) {
			return;
		}
		// AJAX / fragment loads (route manager views, DataTables, etc.) must not enter the shell.
		$xhr = strtolower((string) ($_SERVER['HTTP_X_REQUESTED_WITH'] ?? ''));
		if ($xhr === 'xmlhttprequest') {
			return;
		}
		$secDest = strtolower((string) ($_SERVER['HTTP_SEC_FETCH_DEST'] ?? ''));
		if ($secDest !== '' && $secDest !== 'document') {
			return;
		}
		$script = str_replace('\\', '/', (string) ($_SERVER['SCRIPT_NAME'] ?? ''));
		if (str_contains($script, '/operator/')) {
			return;
		}
		// Only top-level module entry pages - never nested AJAX views under /modules/.
		// Accounts is rewritten to /accounts/template/foo.php (see services/accounting/.htaccess).
		$isTopLevel = (bool) preg_match('#/(tickets|cargo)/[^/]+\.php$#i', $script)
			|| (bool) preg_match('#/accounts/(?:template/)?[^/]+\.php$#i', $script);
		if (!$isTopLevel) {
			return;
		}
		$base = basename($script);
		$baseLower = strtolower($base);
		$skip = [
			'index.php', 'spa.php', 'app.php', 'log_out.php', 'auth.php',
		];
		if (in_array($baseLower, $skip, true)) {
			return;
		}
		if (str_starts_with($baseLower, 'fetch_') || str_starts_with($baseLower, 'save_')
			|| str_starts_with($baseLower, 'update_') || str_starts_with($baseLower, 'delete_')
			|| str_starts_with($baseLower, 'insert_') || str_starts_with($baseLower, 'load_')) {
			return;
		}
		$module = tessera_operator_spa_module_from_script($script);
		if ($module === null) {
			return;
		}
		$page = tessera_operator_spa_resolve_page($module, $base);
		$query = $_GET;
		unset($query['full']);
		$url = tessera_operator_shell_url($module, $page);
		if ($query !== []) {
			$url .= '&' . http_build_query($query);
		}
		header('Location: ' . $url, true, 302);
		exit;
	}

	function tessera_operator_spa_module_from_script(string $script): ?string {
		$script = str_replace('\\', '/', $script);
		if (str_contains($script, '/tickets/')) {
			return 'tickets';
		}
		if (str_contains($script, '/cargo/')) {
			return 'cargo';
		}
		if (str_contains($script, '/accounts/')) {
			return 'accounts';
		}
		return null;
	}

	/**
	 * Resolve Accounts PHP root for monorepo + deployed layouts.
	 * Deployed image: /accounts/template (COPY services/accounting → /accounts).
	 * Monorepo: services/accounting/template.
	 */
	function tessera_operator_spa_accounts_root(): ?string {
		$candidates = [];
		if (function_exists('tessera_operator_module_roots')) {
			$roots = tessera_operator_module_roots();
			if (!empty($roots['accounts'])) {
				$candidates[] = $roots['accounts'] . '/template';
				$candidates[] = $roots['accounts'];
			}
		}
		$html = dirname(__DIR__, 2);
		$candidates[] = $html . '/accounts/template';
		$candidates[] = $html . '/accounts';
		$candidates[] = $html . '/accounting/template';
		$repoServices = dirname(__DIR__, 3) . '/services/accounting/template';
		$candidates[] = $repoServices;
		foreach ($candidates as $c) {
			$r = realpath($c);
			if ($r !== false && is_file($r . DIRECTORY_SEPARATOR . 'manifest.php')) {
				return $r;
			}
		}
		return null;
	}

	/** @return array<string,array{root:string,bootstrap?:callable():void}> */
	function tessera_operator_spa_modules(): array {
		$ticketsRoot = realpath(dirname(__DIR__));
		$cargoRoot = realpath(dirname(__DIR__, 2) . '/cargo');
		$accountsRoot = tessera_operator_spa_accounts_root();
		$out = [];
		if ($ticketsRoot) {
			$out['tickets'] = ['root' => $ticketsRoot];
		}
		if ($cargoRoot) {
			$out['cargo'] = ['root' => $cargoRoot];
		}
		if ($accountsRoot) {
			$authBootstrap = $accountsRoot . '/includes/auth_bootstrap.php';
			$out['accounts'] = [
				'root' => $accountsRoot,
				'bootstrap' => static function () use ($authBootstrap): void {
					global $db, $username, $level, $firstname, $surname, $image, $footer, $fullname, $fullname_user, $url_time, $system_user, $firstStringCharacter, $shell_v;
					if (!isset($db) || !($db instanceof mysqli)) {
						if (is_file($authBootstrap)) {
							require_once $authBootstrap;
						}
					}
				},
			];
		}
		return $out;
	}

	function tessera_operator_spa_default_page(string $module): string {
		$defaults = [
			'tickets' => 'home.php',
			'cargo' => 'home.php',
			'accounts' => 'manifest.php',
		];
		return $defaults[strtolower($module)] ?? 'home.php';
	}

	function tessera_operator_spa_allowed(string $module, string $page): bool {
		$module = strtolower($module);
		$page = tessera_operator_spa_resolve_page($module, $page);
		if (!preg_match('/^[A-Za-z0-9_.-]+\.php$/', $page)) {
			return false;
		}
		$mods = tessera_operator_spa_modules();
		if (!isset($mods[$module])) {
			return false;
		}
		$root = $mods[$module]['root'];
		$file = realpath($root . DIRECTORY_SEPARATOR . $page);
		return $file !== false && str_starts_with($file, $root . DIRECTORY_SEPARATOR) && is_file($file);
	}

	function tessera_operator_spa_is_request(): bool {
		if (!empty($_SERVER['HTTP_X_TESSERA_OPERATOR_SPA'])) {
			return true;
		}
		return isset($_GET['spa']) && (string) $_GET['spa'] === '1';
	}

	/**
	 * Capture a modern /operator/ui screen when ui flag is new.
	 *
	 * @param array<string,string|int> $spaQuery
	 * @return array{module:string,path:string,title:string,html:string,styles:string,topnav_html:string,scripts:array<int,array{src?:string,inline?:string}>,main_page:string}|null
	 */
	function tessera_operator_spa_capture_modern(string $module, string $screenKey, string $page, array $spaQuery = []): ?array
	{
		$map = [
			'tickets' => [
				'schedule' => 'ui/tickets/schedule.php',
				'schedule_book' => 'ui/tickets/schedule_book.php',
				'tickets' => 'ui/tickets/tickets.php',
				'manifest' => 'ui/tickets/manifest.php',
				'vehicle_manager' => 'ui/tickets/vehicle_manager.php',
				'home' => 'ui/tickets/home.php',
				'my_user_daily_charge' => 'ui/tickets/my_user_daily_charge.php',
				'ticket_sales_summary' => 'ui/tickets/ticket_sales_summary.php',
				'bus_sales_report' => 'ui/tickets/bus_sales_report.php',
				'buss_sales_report' => 'ui/tickets/bus_sales_report.php',
				'user_ticket_sales_summary' => 'ui/tickets/user_ticket_sales_summary.php',
				'my_ticket_sales_summary' => 'ui/tickets/my_ticket_sales_summary.php',
				'my_ticket_info' => 'ui/tickets/my_ticket_info.php',
				'deleted_book' => 'ui/tickets/deleted_book.php',
				'changed_seat' => 'ui/tickets/changed_seat.php',
				'charge_summary' => 'ui/tickets/charge_summary.php',
				'sales_summary' => 'ui/tickets/sales_summary.php',
				'user_daily_charge' => 'ui/tickets/user_daily_charge.php',
				'schedule_manage' => 'ui/tickets/schedule_manage.php',
				'register_trip' => 'ui/tickets/register_trip.php',
				'register_route' => 'ui/tickets/register_route.php',
				'register_sub_route' => 'ui/tickets/register_sub_route.php',
				'register_route_points' => 'ui/tickets/register_route_points.php',
				'register_sub_route_points' => 'ui/tickets/register_sub_route_points.php',
				'register_location_name' => 'ui/tickets/register_location_name.php',
				'register_currency' => 'ui/tickets/register_currency.php',
				'route_contact' => 'ui/tickets/route_contact.php',
				'route_transit' => 'ui/tickets/route_transit.php',
				'traveling_time' => 'ui/tickets/traveling_time.php',
				'trip_list' => 'ui/tickets/trip_list.php',
				'route_manager' => 'ui/tickets/route_manager.php',
				'assign_route' => 'ui/tickets/assign_route.php',
				'sign_up' => 'ui/tickets/sign_up.php',
				'physical_booking' => 'ui/tickets/physical_booking.php',
				'passenger_manifest' => 'ui/tickets/passenger_manifest.php',
				'passanger_manifest' => 'ui/tickets/passenger_manifest.php',
				'manifest_passenger' => 'ui/tickets/passenger_manifest.php',
				'passenger_manifest_whole' => 'ui/tickets/passenger_manifest_whole.php',
				'passanger_manifest_whole' => 'ui/tickets/passenger_manifest_whole.php',
				'passenger_manifest_whole2' => 'ui/tickets/passenger_manifest_whole2.php',
				'passanger_manifest_whole2' => 'ui/tickets/passenger_manifest_whole2.php',
				'transfer' => 'ui/tickets/transfer.php',
				'transfer_all' => 'ui/tickets/transfer_all.php',
				'transfer_multiple' => 'ui/tickets/transfer_multiple.php',
				'transfer_muliple' => 'ui/tickets/transfer_multiple.php',
				'transfer_selected_seats' => 'ui/tickets/transfer_selected_seats.php',
				'passenger_seat_chart' => 'ui/tickets/passenger_seat_chart.php',
				'passanger_seat_chart' => 'ui/tickets/passenger_seat_chart.php',
				'vehicle_transfer_list' => 'ui/tickets/vehicle_transfer_list.php',
				'print_pos' => 'ui/tickets/print_pos.php',
				'print_ticket' => 'ui/tickets/print_ticket.php',
				'printa4' => 'ui/tickets/printa4.php',
				'print_ticket_luggage' => 'ui/tickets/print_ticket_luggage.php',
				'print_booking_search' => 'ui/tickets/print_booking_search.php',
				'print_bulk_tickets' => 'ui/tickets/print_bulk_tickets.php',
				'print_multiple_ticket' => 'ui/tickets/print_multiple_ticket.php',
				'print_multiple_ticket_manifest' => 'ui/tickets/print_multiple_ticket_manifest.php',
				'print_ticket_table' => 'ui/tickets/print_ticket_table.php',
				'generate_invoice' => 'ui/tickets/generate_invoice.php',
				'eprofile' => 'ui/tickets/eprofile.php',
				'luggages' => 'ui/tickets/luggages.php',
				'update_luggage' => 'ui/tickets/update_luggage.php',
				'pinned' => 'ui/tickets/pinned.php',
				'pinned_few' => 'ui/tickets/pinned_few.php',
				'view' => 'ui/tickets/view.php',
				'profile' => 'ui/tickets/settings_hub.php',
				'user_profile' => 'ui/tickets/settings_hub.php',
				'company_info' => 'ui/tickets/settings_hub.php',
				'roles' => 'ui/tickets/settings_hub.php',
			],
			'cargo' => [
				'register_luggage' => 'ui/cargo/register_luggage.php',
				'all_luggages' => 'ui/cargo/all_luggages.php',
				'store_inventory' => 'ui/cargo/store_inventory.php',
				'vehicle_view' => 'ui/cargo/vehicle_view.php',
				'luggage_check_out' => 'ui/cargo/luggage_check_out.php',
				'my_charge' => 'ui/cargo/my_charge.php',
				'check_luggages' => 'ui/cargo/check_luggages.php',
				'home' => 'ui/cargo/home.php',
				'my_luggages' => 'ui/cargo/my_luggages.php',
				'deleted' => 'ui/cargo/deleted.php',
				'update_luggage' => 'ui/cargo/update_luggage.php',
				'view' => 'ui/cargo/view.php',
				'manifest_per_user' => 'ui/cargo/manifest_per_user.php',
				'store_charge' => 'ui/cargo/store_charge.php',
				'summary_store_charge' => 'ui/cargo/summary_store_charge.php',
				'total_charge_summary' => 'ui/cargo/total_charge_summary.php',
				'charges' => 'ui/cargo/charges.php',
				'vehicle_report_new' => 'ui/cargo/vehicle_report_new.php',
				'vehicle_report_new_edit' => 'ui/cargo/vehicle_report_new_edit.php',
				'register_currency' => 'ui/cargo/register_currency.php',
				'register_route' => 'ui/cargo/register_route.php',
				'register_route_points' => 'ui/cargo/register_route.php',
				'register_location_name' => 'ui/cargo/register_route.php',
				'assign_route' => 'ui/cargo/assign_route.php',
				'sign_up' => 'ui/cargo/sign_up.php',
				'vehicle_registration' => 'ui/cargo/vehicle_registration.php',
				'print_ticket' => 'ui/cargo/print_ticket.php',
				'print_ticket_mulitple' => 'ui/cargo/print_ticket_mulitple.php',
				'print_manifest' => 'ui/cargo/print_manifest.php',
				'print_store' => 'ui/cargo/print_store.php',
				'print_recieved' => 'ui/cargo/print_recieved.php',
				'print_pos' => 'ui/cargo/print_pos.php',
				'print_income' => 'ui/cargo/print_income.php',
				'adaptor' => 'ui/cargo/adaptor.php',
				'cash_hand' => 'ui/cargo/cash_hand.php',
				'profile' => 'ui/cargo/profile.php',
				'accounts_report' => 'ui/cargo/legacy_moved.php',
				'Individual_accounts' => 'ui/cargo/legacy_moved.php',
				'expenses' => 'ui/cargo/legacy_moved.php',
				'expenses_view' => 'ui/cargo/legacy_moved.php',
				'expenses_report' => 'ui/cargo/legacy_moved.php',
				'expenses_category' => 'ui/cargo/legacy_moved.php',
				'fuel_expenses' => 'ui/cargo/legacy_moved.php',
				'manifest_exp' => 'ui/cargo/legacy_moved.php',
				'general_account' => 'ui/cargo/legacy_moved.php',
				'income' => 'ui/cargo/legacy_moved.php',
				'loan' => 'ui/cargo/legacy_moved.php',
				'loan_report' => 'ui/cargo/legacy_moved.php',
				'loan_type' => 'ui/cargo/legacy_moved.php',
				'loan_insert' => 'ui/cargo/legacy_moved.php',
				'loan_insert2' => 'ui/cargo/legacy_moved.php',
				'salary' => 'ui/cargo/legacy_moved.php',
				'salaryslip' => 'ui/cargo/legacy_moved.php',
				'salary_show' => 'ui/cargo/legacy_moved.php',
				'employees' => 'ui/cargo/legacy_moved.php',
				'edit_employee' => 'ui/cargo/legacy_moved.php',
				'employee_report' => 'ui/cargo/legacy_moved.php',
				'deploy' => 'ui/cargo/legacy_moved.php',
				'bulk_sms_com' => 'ui/cargo/legacy_moved.php',
				'whatsapp' => 'ui/cargo/legacy_moved.php',
				'whatsapp_sending' => 'ui/cargo/legacy_moved.php',
				'WhatsappAPI' => 'ui/cargo/legacy_moved.php',
			],
			'accounts' => [
				'manifest' => 'ui/accounts/manifest.php',
				'compose_vehicle' => 'ui/accounts/compose_vehicle.php',
				'daily' => 'ui/accounts/daily.php',
				'withdraw' => 'ui/accounts/withdraw.php',
				'report_trips' => 'ui/accounts/report_trips.php',
				'report_profit_loss' => 'ui/accounts/report_profit_loss.php',
				'jobcard' => 'ui/accounts/jobcard.php',
				'job_card' => 'ui/accounts/jobcard.php',
				'jobcard_detail' => 'ui/accounts/jobcard_detail.php',
				'jobcard_entries' => 'ui/accounts/jobcard_entries.php',
				'jobcard_entries_2' => 'ui/accounts/jobcard_entries.php',
				'jobcard_entires' => 'ui/accounts/jobcard_entries.php',
				'jobcard_entires_2' => 'ui/accounts/jobcard_entries.php',
				'garage_particular' => 'ui/accounts/garage_particular.php',
				'item' => 'ui/accounts/item.php',
				'Register_routes' => 'ui/accounts/Register_routes.php',
				'Register_sub_route' => 'ui/accounts/Register_sub_route.php',
				'constants' => 'ui/accounts/constants.php',
				'currency' => 'ui/accounts/currency.php',
				'currency_value' => 'ui/accounts/currency_value.php',
				'route_currency' => 'ui/accounts/route_currency.php',
				'particular' => 'ui/accounts/particular.php',
				'particular_detail' => 'ui/accounts/particular_detail.php',
				'particular_single_page' => 'ui/accounts/particular_single_page.php',
				'particular_signle_page' => 'ui/accounts/particular_single_page.php',
				'vehicle_category' => 'ui/accounts/vehicle_category.php',
				'vehicle_category_details' => 'ui/accounts/vehicle_category_details.php',
				'assign_route' => 'ui/accounts/assign_route.php',
				'vehicle_report' => 'ui/accounts/vehicle_report.php',
				'account_vehicles' => 'ui/accounts/account_vehicles.php',
				'account_filter' => 'ui/accounts/account_filter.php',
				'report_cash_flow' => 'ui/accounts/report_cash_flow.php',
				'report_revenue' => 'ui/accounts/report_revenue.php',
				'report_expenses' => 'ui/accounts/report_expenses.php',
				'report_payables' => 'ui/accounts/report_payables.php',
				'report_currency' => 'ui/accounts/report_currency.php',
				'category_filter' => 'ui/accounts/category_filter.php',
				'category_vehicle_list' => 'ui/accounts/category_vehicle_list.php',
				'single_vehicle_particular' => 'ui/accounts/single_vehicle_particular.php',
				'vehicle_account_trip_date' => 'ui/accounts/vehicle_account_trip_date.php',
				'vehcile_account_trip_date' => 'ui/accounts/vehicle_account_trip_date.php',
				'vehicle_report_vehicle' => 'ui/accounts/vehicle_report_vehicle.php',
			],
		];
		$rel = $map[$module][$screenKey] ?? null;
		if ($rel === null) {
			return null;
		}

		$doc = rtrim((string) ($_SERVER['DOCUMENT_ROOT'] ?? ''), "/\\");
		$shellRoots = array_filter([
			$doc !== '' ? $doc . '/operator' : null,
			dirname(__DIR__, 3) . '/apps/operator-shell',
			dirname(__DIR__, 2) . '/apps/operator-shell',
			'/var/www/html/operator',
		]);
		$file = null;
		foreach ($shellRoots as $root) {
			$candidate = rtrim((string) $root, '/') . '/' . $rel;
			if (is_file($candidate)) {
				$file = $candidate;
				break;
			}
		}
		if ($file === null) {
			return null;
		}

		$backupGet = $_GET;
		foreach ($spaQuery as $k => $v) {
			if (is_string($k) && (is_string($v) || is_numeric($v))) {
				$_GET[$k] = (string) $v;
			}
		}

		$GLOBALS['tessera_operator_spa_embed'] = true;
		$GLOBALS['tessera_leftover_screen'] = $screenKey;
		ob_start();
		try {
			include $file;
			$html = (string) ob_get_clean();
		} catch (Throwable $e) {
			if (ob_get_level() > 0) {
				ob_end_clean();
			}
			$_GET = $backupGet;
			unset($GLOBALS['tessera_operator_spa_embed'], $GLOBALS['tessera_leftover_screen']);
			throw $e;
		}
		$_GET = $backupGet;
		unset($GLOBALS['tessera_operator_spa_embed'], $GLOBALS['tessera_leftover_screen']);

		$parsed = tessera_operator_spa_parse_html($html, $module, $page, $spaQuery);
		$parsed['module'] = $module;
		$parsed['ui'] = 'new';
		$parsed['ui_slice'] = strtoupper(substr($module, 0, 1)) . '-P0-' . $screenKey;
		return $parsed;
	}

	/**
	 * @param array<string,string|int> $spaQuery
	 * @return array{module:string,path:string,title:string,html:string,styles:string,topnav_html:string,scripts:array<int,array{src?:string,inline?:string}>,main_page:string}
	 */
	function tessera_operator_spa_capture(string $module, string $page, array $spaQuery = []): array {
		$module = strtolower($module);
		$page = tessera_operator_spa_resolve_page($module, $page);
		if (!tessera_operator_spa_allowed($module, $page)) {
			throw new InvalidArgumentException('Page not allowed for SPA load.');
		}

		/* Modern strangler screens (ui.* flags) — see docs/52 */
		require_once __DIR__ . '/tessera_ui_flags.php';
		$screenKey = preg_replace('/\.php$/', '', $page) ?? $page;
		if (tessera_ui_is_new($module, $screenKey)) {
			$modern = tessera_operator_spa_capture_modern($module, $screenKey, $page, $spaQuery);
			if ($modern !== null) {
				return $modern;
			}
		}

		$mods = tessera_operator_spa_modules();
		$root = $mods[$module]['root'];
		$pageFile = $page;

		$backupGet = $_GET;
		foreach ($spaQuery as $k => $v) {
			if (is_string($k) && (is_string($v) || is_numeric($v))) {
				$_GET[$k] = (string) $v;
			}
		}

		$GLOBALS['tessera_operator_spa_embed'] = true;
		$GLOBALS['tessera_accounts_spa_embed'] = true;

		if (isset($mods[$module]['bootstrap']) && is_callable($mods[$module]['bootstrap'])) {
			($mods[$module]['bootstrap'])();
		}

		$cwd = getcwd();
		chdir($root);
		ob_start();
		try {
			include $root . DIRECTORY_SEPARATOR . $pageFile;
			$html = (string) ob_get_clean();
		} catch (Throwable $e) {
			// Never leak page HTML into the SPA JSON response when a screen fatals mid-render.
			if (ob_get_level() > 0) {
				ob_end_clean();
			}
			if ($cwd !== false) {
				@chdir($cwd);
			}
			$_GET = $backupGet;
			unset($GLOBALS['tessera_operator_spa_embed'], $GLOBALS['tessera_accounts_spa_embed']);
			throw $e;
		} finally {
			if ($cwd !== false) {
				@chdir($cwd);
			}
		}
		$html = tessera_operator_spa_relocate_trailing_scripts($html);

		$_GET = $backupGet;
		unset($GLOBALS['tessera_operator_spa_embed'], $GLOBALS['tessera_accounts_spa_embed']);

		$parsed = tessera_operator_spa_parse_html($html, $module, $pageFile, $spaQuery);
		$parsed['module'] = $module;
		return $parsed;
	}

	/** @param array<string,string> $query */
	function tessera_operator_spa_parse_html(string $html, string $module, string $path, array $query = []): array {
		// Extract scripts from raw HTML first - libxml corrupts inline JS that embeds HTML strings.
		$scripts = tessera_operator_spa_extract_scripts_from_html($html, $module);

		$dom = new DOMDocument();
		$prev = libxml_use_internal_errors(true);
		$dom->loadHTML('<?xml encoding="utf-8" ?>' . $html, LIBXML_NOWARNING | LIBXML_NOERROR);
		libxml_clear_errors();
		libxml_use_internal_errors($prev);

		$title = ucfirst($module);
		$titles = $dom->getElementsByTagName('title');
		if ($titles->length > 0) {
			$title = trim($titles->item(0)->textContent ?? $title);
		}

		$styles = '';
		$head = $dom->getElementsByTagName('head')->item(0);
		if ($head) {
			foreach ($head->childNodes as $node) {
				if ($node->nodeName === 'style') {
					$styles .= $dom->saveHTML($node);
				} elseif ($node->nodeName === 'link') {
					/** @var DOMElement $node */
					$rel = strtolower($node->getAttribute('rel') ?: '');
					$href = $node->getAttribute('href') ?: '';
					if ($rel === 'stylesheet' && $href !== '') {
						$node->setAttribute('href', tessera_operator_spa_normalize_asset_url($href, $module));
						if (tessera_operator_spa_stylesheet_allowed($module, $href)) {
							$styles .= $dom->saveHTML($node);
						}
					}
				}
			}
		}

		$topnavHtml = '';
		$topnav = $dom->getElementById('topnav-menu-content');
		if ($topnav instanceof DOMElement) {
			$topnavHtml = tessera_operator_spa_inner_html($dom, $topnav);
		}

		$body = $dom->getElementsByTagName('body')->item(0);
		$fragment = '';
		$ignoredScripts = [];
		if ($body) {
			foreach ($body->childNodes as $node) {
				if ($node->nodeType !== XML_ELEMENT_NODE) {
					continue;
				}
				/** @var DOMElement $node */
				if ($node->nodeName === 'header' && $node->getAttribute('id') === 'page-topbar') {
					continue;
				}
				if ($node->nodeName === 'script') {
					continue;
				}
				if ($node->nodeName === 'div' && str_contains($node->getAttribute('class') ?: '', 'main-content')) {
					$fragment .= tessera_operator_spa_inner_html($dom, $node, $ignoredScripts, $module);
					continue;
				}
				if ($node->nodeName === 'div' && $node->getAttribute('id') === 'layout-wrapper') {
					$fragment .= tessera_operator_spa_extract_layout_wrapper($dom, $node, $ignoredScripts, $module);
					continue;
				}
				$fragment .= $dom->saveHTML($node);
			}
		}

		$mainPage = isset($query['main_page']) ? (string) $query['main_page'] : '';
		if ($mainPage === '') {
			$mainPage = tessera_operator_spa_guess_main_page($module, $path);
		}

		$fragment = tessera_operator_spa_rewrite_relative_hrefs($fragment, $module);
		$topnavHtml = tessera_operator_spa_rewrite_relative_hrefs($topnavHtml, $module);
		$styles = tessera_operator_spa_fix_protocol_relative_assets($styles);
		$fragment = tessera_operator_spa_fix_protocol_relative_assets($fragment);
		$topnavHtml = tessera_operator_spa_fix_protocol_relative_assets($topnavHtml);
		$fragment = tessera_operator_spa_strip_script_tags($fragment);
		// Page CSS is written as `body.manifest-page`. The shell keeps its own body,
		// so copy that class onto a wrapper or the dark-theme rules never match.
		if ($body instanceof DOMElement && $fragment !== '') {
			$pageClass = trim($body->getAttribute('class') ?: '');
			if ($pageClass !== '') {
				$fragment = '<div class="' . htmlspecialchars($pageClass, ENT_QUOTES, 'UTF-8') . '">' . $fragment . '</div>';
			}
		}

		return [
			'path' => $path,
			'title' => $title,
			'html' => $fragment,
			'styles' => $styles,
			'topnav_html' => $topnavHtml,
			'scripts' => tessera_operator_spa_unique_scripts($scripts),
			'main_page' => $mainPage,
		];
	}

	/**
	 * Regex-extract body scripts in document order (libxml-safe for HTML-in-JS strings).
	 *
	 * @return array<int,array{src?:string,inline?:string}>
	 */
	function tessera_operator_spa_extract_scripts_from_html(string $html, string $module): array {
		$scripts = [];
		$bodyHtml = $html;
		if (preg_match('/<body\b[^>]*>([\s\S]*)<\/body>/i', $html, $m)) {
			$bodyHtml = $m[1];
		}
		if (!preg_match_all('/<script\b([^>]*)>([\s\S]*?)<\/script>/i', $bodyHtml, $matches, PREG_SET_ORDER)) {
			return $scripts;
		}
		foreach ($matches as $match) {
			$attrs = $match[1] ?? '';
			$src = '';
			if (preg_match('/\bsrc\s*=\s*([\'"])(.*?)\1/i', $attrs, $sm)) {
				$src = html_entity_decode($sm[2], ENT_QUOTES | ENT_HTML5, 'UTF-8');
			}
			if ($src !== '') {
				$src = tessera_operator_spa_normalize_script_src($src, $module);
				if (tessera_operator_spa_is_core_script($src)) {
					continue;
				}
				$scripts[] = ['src' => $src];
				continue;
			}
			$inline = trim($match[2] ?? '');
			if ($inline === '' || tessera_operator_spa_skip_inline_script($inline)) {
				continue;
			}
			$scripts[] = ['inline' => $inline];
		}
		return $scripts;
	}

	function tessera_operator_spa_skip_inline_script(string $inline): bool {
		if (str_contains($inline, 'srSyncChromeHeight') || str_contains($inline, 'TesseraTourConfig')
			|| str_contains($inline, "classList.add('sr-platform-body')")) {
			return true;
		}
		// document.write after DOM parse wipes the operator shell document.
		if (preg_match('/\bdocument\.write\s*\(/i', $inline)) {
			return true;
		}
		// Regex extraction can yield truncated attribute debris (e.g. `">`) - never inject it.
		if (strlen($inline) < 12 || !preg_match('/[a-zA-Z_$]/', $inline)) {
			return true;
		}
		if (preg_match('/^["\'>\s]+$/', $inline)) {
			return true;
		}
		return false;
	}

	function tessera_operator_spa_rewrite_relative_hrefs(string $html, string $module): string {
		if ($html === '') {
			return $html;
		}
		$prefix = rtrim(tessera_operator_spa_public_prefix($module), '/');
		return (string) preg_replace_callback(
			'/(\s(?:href|action|src)=["\'])((?!https?:|\/|#|mailto:|javascript:|data:)[^"\']*)/i',
			static function (array $m) use ($prefix): string {
				return $m[1] . $prefix . '/' . ltrim($m[2], '/');
			},
			$html
		);
	}

	function tessera_operator_spa_extract_layout_wrapper(DOMDocument $dom, DOMElement $wrapper, array &$scripts, string $module): string {
		$html = '';
		foreach ($wrapper->childNodes as $node) {
			if ($node->nodeType !== XML_ELEMENT_NODE) {
				continue;
			}
			/** @var DOMElement $node */
			if ($node->nodeName === 'header' && $node->getAttribute('id') === 'page-topbar') {
				continue;
			}
			if ($node->nodeName === 'script') {
				tessera_operator_spa_collect_script($node, $scripts, $module);
				continue;
			}
			if ($node->nodeName === 'div' && str_contains($node->getAttribute('class') ?: '', 'main-content')) {
				$html .= tessera_operator_spa_inner_html($dom, $node, $scripts, $module);
				continue;
			}
			$html .= $dom->saveHTML($node);
		}
		return $html;
	}

	function tessera_operator_spa_public_prefix(string $module): string {
		return match (strtolower($module)) {
			'accounts' => '/accounts/',
			'cargo' => '/cargo/',
			default => '/tickets/',
		};
	}

	function tessera_operator_spa_relocate_trailing_scripts(string $html): string {
		if (!preg_match('/<\/html>\s*(.+)$/is', $html, $m)) {
			return $html;
		}
		$tail = trim($m[1]);
		if ($tail === '' || !str_contains($tail, '<script')) {
			return $html;
		}
		$head = substr($html, 0, strlen($html) - strlen($m[1]));
		$head = preg_replace('/<\/body>\s*<\/html>\s*$/is', '', (string) $head) ?? $head;
		return rtrim($head) . "\n" . $tail . "\n</body></html>";
	}

	function tessera_operator_spa_normalize_asset_url(string $url, string $module): string {
		$url = trim($url);
		if ($url === '') {
			return $url;
		}
		if (str_starts_with($url, '//')) {
			return 'https:' . $url;
		}
		if (preg_match('#^https?://#i', $url) || str_starts_with($url, '/')) {
			return $url;
		}
		return tessera_operator_spa_public_prefix($module) . ltrim($url, '/');
	}

	function tessera_operator_spa_normalize_script_src(string $src, string $module): string {
		return tessera_operator_spa_normalize_asset_url($src, $module);
	}

	function tessera_operator_spa_strip_script_tags(string $html): string {
		if ($html === '') {
			return $html;
		}
		return (string) preg_replace('/<script\b[^>]*>[\s\S]*?<\/script>/i', '', $html);
	}

	function tessera_operator_spa_fix_protocol_relative_assets(string $html): string {
		if ($html === '') {
			return $html;
		}
		return (string) preg_replace(
			'/(\s(?:href|src)=["\'])\/\/([^"\']+)/i',
			'$1https://$2',
			$html
		);
	}

	function tessera_operator_spa_stylesheet_allowed(string $module, string $href): bool {
		// Shell head already loads Dashonic core - skip duplicates from legacy page <head>.
		$coreSkip = [
			'bootstrap.min.css',
			'icons.min.css',
			'app.min.css',
			'sr-platform-shell.css',
			'tessera-operator-shell.css',
			'tessera-accounts-layout.css',
			'sweetalert2.min.css',
			'flatpickr.min.css',
			'jsvectormap.min.css',
		];
		foreach ($coreSkip as $skip) {
			if (str_contains($href, $skip)) {
				return false;
			}
		}
		$needles = [
			'/tickets/',
			'/cargo/',
			'/accounts/',
			'tessera-reports',
			'tessera-accounts',
			'home-dashboard',
			'booking-dark',
			'schedule.css',
			'datatable',
			'select_css',
			'buttons.css',
			'style.css',
			'gridjs',
			'alertify',
			'fullcalendar',
			'sr-platform',
		];
		foreach ($needles as $n) {
			if (str_contains($href, $n)) {
				return true;
			}
		}
		return str_starts_with($href, 'css/') || str_starts_with($href, 'assets/css/')
			|| str_starts_with($href, 'assets/libs/');
	}

	function tessera_operator_spa_inner_html(DOMDocument $dom, DOMElement $el, array &$scripts = [], string $module = 'tickets'): string {
		$html = '';
		foreach ($el->childNodes as $child) {
			if ($child->nodeType === XML_ELEMENT_NODE && strtolower($child->nodeName) === 'script') {
				tessera_operator_spa_collect_script($child, $scripts, $module);
				continue;
			}
			$html .= $dom->saveHTML($child);
		}
		return $html;
	}

	/** @param array<int,array{src?:string,inline?:string}> $scripts */
	function tessera_operator_spa_collect_script(DOMElement $script, array &$scripts, string $module = 'tickets'): void {
		$src = trim($script->getAttribute('src') ?: '');
		if ($src !== '') {
			$src = tessera_operator_spa_normalize_script_src($src, $module);
			if (tessera_operator_spa_is_core_script($src)) {
				return;
			}
			$scripts[] = ['src' => $src];
			return;
		}
		$inline = trim($script->textContent ?? '');
		if ($inline === '' || tessera_operator_spa_skip_inline_script($inline)) {
			return;
		}
		$scripts[] = ['inline' => $inline];
	}

	/** Collect every nested <script> under $root (depth-first document order). */
	function tessera_operator_spa_collect_scripts_under(DOMNode $root, array &$scripts, string $module = 'tickets'): void {
		if ($root instanceof DOMElement && strtolower($root->nodeName) === 'script') {
			tessera_operator_spa_collect_script($root, $scripts, $module);
			return;
		}
		if (!$root->hasChildNodes()) {
			return;
		}
		foreach ($root->childNodes as $child) {
			if ($child->nodeType !== XML_ELEMENT_NODE) {
				continue;
			}
			tessera_operator_spa_collect_scripts_under($child, $scripts, $module);
		}
	}

	function tessera_operator_spa_is_core_script(string $src): bool {
		$core = [
			'/tickets/assets/libs/bootstrap',
			'assets/libs/bootstrap',
			'bootstrap.bundle.min.js',
			'/tickets/assets/js/app.js',
			'/cargo/assets/js/app.js',
			'assets/js/app.js',
			'/tickets/assets/libs/jquery/',
			'assets/libs/jquery/',
			'js/vendor/jquery',
			'jquery-1.12.4',
			'jquery-1.12',
			'/tickets/assets/libs/metismenu',
			'assets/libs/metismenu',
			'/tickets/assets/libs/simplebar',
			'assets/libs/simplebar',
			'/tickets/assets/libs/feather',
			'assets/libs/feather',
			'/tickets/assets/libs/sweetalert',
			'assets/libs/sweetalert',
			'/tickets/assets/libs/flatpickr',
			'assets/libs/flatpickr',
			'/tickets/assets/js/bootstrap-jquery-bridge',
			'assets/js/bootstrap-jquery-bridge',
			'vendors/select2/',
			'/tickets/vendors/select2/',
			'cdn.datatables.net',
			'cdn.jsdelivr.net/npm/select2',
			'/accounts/dropzone/',
			'tessera-operator-shell.js',
			'tessera-accounts-spa.js',
			'assets/js/pages/calendar.init.js',
			'assets/js/pages/form-advanced.init.js',
			/* fullcalendar must load with schedule.php (and similar) - do not treat as shell core */
			'assets/libs/choices.js',
			'assets/libs/@simonwep/pickr',
			'assets/libs/leaflet',
			'assets/libs/apexcharts',
			'/tickets/assets/libs/apexcharts',
			'/cargo/assets/libs/apexcharts',
			/* Operator shell already ships DataTables + Buttons via dashonic; skip legacy local stack. */
			'datatable/datatable.js',
			'datatable/button.js',
			'datatable/jsq.js',
			'datatable/pdf_maker',
			'datatable/html.js',
			'datatable/print.js',
		];
		foreach ($core as $needle) {
			if (str_contains($src, $needle)) {
				return true;
			}
		}
		return false;
	}

	/** @param array<int,array{src?:string,inline?:string}> $scripts */
	function tessera_operator_spa_unique_scripts(array $scripts): array {
		$seen = [];
		$out = [];
		foreach ($scripts as $s) {
			$key = isset($s['src']) ? 's:' . $s['src'] : 'i:' . md5($s['inline'] ?? '');
			if (isset($seen[$key])) {
				continue;
			}
			$seen[$key] = true;
			$out[] = $s;
		}
		return $out;
	}

	function tessera_operator_spa_guess_main_page(string $module, string $path): string {
		$maps = [
			'accounts' => [
				'manifest.php' => 'Manifests',
				'compose_vehicle.php' => 'Manifests',
				'daily.php' => 'Cash Book',
				'withdraw.php' => 'Cash Book',
				'jobcard.php' => 'Purchases',
				'job_card.php' => 'Purchases',
				'jobcard_detail.php' => 'Purchases',
				'jobcard_entries.php' => 'Purchases',
				'jobcard_entries_2.php' => 'Purchases',
				'jobcard_entires.php' => 'Purchases',
				'jobcard_entires_2.php' => 'Purchases',
				'garage_particular.php' => 'Setup',
				'item.php' => 'Setup',
				'Register_routs.php' => 'Setup',
				'Register_routes.php' => 'Setup',
				'Register_sub_route.php' => 'Setup',
				'constants.php' => 'Setup',
				'currency.php' => 'Currency',
				'currency_value.php' => 'Currency',
				'route_currency.php' => 'Currency',
				'particular.php' => 'Setup',
				'particular_detail.php' => 'Setup',
				'particular_single_page.php' => 'Setup',
				'particular_signle_page.php' => 'Setup',
				'vehicle_category.php' => 'Setup',
				'vehicle_category_details.php' => 'Setup',
				'assign_route.php' => 'Setup',
				'report_profit_loss.php' => 'Financial Reports',
				'report_trips.php' => 'Fleet Reports',
				'vehicle_report.php' => 'Fleet Reports',
				'account_vehicles.php' => 'Fleet Reports',
				'account_vehciles.php' => 'Fleet Reports',
				'account_filter.php' => 'Financial Reports',
				'report_cash_flow.php' => 'Financial Reports',
				'report_revenue.php' => 'Financial Reports',
				'report_expenses.php' => 'Financial Reports',
				'report_payables.php' => 'Financial Reports',
				'report_currency.php' => 'Financial Reports',
			],
			'cargo' => [
				'home.php' => 'Home',
				'my_luggages.php' => 'My luggages',
				'deleted.php' => 'Deletes',
				'update_luggage.php' => 'My luggages',
				'view.php' => 'My luggages',
				'manifest_per_user.php' => 'Charges',
				'store_charge.php' => 'Charges',
				'summary_store_charge.php' => 'Charges',
				'total_charge_summary.php' => 'Charges',
				'charges.php' => 'Charges',
				'vehicle_report_new.php' => 'Vehicle report',
				'vehicle_report_new_edit.php' => 'Vehicle report',
				'register_currency.php' => 'Settings',
				'register_route.php' => 'Settings',
				'register_route_points.php' => 'Settings',
				'register_location_name.php' => 'Settings',
				'assign_route.php' => 'Settings',
				'sign_up.php' => 'Settings',
				'vehicle_registration.php' => 'Settings',
				'vehicle_view.php' => 'Manifest',
				'luggage_check_out.php' => 'Luggage check out',
				'check_luggages.php' => 'Load cargo',
				'my_charge.php' => 'My charges',
			],
			'tickets' => [
				'home.php' => 'Home',
				'tickets.php' => 'Book list',
				'schedule.php' => 'Schedule',
				'schedule_manage.php' => 'Schedule',
				'register_trip.php' => 'Schedule',
				'manifest.php' => 'Manifest',
				'vehicle_manager.php' => 'Vehicle manager',
				'route_manager.php' => 'Route manager',
				'register_route.php' => 'Settings',
				'register_sub_route.php' => 'Settings',
				'register_route_points.php' => 'Settings',
				'register_sub_route_points.php' => 'Settings',
				'register_location_name.php' => 'Settings',
				'register_currency.php' => 'Settings',
				'route_contact.php' => 'Settings',
				'route_transit.php' => 'Settings',
				'traveling_time.php' => 'Settings',
				'trip_list.php' => 'Schedule',
				'assign_route.php' => 'Settings',
				'sign_up.php' => 'Settings',
			],
		];
		return $maps[$module][$path] ?? '';
	}
}
