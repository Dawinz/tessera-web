<?php
require_once __DIR__ . '/includes/boot.php';
require_once $ticketsRoot . '/includes/tessera_payouts.php';
require_once $ticketsRoot . '/includes/tessera_messaging.php';
require_once $ticketsRoot . '/includes/tessera_operator_api.php';

$tenant = function_exists('tessera_tenant_row') ? (tessera_tenant_row() ?: []) : [];
$payout = settings_can('payouts') ? tessera_get_tenant_payout($db, $tenantId) : null;
$msgCfg = settings_can('notifications') ? tessera_messaging_get($db, $tenantId) : null;
$apiKeys = settings_can('api_keys') ? tessera_api_key_list($db, $tenantId) : [];
$activeKeys = 0;
foreach ($apiKeys as $k) {
	if (($k['status'] ?? '') === 'active') {
		$activeKeys++;
	}
}
$staffCount = 0;
$q = @$db->query('SELECT COUNT(*) AS c FROM users');
if ($q && ($r = $q->fetch_assoc())) {
	$staffCount = (int) $r['c'];
}
$payoutReady = $payout && (
	trim((string) ($payout['momo_msisdn'] ?? '')) !== ''
	|| trim((string) ($payout['account_number'] ?? '')) !== ''
);
$notifyReady = $msgCfg && (
	!empty($msgCfg['sms_enabled']) || !empty($msgCfg['whatsapp_enabled']) || !empty($msgCfg['push_enabled'])
);

$sections = [];
if (settings_can('payouts')) {
	$sections[] = [
		'href' => $SETTINGS_BASE . '/payouts.php',
		'title' => 'Payouts',
		'icon' => 'uil-money-withdraw',
		'soft' => 'primary',
		'text' => 'Bank or mobile money where Tessera sends settlements.',
		'meta' => $payoutReady ? (($payout['verified_status'] ?? '') === 'verified' ? 'Verified' : 'Saved') : 'Not configured',
		'ok' => (bool) $payoutReady,
	];
}
if (settings_can('notifications')) {
	$sections[] = [
		'href' => $SETTINGS_BASE . '/notifications.php',
		'title' => 'Notifications',
		'icon' => 'uil-bell',
		'soft' => 'success',
		'text' => 'SMS, WhatsApp and push channels for tickets and cargo.',
		'meta' => $notifyReady ? 'Channels enabled' : 'Disabled',
		'ok' => (bool) $notifyReady,
	];
}
if (settings_can('staff')) {
	$sections[] = [
		'href' => $SETTINGS_BASE . '/staff.php',
		'title' => 'Staff & roles',
		'icon' => 'uil-users-alt',
		'soft' => 'warning',
		'text' => 'Assign owner, manager, tickets, cargo and accountant roles.',
		'meta' => $staffCount . ' users',
		'ok' => $staffCount > 0,
	];
}
if (settings_can('api_keys')) {
	$sections[] = [
		'href' => $SETTINGS_BASE . '/api.php',
		'title' => 'API keys',
		'icon' => 'uil-key-skeleton',
		'soft' => 'info',
		'text' => 'Bearer credentials for /tickets/api/v1/.',
		'meta' => $activeKeys ? $activeKeys . ' active' : 'No keys',
		'ok' => $activeKeys > 0,
	];
	$sections[] = [
		'href' => $SETTINGS_BASE . '/webhooks.php',
		'title' => 'Webhooks',
		'icon' => 'uil-link-alt',
		'soft' => 'secondary',
		'text' => 'Signed outbound events to your HTTPS endpoints.',
		'meta' => 'Configure',
		'ok' => true,
	];
}
if (settings_can('settings')) {
	$sections[] = [
		'href' => $SETTINGS_BASE . '/company.php',
		'title' => 'Company profile',
		'icon' => 'uil-building',
		'soft' => 'danger',
		'text' => 'Display name, legal name, brand colour and logo.',
		'meta' => (string) ($tenant['display_name'] ?? 'Company'),
		'ok' => !empty($tenant['display_name']),
	];
}

$settings_page_title = 'Settings';
$settings_crumb = 'Overview';
$settings_nav_active = 'home';
require_once __DIR__ . '/includes/layout.php';
?>
<div class="row" data-tour="settings-home">
	<div class="col-12">
		<div class="card">
			<div class="card-body">
				<div class="d-flex flex-wrap align-items-center justify-content-between gap-2">
					<div>
						<h4 class="card-title mb-1"><?php echo settings_h($tenant['display_name'] ?? 'Operator settings'); ?></h4>
						<p class="text-muted mb-0">
							Company configuration for payouts, notifications, staff and integrations.
							<span class="ms-1">Plan: <span class="badge badge-soft-primary"><?php echo settings_h($tenant['plan'] ?? '—'); ?></span></span>
						</p>
					</div>
					<a href="<?php echo settings_h($TICKETS_WEB); ?>/home.php" class="btn btn-soft-primary btn-sm">
						<i class="uil uil-arrow-left me-1"></i> Back to Tickets
					</a>
				</div>
			</div>
		</div>
	</div>
</div>

<div class="row">
	<div class="col-md-3 col-6">
		<div class="card mini-stats-wid">
			<div class="card-body">
				<div class="d-flex">
					<div class="flex-grow-1">
						<p class="text-muted fw-medium mb-2">Payout</p>
						<h5 class="mb-0"><?php echo settings_can('payouts') ? ($payoutReady ? 'Ready' : 'Setup') : '—'; ?></h5>
					</div>
					<div class="mini-stat-icon avatar-sm align-self-center rounded-circle bg-primary">
						<span class="avatar-title"><i class="uil uil-money-withdraw font-size-20"></i></span>
					</div>
				</div>
			</div>
		</div>
	</div>
	<div class="col-md-3 col-6">
		<div class="card mini-stats-wid">
			<div class="card-body">
				<div class="d-flex">
					<div class="flex-grow-1">
						<p class="text-muted fw-medium mb-2">Notifications</p>
						<h5 class="mb-0"><?php echo settings_can('notifications') ? ($notifyReady ? 'On' : 'Off') : '—'; ?></h5>
					</div>
					<div class="mini-stat-icon avatar-sm align-self-center rounded-circle bg-success">
						<span class="avatar-title"><i class="uil uil-bell font-size-20"></i></span>
					</div>
				</div>
			</div>
		</div>
	</div>
	<div class="col-md-3 col-6">
		<div class="card mini-stats-wid">
			<div class="card-body">
				<div class="d-flex">
					<div class="flex-grow-1">
						<p class="text-muted fw-medium mb-2">Staff</p>
						<h5 class="mb-0"><?php echo (int) $staffCount; ?></h5>
					</div>
					<div class="mini-stat-icon avatar-sm align-self-center rounded-circle bg-warning">
						<span class="avatar-title"><i class="uil uil-users-alt font-size-20"></i></span>
					</div>
				</div>
			</div>
		</div>
	</div>
	<div class="col-md-3 col-6">
		<div class="card mini-stats-wid">
			<div class="card-body">
				<div class="d-flex">
					<div class="flex-grow-1">
						<p class="text-muted fw-medium mb-2">API keys</p>
						<h5 class="mb-0"><?php echo settings_can('api_keys') ? (int) $activeKeys : '—'; ?></h5>
					</div>
					<div class="mini-stat-icon avatar-sm align-self-center rounded-circle bg-info">
						<span class="avatar-title"><i class="uil uil-key-skeleton font-size-20"></i></span>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>

<div class="row">
	<?php foreach ($sections as $sec): ?>
		<div class="col-md-6">
			<a href="<?php echo settings_h($sec['href']); ?>" class="text-decoration-none">
				<div class="card border shadow-none">
					<div class="card-body">
						<div class="d-flex align-items-start">
							<div class="avatar-sm me-3">
								<span class="avatar-title rounded bg-soft-<?php echo settings_h($sec['soft']); ?> text-<?php echo settings_h($sec['soft'] === 'secondary' ? 'secondary' : $sec['soft']); ?>">
									<i class="uil <?php echo settings_h($sec['icon']); ?> font-size-18"></i>
								</span>
							</div>
							<div class="flex-grow-1 overflow-hidden">
								<div class="d-flex justify-content-between align-items-start mb-1">
									<h5 class="font-size-15 mb-0 text-truncate"><?php echo settings_h($sec['title']); ?></h5>
									<span class="badge badge-soft-<?php echo !empty($sec['ok']) ? 'success' : 'warning'; ?> ms-2"><?php echo settings_h((string) $sec['meta']); ?></span>
								</div>
								<p class="text-muted font-size-13 mb-0"><?php echo settings_h($sec['text']); ?></p>
							</div>
							<i class="uil uil-angle-right-b text-muted font-size-18 ms-2"></i>
						</div>
					</div>
				</div>
			</a>
		</div>
	<?php endforeach; ?>
</div>

<?php if (settings_can('settings') || settings_can('staff')): ?>
<?php
$CARGO_WEB = '/cargo';
?>
<div class="row">
	<div class="col-12">
		<div class="card">
			<div class="card-header">
				<h4 class="card-title mb-0">People &amp; counter setup</h4>
			</div>
			<div class="card-body">
				<p class="text-muted mb-3">Staff accounts and roles live in one place. Route and currency tools stay in Tickets.</p>
				<div class="row g-3">
					<div class="col-md-4">
						<a class="border rounded p-3 d-block h-100 text-decoration-none" href="<?php echo settings_h($SETTINGS_BASE); ?>/staff.php">
							<h5 class="font-size-14 text-dark mb-1">Staff &amp; roles</h5>
							<p class="text-muted font-size-12 mb-0">Add staff and change roles (syncs Tickets, Cargo, Accounts)</p>
						</a>
					</div>
					<div class="col-md-4">
						<a class="border rounded p-3 d-block h-100 text-decoration-none" href="<?php echo settings_h($TICKETS_WEB); ?>/assign_route.php">
							<h5 class="font-size-14 text-dark mb-1">Assign routes (Tickets)</h5>
							<p class="text-muted font-size-12 mb-0">Which routes each seller can book</p>
						</a>
					</div>
					<div class="col-md-4">
						<a class="border rounded p-3 d-block h-100 text-decoration-none" href="<?php echo settings_h($TICKETS_WEB); ?>/register_currency.php">
							<h5 class="font-size-14 text-dark mb-1">Currency (Tickets)</h5>
							<p class="text-muted font-size-12 mb-0">Currencies used at the ticket counter</p>
						</a>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
<div class="row">
	<div class="col-12">
		<div class="card">
			<div class="card-header">
				<h4 class="card-title mb-0">Cargo setup</h4>
			</div>
			<div class="card-body">
				<p class="text-muted mb-3">Fleet, cargo routes, and cargo currency — kept out of the day-ops nav.</p>
				<div class="row g-3">
					<div class="col-md-4">
						<a class="border rounded p-3 d-block h-100 text-decoration-none" href="<?php echo settings_h($CARGO_WEB); ?>/vehicle_registration.php">
							<h5 class="font-size-14 text-dark mb-1">Vehicle registration</h5>
							<p class="text-muted font-size-12 mb-0">Cargo fleet (bus plates, driver, conductor)</p>
						</a>
					</div>
					<div class="col-md-4">
						<a class="border rounded p-3 d-block h-100 text-decoration-none" href="<?php echo settings_h($CARGO_WEB); ?>/register_route.php">
							<h5 class="font-size-14 text-dark mb-1">Cargo routes</h5>
							<p class="text-muted font-size-12 mb-0">Parcel corridors and terminal contacts</p>
						</a>
					</div>
					<div class="col-md-4">
						<a class="border rounded p-3 d-block h-100 text-decoration-none" href="<?php echo settings_h($CARGO_WEB); ?>/assign_route.php">
							<h5 class="font-size-14 text-dark mb-1">Assign cargo routes</h5>
							<p class="text-muted font-size-12 mb-0">Which corridors each cargo agent can use</p>
						</a>
					</div>
					<div class="col-md-4">
						<a class="border rounded p-3 d-block h-100 text-decoration-none" href="<?php echo settings_h($CARGO_WEB); ?>/register_currency.php">
							<h5 class="font-size-14 text-dark mb-1">Cargo currency</h5>
							<p class="text-muted font-size-12 mb-0">Currencies used for parcel charges</p>
						</a>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
<?php
$ACCOUNTS_WEB = '/accounts';
?>
<div class="row">
	<div class="col-12">
		<div class="card">
			<div class="card-header">
				<h4 class="card-title mb-0">Accounts setup</h4>
			</div>
			<div class="card-body">
				<p class="text-muted mb-3">Chart of accounts, routes, and FX — kept out of the day-ops nav.</p>
				<div class="row g-3">
					<div class="col-md-4">
						<a class="border rounded p-3 d-block h-100 text-decoration-none" href="<?php echo settings_h($ACCOUNTS_WEB); ?>/Register_routes.php">
							<h5 class="font-size-14 text-dark mb-1">Accounts routes</h5>
							<p class="text-muted font-size-12 mb-0">Corridors used on manifests</p>
						</a>
					</div>
					<div class="col-md-4">
						<a class="border rounded p-3 d-block h-100 text-decoration-none" href="<?php echo settings_h($ACCOUNTS_WEB); ?>/constants.php">
							<h5 class="font-size-14 text-dark mb-1">Constants</h5>
							<p class="text-muted font-size-12 mb-0">Ledger constants / reference values</p>
						</a>
					</div>
					<div class="col-md-4">
						<a class="border rounded p-3 d-block h-100 text-decoration-none" href="<?php echo settings_h($ACCOUNTS_WEB); ?>/particular.php">
							<h5 class="font-size-14 text-dark mb-1">Particulars</h5>
							<p class="text-muted font-size-12 mb-0">Income and expense categories</p>
						</a>
					</div>
					<div class="col-md-4">
						<a class="border rounded p-3 d-block h-100 text-decoration-none" href="<?php echo settings_h($ACCOUNTS_WEB); ?>/particular_detail.php">
							<h5 class="font-size-14 text-dark mb-1">Particular detail</h5>
							<p class="text-muted font-size-12 mb-0">Line items under each particular</p>
						</a>
					</div>
					<div class="col-md-4">
						<a class="border rounded p-3 d-block h-100 text-decoration-none" href="<?php echo settings_h($ACCOUNTS_WEB); ?>/vehicle_category.php">
							<h5 class="font-size-14 text-dark mb-1">Vehicle categories</h5>
							<p class="text-muted font-size-12 mb-0">Group vehicles for reports</p>
						</a>
					</div>
					<div class="col-md-4">
						<a class="border rounded p-3 d-block h-100 text-decoration-none" href="<?php echo settings_h($ACCOUNTS_WEB); ?>/assign_route.php">
							<h5 class="font-size-14 text-dark mb-1">Assign accounts routes</h5>
							<p class="text-muted font-size-12 mb-0">Which corridors each accountant can use</p>
						</a>
					</div>
					<div class="col-md-4">
						<a class="border rounded p-3 d-block h-100 text-decoration-none" href="<?php echo settings_h($ACCOUNTS_WEB); ?>/currency.php">
							<h5 class="font-size-14 text-dark mb-1">Accounts currency</h5>
							<p class="text-muted font-size-12 mb-0">Currencies used in the ledger</p>
						</a>
					</div>
					<div class="col-md-4">
						<a class="border rounded p-3 d-block h-100 text-decoration-none" href="<?php echo settings_h($ACCOUNTS_WEB); ?>/route_currency.php">
							<h5 class="font-size-14 text-dark mb-1">Route currency</h5>
							<p class="text-muted font-size-12 mb-0">Currency per accounts route</p>
						</a>
					</div>
					<div class="col-md-4">
						<a class="border rounded p-3 d-block h-100 text-decoration-none" href="<?php echo settings_h($ACCOUNTS_WEB); ?>/currency_value.php">
							<h5 class="font-size-14 text-dark mb-1">Exchange rates</h5>
							<p class="text-muted font-size-12 mb-0">FX pairs for ledger conversions</p>
						</a>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
<?php endif; ?>
<?php settings_layout_end(); ?>
