<?php
require_once __DIR__ . '/includes/auth_guard.php';

$id = (int) ($_GET['id'] ?? $_POST['id'] ?? 0);
$tenant = control_get_tenant($db ?? null, !empty($control_db_ok), $id);
if (!$tenant) {
	http_response_code(404);
	echo 'Operator not found.';
	exit;
}

$error = null;
$supportLink = null;
$canTenants = control_can('manage_tenants');
$canPayments = control_can('manage_payments');
$canStaff = control_can('manage_staff');
$canImpersonate = control_can('impersonate');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
	$action = (string) ($_POST['action'] ?? '');

	if ($action === 'set_status' && $canTenants) {
		$status = (string) ($_POST['status'] ?? '');
		if (control_set_tenant_status($db ?? null, !empty($control_db_ok), $id, $status, $control_user['email'])) {
			header('Location: operator.php?id=' . $id . '&updated=1');
			exit;
		}
		$error = 'Status update failed.';
	} elseif ($action === 'provision_databases' && $canTenants) {
		$slug = (string) ($tenant['slug'] ?? '');
		if ($slug === '' || $slug === 'sr-classic') {
			$error = 'Flagship uses shared databases — no dedicated provision.';
		} else {
			$provFile = '/var/www/html/tickets/includes/tessera_db_provision.php';
			if (!is_file($provFile)) {
				$provFile = dirname(__DIR__, 2) . '/services/tickets/includes/tessera_db_provision.php';
			}
			if (!is_file($provFile)) {
				$error = 'Provisioner not found on this host.';
			} else {
				require_once $provFile;
				$prov = tessera_provision_tenant_databases($slug, ['copy_users' => false, 'force' => !empty($_POST['force'])]);
				control_audit($db ?? null, !empty($control_db_ok), $control_user['email'], 'tenant.db_provision', $id, $prov);
				if (!empty($prov['ok'])) {
					header('Location: operator.php?id=' . $id . '&dbprov=1');
					exit;
				}
				$error = 'DB provision failed: ' . (string) ($prov['error'] ?? 'unknown');
			}
		}
	} elseif ($action === 'save_domain' && $canTenants) {
		if (control_update_tenant_domain($db ?? null, !empty($control_db_ok), $id, [
			'domain_status' => $_POST['domain_status'] ?? 'pending',
			'primary_domain' => $_POST['primary_domain'] ?? '',
			'custom_domain' => $_POST['custom_domain'] ?? '',
		], $control_user['email'])) {
			header('Location: operator.php?id=' . $id . '&domain=1');
			exit;
		}
		$error = 'Domain update failed (run migration 004?).';
	} elseif ($action === 'save_branding' && $canTenants) {
		if (control_update_tenant_branding($db ?? null, !empty($control_db_ok), $id, [
			'display_name' => $_POST['display_name'] ?? '',
			'legal_name' => $_POST['legal_name'] ?? '',
			'logo_url' => $_POST['logo_url'] ?? '',
			'primary_color' => $_POST['primary_color'] ?? '',
		], $control_user['email'])) {
			header('Location: operator.php?id=' . $id . '&brand=1');
			exit;
		}
		$error = 'Branding update failed (hex color like #E53E3E).';
	} elseif ($action === 'save_modules' && $canTenants) {
		if (control_update_tenant_modules($db ?? null, !empty($control_db_ok), $id, [
			'plan' => $_POST['plan'] ?? '',
			'module_tickets' => !empty($_POST['module_tickets']) ? 1 : 0,
			'module_cargo' => !empty($_POST['module_cargo']) ? 1 : 0,
			'module_accounts' => !empty($_POST['module_accounts']) ? 1 : 0,
		], $control_user['email'])) {
			header('Location: operator.php?id=' . $id . '&modules=1');
			exit;
		}
		$error = 'Modules update failed.';
	} elseif ($action === 'apply_plan_defaults' && $canTenants) {
		$plan = (string) ($_POST['plan'] ?? $tenant['plan']);
		$defs = control_plan_module_defaults($plan);
		if (control_update_tenant_modules($db ?? null, !empty($control_db_ok), $id, [
			'plan' => $plan,
			'module_tickets' => $defs['tickets'],
			'module_cargo' => $defs['cargo'],
			'module_accounts' => $defs['accounts'],
		], $control_user['email'])) {
			header('Location: operator.php?id=' . $id . '&modules=1');
			exit;
		}
		$error = 'Could not apply plan defaults.';
	} elseif ($action === 'support_session' && $canImpersonate) {
		$target = trim((string) ($_POST['target_username'] ?? ''));
		$res = control_create_support_session($db ?? null, !empty($control_db_ok), $id, $target, $control_user['email']);
		if (!empty($res['ok'])) {
			$supportLink = $res;
		} else {
			$map = [
				'user_not_found' => 'Staff user not found.',
				'user_inactive' => 'Staff user is not active.',
				'tokens_table_missing' => 'Run migration 004 for support tokens.',
				'no_target' => 'Pick a staff username.',
			];
			$error = $map[$res['error'] ?? ''] ?? 'Could not create support link.';
		}
	} elseif ($action === 'save_payment' && $canPayments) {
		$saved = control_save_tenant_payment($db ?? null, !empty($control_db_ok), $id, [
			'client_id' => $_POST['client_id'] ?? '',
			'api_key' => $_POST['api_key'] ?? '',
			'base_url' => $_POST['base_url'] ?? '',
			'webhook_url' => $_POST['webhook_url'] ?? '',
			'payment_mock' => !empty($_POST['payment_mock']) ? 1 : 0,
		], $control_user['email']);
		if ($saved) {
			header('Location: operator.php?id=' . $id . '&payment=1');
			exit;
		}
		$error = 'Could not save platform collection rail (run migration 003?).';
	} elseif ($action === 'save_payout' && $canTenants) {
		if (control_save_tenant_payout($db ?? null, !empty($control_db_ok), $id, [
			'preferred_method' => $_POST['preferred_method'] ?? 'mobile_money',
			'bank_name' => $_POST['bank_name'] ?? '',
			'account_name' => $_POST['account_name'] ?? '',
			'account_number' => $_POST['account_number'] ?? '',
			'bank_branch' => $_POST['bank_branch'] ?? '',
			'momo_network' => $_POST['momo_network'] ?? '',
			'momo_msisdn' => $_POST['momo_msisdn'] ?? '',
			'momo_account_name' => $_POST['momo_account_name'] ?? '',
			'notes' => $_POST['notes'] ?? '',
			'verified_status' => $_POST['verified_status'] ?? 'pending',
		], $control_user['email'], false)) {
			control_ensure_platform_payment_method($db ?? null, !empty($control_db_ok), $id);
			header('Location: operator.php?id=' . $id . '&payout=1');
			exit;
		}
		$error = 'Could not save payout profile (run migration 011?).';
	} elseif ($action === 'create_settlement' && $canPayments) {
		$row = control_create_settlement($db ?? null, !empty($control_db_ok), $id, [
			'period_label' => $_POST['period_label'] ?? '',
			'amount_tzs' => $_POST['amount_tzs'] ?? 0,
			'notes' => $_POST['notes'] ?? '',
		], $control_user['email']);
		if ($row) {
			header('Location: operator.php?id=' . $id . '&settle=1');
			exit;
		}
		$error = 'Could not create settlement (run migration 011?).';
	} elseif ($action === 'mark_settlement_paid' && $canPayments) {
		if (control_mark_settlement_paid($db ?? null, !empty($control_db_ok), (int) ($_POST['settlement_id'] ?? 0), $control_user['email'], (string) ($_POST['reference'] ?? ''))) {
			header('Location: operator.php?id=' . $id . '&settle_paid=1');
			exit;
		}
		$error = 'Could not mark settlement paid.';
	} elseif ($action === 'create_staff' && $canStaff) {
		$staffConn = control_operator_staff_mysqli($db ?? null, !empty($control_db_ok), $tenant);
		$res = control_create_operator_staff($staffConn['db'], !empty($staffConn['ok']), $id, [
			'username' => $_POST['username'] ?? '',
			'password' => $_POST['password'] ?? '',
			'firstname' => $_POST['firstname'] ?? '',
			'surname' => $_POST['surname'] ?? '',
			'email' => $_POST['email'] ?? '',
			'number' => $_POST['number'] ?? '',
			'role' => $_POST['role'] ?? 'Administrator',
		], $control_user['email'], $db ?? null, $tenant);
		if (!empty($res['ok'])) {
			header('Location: operator.php?id=' . $id . '&staff=1');
			exit;
		}
		$map = [
			'duplicate' => 'Username, email, or phone already exists.',
			'validation' => 'Check required fields (password ≥ 6 chars).',
			'insert_failed' => 'Insert failed.',
			'users_table_missing' => 'Users table missing.',
		];
		$error = $map[$res['error'] ?? ''] ?? 'Staff create failed.';
	} elseif ($action === 'reset_staff_password' && $canStaff) {
		$staffConn = control_operator_staff_mysqli($db ?? null, !empty($control_db_ok), $tenant);
		$res = control_reset_operator_staff_password(
			$staffConn['db'],
			!empty($staffConn['ok']),
			$id,
			(int) ($_POST['user_id'] ?? 0),
			(string) ($_POST['password'] ?? ''),
			$control_user['email'],
			$db ?? null,
			$tenant
		);
		if (!empty($res['ok'])) {
			header('Location: operator.php?id=' . $id . '&staff_reset=1');
			exit;
		}
		$error = 'Staff password reset failed.';
	} elseif ($_SERVER['REQUEST_METHOD'] === 'POST') {
		$error = 'Your role cannot perform that action.';
	}
}

$tenant = control_get_tenant($db ?? null, !empty($control_db_ok), $id);
$casesPack = control_list_cases($db ?? null, !empty($control_db_ok));
$related = array_values(array_filter($casesPack['rows'], static function ($c) use ($tenant) {
	return (!empty($c['tenant_id']) && (int) $c['tenant_id'] === (int) $tenant['id'])
		|| (!empty($c['slug']) && $c['slug'] === $tenant['slug']);
}));
$consoleUrl = control_operator_console_url($tenant);
$paymentRow = control_get_tenant_payment($db ?? null, !empty($control_db_ok), $id);
$payment = control_payment_public_view($paymentRow);
$payout = control_get_tenant_payout($db ?? null, !empty($control_db_ok), $id);
$settlements = control_list_settlements($db ?? null, !empty($control_db_ok), $id, 20);
control_ensure_platform_payment_method($db ?? null, !empty($control_db_ok), $id);
$staffConn = control_operator_staff_mysqli($db ?? null, !empty($control_db_ok), $tenant);
$staffPack = control_list_operator_staff($staffConn['db'], !empty($staffConn['ok']), 25);
$created = isset($_GET['created']);
$updated = isset($_GET['updated']);
$paymentSaved = isset($_GET['payment']);
$payoutSaved = isset($_GET['payout']);
$settleSaved = isset($_GET['settle']);
$settlePaid = isset($_GET['settle_paid']);
$staffCreated = isset($_GET['staff']);
$staffReset = isset($_GET['staff_reset']);
$domainSaved = isset($_GET['domain']);
$brandSaved = isset($_GET['brand']);
$modulesSaved = isset($_GET['modules']);
$dbProvisioned = isset($_GET['dbprov']);
$dbMode = (string) ($tenant['db_mode'] ?? 'shared');
$dbTicket = (string) ($tenant['db_ticket'] ?? '');
$dbCargo = (string) ($tenant['db_cargo'] ?? '');
$dbAccount = (string) ($tenant['db_account'] ?? '');
$expectedHost = control_expected_subdomain($tenant);
$domainStatus = (string) ($tenant['domain_status'] ?? 'pending');
$volumePeriod = isset($_GET['vp']) ? (string) $_GET['vp'] : '7d';
$volume = control_tenant_metrics($db ?? null, !empty($control_db_ok), $tenant, $volumePeriod);

$defaultWebhook = rtrim(control_config()['public_operator_base'], '/') . '/tickets/api/payment_webhook.php';

$__page_title = $tenant['display_name'] . ' | Tessera Control';
$control_nav = 'operator';
include __DIR__ . '/includes/control_layout_start.php';
?>

<div class="row">
	<div class="col-12">
		<div class="page-title-box d-flex align-items-center justify-content-between">
			<div>
				<h4 class="mb-1"><?php echo control_h($tenant['display_name']); ?></h4>
				<ol class="breadcrumb m-0">
					<li class="breadcrumb-item"><a href="overview.php">Control</a></li>
					<li class="breadcrumb-item"><a href="operators.php">Operators</a></li>
					<li class="breadcrumb-item active">#<?php echo (int) $tenant['id']; ?></li>
				</ol>
			</div>
			<div class="d-flex flex-wrap gap-2 align-items-center">
				<?php echo control_status_badge($tenant['status']); ?>
				<a class="btn btn-soft-secondary" href="<?php echo control_h($consoleUrl); ?>" target="_blank" rel="noopener">
					<i class="mdi mdi-open-in-new me-1"></i> Staff console
				</a>
				<a class="btn btn-primary" href="onboarding_new.php">Start onboarding</a>
			</div>
		</div>
	</div>
</div>

<?php if ($created): ?>
	<div class="alert alert-success alert-dismissible fade show">Operator created successfully.<button type="button" class="btn-close" data-bs-dismiss="alert"></button></div>
<?php endif; ?>
<?php if ($updated): ?>
	<div class="alert alert-success alert-dismissible fade show">Status updated.<button type="button" class="btn-close" data-bs-dismiss="alert"></button></div>
<?php endif; ?>
<?php if ($paymentSaved): ?>
	<div class="alert alert-success alert-dismissible fade show">Platform collection rail saved. Secret is masked after save.<button type="button" class="btn-close" data-bs-dismiss="alert"></button></div>
<?php endif; ?>
<?php if ($payoutSaved): ?>
	<div class="alert alert-success alert-dismissible fade show">Payout profile saved.<button type="button" class="btn-close" data-bs-dismiss="alert"></button></div>
<?php endif; ?>
<?php if ($settleSaved): ?>
	<div class="alert alert-success alert-dismissible fade show">Settlement recorded.<button type="button" class="btn-close" data-bs-dismiss="alert"></button></div>
<?php endif; ?>
<?php if ($settlePaid): ?>
	<div class="alert alert-success alert-dismissible fade show">Settlement marked paid out.<button type="button" class="btn-close" data-bs-dismiss="alert"></button></div>
<?php endif; ?>
<?php if ($staffCreated): ?>
	<div class="alert alert-success alert-dismissible fade show">Staff user created.<button type="button" class="btn-close" data-bs-dismiss="alert"></button></div>
<?php endif; ?>
<?php if ($staffReset): ?>
	<div class="alert alert-success alert-dismissible fade show">Staff password reset (audited).<button type="button" class="btn-close" data-bs-dismiss="alert"></button></div>
<?php endif; ?>
<?php if ($domainSaved): ?>
	<div class="alert alert-success alert-dismissible fade show">Domain status saved.<button type="button" class="btn-close" data-bs-dismiss="alert"></button></div>
<?php endif; ?>
<?php if ($brandSaved): ?>
	<div class="alert alert-success alert-dismissible fade show">White-label branding saved — staff chrome will pick it up.<button type="button" class="btn-close" data-bs-dismiss="alert"></button></div>
<?php endif; ?>
<?php if ($modulesSaved): ?>
	<div class="alert alert-success alert-dismissible fade show">Module entitlements updated.<button type="button" class="btn-close" data-bs-dismiss="alert"></button></div>
<?php endif; ?>
<?php if ($dbProvisioned): ?>
	<div class="alert alert-success alert-dismissible fade show">Dedicated databases provisioned for this operator.<button type="button" class="btn-close" data-bs-dismiss="alert"></button></div>
<?php endif; ?>
<?php if ($supportLink): ?>
	<div class="alert alert-warning alert-dismissible fade show">
		<strong>Support link ready</strong> (one-time, expires <?php echo control_h($supportLink['expires_at']); ?>).
		<div class="mt-2"><a class="btn btn-sm btn-warning" href="<?php echo control_h($supportLink['url']); ?>" target="_blank" rel="noopener">Open staff console as support</a></div>
		<div class="font-size-12 text-muted mt-2 text-break"><?php echo control_h($supportLink['url']); ?></div>
		<button type="button" class="btn-close" data-bs-dismiss="alert"></button>
	</div>
<?php endif; ?>
<?php if ($error): ?>
	<div class="alert alert-danger alert-dismissible fade show"><?php echo control_h($error); ?><button type="button" class="btn-close" data-bs-dismiss="alert"></button></div>
<?php endif; ?>

<div class="control-page">
<div class="row mb-3">
	<div class="col-12">
		<div class="card border">
			<div class="card-body py-3 d-flex flex-wrap justify-content-between align-items-center gap-2">
				<div>
					<p class="text-muted text-uppercase font-size-12 mb-1">Databases (hybrid)</p>
					<?php if ($dbMode === 'dedicated' && $dbTicket !== ''): ?>
						<code class="font-size-12"><?php echo control_h($dbTicket); ?></code>
						· <code class="font-size-12"><?php echo control_h($dbCargo !== '' ? $dbCargo : '—'); ?></code>
						· <code class="font-size-12"><?php echo control_h($dbAccount !== '' ? $dbAccount : '—'); ?></code>
					<?php elseif (($tenant['slug'] ?? '') === 'sr-classic'): ?>
						<span class="text-muted">Shared <code>ticket</code> / <code>cargo</code> / <code>account</code> (flagship)</span>
					<?php else: ?>
						<span class="text-warning">Dedicated DBs not provisioned yet</span>
					<?php endif; ?>
				</div>
				<?php if ($canTenants && ($tenant['slug'] ?? '') !== 'sr-classic'): ?>
					<form method="post" class="m-0">
						<input type="hidden" name="action" value="provision_databases">
						<input type="hidden" name="id" value="<?php echo (int) $id; ?>">
						<button type="submit" class="btn btn-sm btn-soft-primary">Provision / repair DBs</button>
					</form>
				<?php endif; ?>
			</div>
		</div>
	</div>
</div>
<div class="row mb-3">
	<div class="col-12 d-flex justify-content-between align-items-center flex-wrap gap-2">
		<div class="text-muted font-size-13"><?php echo control_h($volume['note'] ?? ''); ?> · <?php echo control_h($volume['period']['label'] ?? ''); ?></div>
		<div class="btn-group btn-group-sm">
			<?php foreach (['today' => 'Today', '7d' => '7d', '30d' => '30d'] as $k => $lab): ?>
				<a class="btn btn-soft-<?php echo $volumePeriod === $k ? 'primary' : 'secondary'; ?>" href="operator.php?id=<?php echo (int) $id; ?>&vp=<?php echo $k; ?>"><?php echo $lab; ?></a>
			<?php endforeach; ?>
		</div>
	</div>
</div>
<div class="row g-3 mb-3">
	<div class="col-md-3">
		<div class="card control-kpi-card h-100"><div class="card-body py-3">
			<div class="control-kpi-label">Tickets</div>
			<div class="control-kpi-value"><?php echo number_format((int) $volume['tickets']); ?></div>
		</div></div>
	</div>
	<div class="col-md-3">
		<div class="card control-kpi-card h-100"><div class="card-body py-3">
			<div class="control-kpi-label">Ticket revenue</div>
			<div class="control-kpi-value"><?php echo control_h(control_format_money((float) $volume['ticket_revenue'])); ?></div>
		</div></div>
	</div>
	<div class="col-md-3">
		<div class="card control-kpi-card h-100"><div class="card-body py-3">
			<div class="control-kpi-label">Parcels</div>
			<div class="control-kpi-value"><?php echo number_format((int) $volume['parcels']); ?></div>
		</div></div>
	</div>
	<div class="col-md-3">
		<div class="card control-kpi-card h-100"><div class="card-body py-3">
			<div class="control-kpi-label">Pay success</div>
			<div class="control-kpi-value"><?php echo $volume['payment_success_pct'] === null ? '—' : control_h((string) $volume['payment_success_pct']) . '%'; ?></div>
		</div></div>
	</div>
</div>

<ul class="nav nav-tabs nav-tabs-custom mb-3" role="tablist">
	<li class="nav-item"><button class="nav-link active" type="button" data-bs-toggle="tab" data-bs-target="#op-config">Configuration</button></li>
	<li class="nav-item"><button class="nav-link" type="button" data-bs-toggle="tab" data-bs-target="#op-payments">Payments</button></li>
	<li class="nav-item"><button class="nav-link" type="button" data-bs-toggle="tab" data-bs-target="#op-staff">Staff</button></li>
</ul>

<div class="row">
	<div class="col-xl-8">
		<div class="tab-content">
		<div class="tab-pane fade show active" id="op-config">
		<div class="card">
			<div class="card-header bg-transparent border-bottom">
				<h5 class="card-title mb-0">Profile</h5>
			</div>
			<div class="card-body">
				<div class="row g-4">
					<div class="col-sm-6 col-lg-3">
						<div class="text-muted text-uppercase font-size-12">Plan</div>
						<div class="fw-semibold text-capitalize mt-1"><?php echo control_h($tenant['plan']); ?></div>
					</div>
					<div class="col-sm-6 col-lg-3">
						<div class="text-muted text-uppercase font-size-12">Slug</div>
						<div class="fw-semibold mt-1"><code><?php echo control_h($tenant['slug']); ?></code></div>
					</div>
					<div class="col-sm-6 col-lg-3">
						<div class="text-muted text-uppercase font-size-12">Domain</div>
						<div class="fw-semibold mt-1"><?php echo control_h($tenant['primary_domain'] ?: '—'); ?></div>
					</div>
					<div class="col-sm-6 col-lg-3">
						<div class="text-muted text-uppercase font-size-12">Legal name</div>
						<div class="fw-semibold mt-1"><?php echo control_h($tenant['legal_name']); ?></div>
					</div>
					<div class="col-sm-6">
						<div class="text-muted text-uppercase font-size-12">Created</div>
						<div class="fw-semibold mt-1"><?php echo control_h($tenant['created_at']); ?></div>
					</div>
					<div class="col-sm-6">
						<div class="text-muted text-uppercase font-size-12">Updated</div>
						<div class="fw-semibold mt-1"><?php echo control_h($tenant['updated_at']); ?></div>
					</div>
				</div>
				<div class="alert alert-soft-info border-0 mt-4 mb-0">
					Entitled modules:
					<?php
					$bits = [];
					if (!empty($tenant['module_tickets'])) { $bits[] = 'Tickets'; }
					if (!empty($tenant['module_cargo'])) { $bits[] = 'Cargo'; }
					if (!empty($tenant['module_accounts'])) { $bits[] = 'Accounts'; }
					echo control_h($bits ? implode(' · ', $bits) : 'None');
					?>.
					Staff never see Tessera Control.
				</div>
				<?php if ($tenant['status'] === 'suspended'): ?>
					<div class="alert alert-danger border-0 mt-3 mb-0">
						<strong>Access enforced:</strong> Staff login is blocked on Tickets, Cargo, and Accounts until you restore this operator.
					</div>
				<?php elseif ($tenant['status'] === 'active'): ?>
					<div class="alert alert-success border-0 mt-3 mb-0">
						<strong>Access open:</strong> Staff consoles allow login for this tenant (suspend is enforced when set).
					</div>
				<?php endif; ?>
			</div>
		</div>

		<div class="card">
			<div class="card-header bg-transparent border-bottom d-flex justify-content-between align-items-center">
				<h5 class="card-title mb-0">Domain / DNS</h5>
				<span class="badge badge-soft-<?php echo $domainStatus === 'ssl_ok' ? 'success' : ($domainStatus === 'failed' ? 'danger' : 'warning'); ?>"><?php echo control_h($domainStatus); ?></span>
			</div>
			<div class="card-body">
				<p class="text-muted font-size-13">Expected host: <code><?php echo control_h($expectedHost); ?></code>. Track DNS/SSL until go-live; automation can come later.</p>
				<?php if ($canTenants): ?>
					<form method="post" class="row g-3">
						<input type="hidden" name="action" value="save_domain">
						<input type="hidden" name="id" value="<?php echo (int) $id; ?>">
						<div class="col-md-4">
							<label class="form-label">Status</label>
							<select name="domain_status" class="form-select">
								<?php foreach (control_domain_statuses() as $ds): ?>
									<option value="<?php echo control_h($ds); ?>" <?php echo $domainStatus === $ds ? 'selected' : ''; ?>><?php echo control_h($ds); ?></option>
								<?php endforeach; ?>
							</select>
						</div>
						<div class="col-md-4">
							<label class="form-label">Primary domain</label>
							<input type="text" name="primary_domain" class="form-control" value="<?php echo control_h($tenant['primary_domain'] ?? $expectedHost); ?>">
						</div>
						<div class="col-md-4">
							<label class="form-label">Custom domain (optional)</label>
							<input type="text" name="custom_domain" class="form-control" value="<?php echo control_h($tenant['custom_domain'] ?? ''); ?>" placeholder="book.operator.co.tz">
						</div>
						<div class="col-12">
							<button type="submit" class="btn btn-primary">Save domain</button>
						</div>
					</form>
				<?php else: ?>
					<p class="mb-0 text-muted">Primary: <?php echo control_h($tenant['primary_domain'] ?: '—'); ?> · Custom: <?php echo control_h(($tenant['custom_domain'] ?? '') !== '' ? $tenant['custom_domain'] : '—'); ?></p>
				<?php endif; ?>
			</div>
		</div>

		<div class="card">
			<div class="card-header bg-transparent border-bottom">
				<h5 class="card-title mb-0">White-label</h5>
			</div>
			<div class="card-body">
				<?php if ($canTenants): ?>
					<form method="post" class="row g-3">
						<input type="hidden" name="action" value="save_branding">
						<input type="hidden" name="id" value="<?php echo (int) $id; ?>">
						<div class="col-md-6">
							<label class="form-label">Display name</label>
							<input type="text" name="display_name" class="form-control" value="<?php echo control_h($tenant['display_name']); ?>" required>
						</div>
						<div class="col-md-6">
							<label class="form-label">Legal name</label>
							<input type="text" name="legal_name" class="form-control" value="<?php echo control_h($tenant['legal_name']); ?>">
						</div>
						<div class="col-md-8">
							<label class="form-label">Logo URL</label>
							<input type="url" name="logo_url" class="form-control" value="<?php echo control_h($tenant['logo_url'] ?? ''); ?>" placeholder="https://…/logo.png">
						</div>
						<div class="col-md-4">
							<label class="form-label">Primary color</label>
							<input type="text" name="primary_color" class="form-control" value="<?php echo control_h($tenant['primary_color'] ?? '#E53E3E'); ?>" pattern="#[0-9A-Fa-f]{6}" placeholder="#E53E3E">
						</div>
						<div class="col-12">
							<button type="submit" class="btn btn-primary">Save branding</button>
						</div>
					</form>
				<?php else: ?>
					<p class="mb-0 text-muted"><?php echo control_h($tenant['display_name']); ?> · <?php echo control_h($tenant['primary_color'] ?? '—'); ?></p>
				<?php endif; ?>
			</div>
		</div>

		<div class="card">
			<div class="card-header bg-transparent border-bottom d-flex justify-content-between align-items-center">
				<h5 class="card-title mb-0">Module entitlements</h5>
				<span class="badge badge-soft-info text-capitalize"><?php echo control_h($tenant['plan']); ?></span>
			</div>
			<div class="card-body">
				<p class="text-muted font-size-13 mb-3"><strong>standard</strong> = Tickets only · <strong>flagship / enterprise</strong> = full suite (override allowed).</p>
				<?php if ($canTenants): ?>
					<form method="post" class="row g-3">
						<input type="hidden" name="action" value="save_modules">
						<input type="hidden" name="id" value="<?php echo (int) $id; ?>">
						<div class="col-md-4">
							<label class="form-label">Plan</label>
							<select name="plan" class="form-select">
								<?php foreach (['standard', 'flagship', 'enterprise'] as $p): ?>
									<option value="<?php echo $p; ?>" <?php echo ($tenant['plan'] ?? '') === $p ? 'selected' : ''; ?>><?php echo $p; ?></option>
								<?php endforeach; ?>
							</select>
						</div>
						<div class="col-md-8 d-flex align-items-end gap-3 flex-wrap">
							<div class="form-check">
								<input class="form-check-input" type="checkbox" name="module_tickets" value="1" id="mod_tickets" <?php echo !empty($tenant['module_tickets']) ? 'checked' : ''; ?>>
								<label class="form-check-label" for="mod_tickets">Tickets</label>
							</div>
							<div class="form-check">
								<input class="form-check-input" type="checkbox" name="module_cargo" value="1" id="mod_cargo" <?php echo !empty($tenant['module_cargo']) ? 'checked' : ''; ?>>
								<label class="form-check-label" for="mod_cargo">Cargo</label>
							</div>
							<div class="form-check">
								<input class="form-check-input" type="checkbox" name="module_accounts" value="1" id="mod_accounts" <?php echo !empty($tenant['module_accounts']) ? 'checked' : ''; ?>>
								<label class="form-check-label" for="mod_accounts">Accounts</label>
							</div>
						</div>
						<div class="col-12">
							<button type="submit" class="btn btn-primary">Save modules</button>
						</div>
					</form>
					<form method="post" class="mt-2">
						<input type="hidden" name="action" value="apply_plan_defaults">
						<input type="hidden" name="id" value="<?php echo (int) $id; ?>">
						<input type="hidden" name="plan" value="<?php echo control_h($tenant['plan']); ?>">
						<button type="submit" class="btn btn-soft-secondary btn-sm">Reset to plan defaults</button>
					</form>
				<?php else: ?>
					<p class="mb-0">
						Tickets <?php echo !empty($tenant['module_tickets']) ? '✓' : '—'; ?> ·
						Cargo <?php echo !empty($tenant['module_cargo']) ? '✓' : '—'; ?> ·
						Accounts <?php echo !empty($tenant['module_accounts']) ? '✓' : '—'; ?>
					</p>
				<?php endif; ?>
			</div>
		</div>

		</div>
		<div class="tab-pane fade" id="op-payments">

		<div class="card">
			<div class="card-header bg-transparent border-bottom d-flex justify-content-between align-items-center">
				<h5 class="card-title mb-0">Platform collection rail</h5>
				<?php if ($payment['configured']): ?>
					<span class="badge badge-soft-success">Configured</span>
				<?php else: ?>
					<span class="badge badge-soft-warning">Missing</span>
				<?php endif; ?>
			</div>
			<div class="card-body">
				<p class="text-muted font-size-13">Owner-only. Tessera collects passenger/cargo payments for this operator. Keys are encrypted; never shown to operator staff. Operator self-serve gateways come later.</p>
				<?php if (!$canPayments): ?>
					<div class="alert alert-soft-secondary mb-0">Your role can view status but cannot edit collection secrets.</div>
					<dl class="row mb-0 mt-3">
						<dt class="col-sm-4 text-muted">Client ID</dt>
						<dd class="col-sm-8"><?php echo control_h($payment['client_id'] ?: '—'); ?></dd>
						<dt class="col-sm-4 text-muted">Webhook</dt>
						<dd class="col-sm-8 font-size-12"><?php echo control_h($payment['webhook_url'] ?: '—'); ?></dd>
						<dt class="col-sm-4 text-muted">Mock mode</dt>
						<dd class="col-sm-8"><?php echo $payment['payment_mock'] ? 'On' : 'Off'; ?></dd>
					</dl>
				<?php else: ?>
					<form method="post" class="vstack gap-3" autocomplete="off">
						<input type="hidden" name="action" value="save_payment">
						<input type="hidden" name="id" value="<?php echo (int) $id; ?>">
						<div>
							<label class="form-label">Client ID</label>
							<input type="text" name="client_id" class="form-control" value="<?php echo control_h($payment['client_id']); ?>" required>
						</div>
						<div>
							<label class="form-label">API key <?php echo $payment['api_key_last4'] ? '<span class="text-muted">(leave blank to keep current)</span>' : ''; ?></label>
							<input type="password" name="api_key" class="form-control" placeholder="<?php echo $payment['api_key_last4'] ? '••••' . control_h($payment['api_key_last4']) : 'Paste new key'; ?>" <?php echo $payment['configured'] ? '' : 'required'; ?>>
						</div>
						<div>
							<label class="form-label">Base URL (optional)</label>
							<input type="url" name="base_url" class="form-control" value="<?php echo control_h($payment['base_url']); ?>" placeholder="https://api.example.com">
						</div>
						<div>
							<label class="form-label">Webhook URL</label>
							<input type="url" name="webhook_url" class="form-control" value="<?php echo control_h($payment['webhook_url'] ?: $defaultWebhook); ?>">
						</div>
						<div class="form-check">
							<input class="form-check-input" type="checkbox" name="payment_mock" value="1" id="payment_mock" <?php echo $payment['payment_mock'] ? 'checked' : ''; ?>>
							<label class="form-check-label" for="payment_mock">Force mock payments (demo)</label>
						</div>
						<?php if ($payment['webhook_last_at']): ?>
							<div class="text-muted font-size-12">Last webhook: <?php echo control_h($payment['webhook_last_at']); ?> · <?php echo control_h($payment['webhook_last_status'] ?: '—'); ?></div>
						<?php endif; ?>
						<button type="submit" class="btn btn-primary align-self-start">Save collection rail</button>
					</form>
				<?php endif; ?>
			</div>
		</div>

		<div class="card">
			<div class="card-header bg-transparent border-bottom d-flex justify-content-between align-items-center">
				<h5 class="card-title mb-0">Operator payout profile</h5>
				<span class="badge badge-soft-<?php echo ($payout['verified_status'] ?? '') === 'verified' ? 'success' : 'warning'; ?>">
					<?php echo control_h($payout['verified_status'] ?? 'unverified'); ?>
				</span>
			</div>
			<div class="card-body">
				<p class="text-muted font-size-13">Where Tessera sends operator settlements (bank or mobile money). Operators can also edit this in Settings.</p>
				<?php if (!$canTenants): ?>
					<div class="alert alert-soft-secondary mb-0">Read-only for your role.</div>
				<?php else: ?>
					<form method="post" class="vstack gap-3">
						<input type="hidden" name="action" value="save_payout">
						<input type="hidden" name="id" value="<?php echo (int) $id; ?>">
						<div>
							<label class="form-label">Preferred method</label>
							<select name="preferred_method" class="form-select">
								<option value="mobile_money" <?php echo ($payout['preferred_method'] ?? '') === 'mobile_money' ? 'selected' : ''; ?>>Mobile money</option>
								<option value="bank" <?php echo ($payout['preferred_method'] ?? '') === 'bank' ? 'selected' : ''; ?>>Bank transfer</option>
							</select>
						</div>
						<div class="row g-2">
							<div class="col-md-6">
								<label class="form-label">Mobile money network</label>
								<input type="text" name="momo_network" class="form-control" value="<?php echo control_h($payout['momo_network'] ?? ''); ?>" placeholder="M-Pesa / Tigo Pesa / Airtel Money">
							</div>
							<div class="col-md-6">
								<label class="form-label">Mobile money number</label>
								<input type="text" name="momo_msisdn" class="form-control" value="<?php echo control_h($payout['momo_msisdn'] ?? ''); ?>">
							</div>
							<div class="col-md-6">
								<label class="form-label">Mobile money account name</label>
								<input type="text" name="momo_account_name" class="form-control" value="<?php echo control_h($payout['momo_account_name'] ?? ''); ?>">
							</div>
						</div>
						<div class="row g-2">
							<div class="col-md-6">
								<label class="form-label">Bank name</label>
								<input type="text" name="bank_name" class="form-control" value="<?php echo control_h($payout['bank_name'] ?? ''); ?>">
							</div>
							<div class="col-md-6">
								<label class="form-label">Account number</label>
								<input type="text" name="account_number" class="form-control" value="<?php echo control_h($payout['account_number'] ?? ''); ?>">
							</div>
							<div class="col-md-6">
								<label class="form-label">Account name</label>
								<input type="text" name="account_name" class="form-control" value="<?php echo control_h($payout['account_name'] ?? ''); ?>">
							</div>
							<div class="col-md-6">
								<label class="form-label">Branch</label>
								<input type="text" name="bank_branch" class="form-control" value="<?php echo control_h($payout['bank_branch'] ?? ''); ?>">
							</div>
						</div>
						<div>
							<label class="form-label">Verification</label>
							<select name="verified_status" class="form-select">
								<?php foreach (['unverified','pending','verified','rejected'] as $vs): ?>
									<option value="<?php echo $vs; ?>" <?php echo ($payout['verified_status'] ?? '') === $vs ? 'selected' : ''; ?>><?php echo $vs; ?></option>
								<?php endforeach; ?>
							</select>
						</div>
						<div>
							<label class="form-label">Notes</label>
							<textarea name="notes" class="form-control" rows="2"><?php echo control_h($payout['notes'] ?? ''); ?></textarea>
						</div>
						<button type="submit" class="btn btn-primary align-self-start">Save payout profile</button>
					</form>
				<?php endif; ?>
			</div>
		</div>

		<div class="card">
			<div class="card-header bg-transparent border-bottom">
				<h5 class="card-title mb-0">Settlements (payouts to operator)</h5>
			</div>
			<div class="card-body">
				<?php if ($canPayments): ?>
					<form method="post" class="row g-2 align-items-end mb-3">
						<input type="hidden" name="action" value="create_settlement">
						<input type="hidden" name="id" value="<?php echo (int) $id; ?>">
						<div class="col-md-4">
							<label class="form-label">Period</label>
							<input type="text" name="period_label" class="form-control" placeholder="2026-07" required>
						</div>
						<div class="col-md-3">
							<label class="form-label">Amount (TZS)</label>
							<input type="number" step="0.01" min="1" name="amount_tzs" class="form-control" required>
						</div>
						<div class="col-md-3">
							<label class="form-label">Notes</label>
							<input type="text" name="notes" class="form-control">
						</div>
						<div class="col-md-2">
							<button type="submit" class="btn btn-soft-primary w-100">Add owed</button>
						</div>
					</form>
				<?php endif; ?>
				<?php if (empty($settlements)): ?>
					<p class="text-muted mb-0">No settlements yet.</p>
				<?php else: ?>
					<div class="table-responsive">
						<table class="table table-sm mb-0">
							<thead><tr><th>Period</th><th>Amount</th><th>Status</th><th></th></tr></thead>
							<tbody>
							<?php foreach (array_slice($settlements, 0, 5) as $s): ?>
								<tr>
									<td><?php echo control_h($s['period_label']); ?></td>
									<td><?php echo number_format((float) $s['amount_tzs'], 0); ?> <?php echo control_h($s['currency']); ?></td>
									<td><?php echo control_h($s['status']); ?></td>
									<td class="text-end">
										<?php if ($canPayments && in_array($s['status'], ['owed','processing'], true)): ?>
											<form method="post" class="d-inline">
												<input type="hidden" name="action" value="mark_settlement_paid">
												<input type="hidden" name="id" value="<?php echo (int) $id; ?>">
												<input type="hidden" name="settlement_id" value="<?php echo (int) $s['id']; ?>">
												<input type="text" name="reference" class="form-control form-control-sm d-inline-block" style="width:8rem" placeholder="Ref">
												<button class="btn btn-sm btn-soft-success">Mark paid</button>
											</form>
										<?php endif; ?>
									</td>
								</tr>
							<?php endforeach; ?>
							</tbody>
						</table>
					</div>
				<?php endif; ?>
			</div>
		</div>

		</div>
		<div class="tab-pane fade" id="op-staff">

		<div class="card">
			<div class="card-header bg-transparent border-bottom d-flex justify-content-between align-items-center">
				<h5 class="card-title mb-0">Staff users</h5>
				<span class="badge badge-soft-info"><?php echo (int) $staffPack['count']; ?> total</span>
			</div>
			<div class="card-body p-0">
				<?php if (empty($staffPack['rows'])): ?>
					<p class="text-muted p-3 mb-0">No staff users in this operator’s Tickets database yet. Create the first admin below.</p>
				<?php else: ?>
					<div class="table-responsive control-table-scroll" style="max-height:320px">
						<table class="table table-hover mb-0 table-control">
							<thead class="table-light sticky-top">
								<tr>
									<th>User</th>
									<th>Role</th>
									<th>Status</th>
									<th></th>
								</tr>
							</thead>
							<tbody>
								<?php foreach (array_slice($staffPack['rows'], 0, 10) as $u): ?>
									<tr>
										<td>
											<div class="fw-semibold"><?php echo control_h($u['username']); ?></div>
											<div class="text-muted font-size-12"><?php echo control_h(trim($u['firstname'] . ' ' . $u['surname'])); ?> · <?php echo control_h($u['email']); ?></div>
										</td>
										<td><?php echo control_h($u['role']); ?></td>
										<td>
											<?php if (strtolower((string) $u['status']) === 'active'): ?>
												<span class="badge badge-soft-success">active</span>
											<?php else: ?>
												<span class="badge badge-soft-secondary"><?php echo control_h($u['status']); ?></span>
											<?php endif; ?>
										</td>
										<td class="text-end">
											<?php if ($canStaff): ?>
												<form method="post" class="d-inline-flex gap-1 align-items-center">
													<input type="hidden" name="action" value="reset_staff_password">
													<input type="hidden" name="id" value="<?php echo (int) $id; ?>">
													<input type="hidden" name="user_id" value="<?php echo (int) $u['id']; ?>">
													<input type="password" name="password" class="form-control form-control-sm" style="width:120px" placeholder="New pwd" minlength="6" required>
													<button class="btn btn-sm btn-soft-warning" type="submit">Reset</button>
												</form>
											<?php endif; ?>
										</td>
									</tr>
								<?php endforeach; ?>
							</tbody>
						</table>
					</div>
				<?php endif; ?>
			</div>
			<?php if ($canStaff): ?>
				<div class="card-body border-top">
					<h6 class="mb-3">Create staff admin</h6>
					<form method="post" class="row g-2">
						<input type="hidden" name="action" value="create_staff">
						<input type="hidden" name="id" value="<?php echo (int) $id; ?>">
						<div class="col-md-4">
							<input type="text" name="firstname" class="form-control" placeholder="First name" required>
						</div>
						<div class="col-md-4">
							<input type="text" name="surname" class="form-control" placeholder="Surname" required>
						</div>
						<div class="col-md-4">
							<input type="text" name="username" class="form-control" placeholder="Username" required>
						</div>
						<div class="col-md-4">
							<input type="email" name="email" class="form-control" placeholder="Email" required>
						</div>
						<div class="col-md-4">
							<input type="text" name="number" class="form-control" placeholder="Phone">
						</div>
						<div class="col-md-4">
							<select name="role" class="form-select">
								<option value="Administrator">Administrator</option>
								<option value="Super Administrator">Super Administrator</option>
								<option value="Agent">Agent</option>
							</select>
						</div>
						<div class="col-md-4">
							<input type="password" name="password" class="form-control" placeholder="Password" minlength="6" required>
						</div>
						<div class="col-md-4 d-flex align-items-center">
							<button type="submit" class="btn btn-primary">Create user</button>
						</div>
					</form>
				</div>
			<?php endif; ?>
		</div>

		<div class="card">
			<div class="card-header bg-transparent border-bottom d-flex justify-content-between align-items-center">
				<h5 class="card-title mb-0">Related onboarding</h5>
				<a href="onboarding_new.php" class="btn btn-sm btn-soft-primary">New case</a>
			</div>
			<div class="card-body p-0">
				<?php if (!$related): ?>
					<p class="text-muted p-3 mb-0">No onboarding case linked yet.</p>
				<?php else: ?>
					<div class="table-responsive">
						<table class="table table-hover mb-0 table-control">
							<thead class="table-light">
								<tr><th>Case</th><th>Stage</th><th>Go-live</th><th></th></tr>
							</thead>
							<tbody>
								<?php foreach ($related as $c): ?>
									<tr>
										<td>#<?php echo (int) $c['id']; ?> · <?php echo control_h($c['company_name']); ?></td>
										<td><span class="badge badge-soft-info"><?php echo control_h($c['stage']); ?></span></td>
										<td class="text-muted"><?php echo control_h($c['go_live_date'] ?: '—'); ?></td>
										<td class="text-end"><a class="btn btn-sm btn-soft-primary" href="onboarding_case.php?id=<?php echo (int) $c['id']; ?>">Open</a></td>
									</tr>
								<?php endforeach; ?>
							</tbody>
						</table>
					</div>
				<?php endif; ?>
			</div>
		</div>

		</div>
		</div>
	</div>

	<div class="col-xl-4">
		<div class="card">
			<div class="card-header bg-transparent border-bottom">
				<h5 class="card-title mb-0">Platform actions</h5>
			</div>
			<div class="card-body">
				<p class="text-muted">Status changes are audited when registry tables exist.</p>
				<div class="d-grid gap-2">
					<?php if ($canTenants): ?>
						<?php if ($tenant['status'] !== 'active'): ?>
							<form method="post">
								<input type="hidden" name="action" value="set_status">
								<input type="hidden" name="id" value="<?php echo (int) $id; ?>">
								<input type="hidden" name="status" value="active">
								<button class="btn btn-success w-100" type="submit">Activate / restore</button>
							</form>
						<?php endif; ?>
						<?php if ($tenant['status'] !== 'suspended'): ?>
							<form method="post">
								<input type="hidden" name="action" value="set_status">
								<input type="hidden" name="id" value="<?php echo (int) $id; ?>">
								<input type="hidden" name="status" value="suspended">
								<button class="btn btn-danger w-100" type="submit">Suspend operator</button>
							</form>
						<?php endif; ?>
						<?php if ($tenant['status'] !== 'provisioning'): ?>
							<form method="post">
								<input type="hidden" name="action" value="set_status">
								<input type="hidden" name="id" value="<?php echo (int) $id; ?>">
								<input type="hidden" name="status" value="provisioning">
								<button class="btn btn-warning w-100" type="submit">Mark provisioning</button>
							</form>
						<?php endif; ?>
					<?php else: ?>
						<p class="text-muted mb-0 font-size-13">Readonly — status actions hidden.</p>
					<?php endif; ?>
					<a class="btn btn-soft-secondary" href="<?php echo control_h($consoleUrl); ?>" target="_blank" rel="noopener">Open staff console</a>
				</div>
			</div>
		</div>

		<?php if ($canImpersonate): ?>
		<div class="card">
			<div class="card-header bg-transparent border-bottom">
				<h5 class="card-title mb-0">View as operator</h5>
			</div>
			<div class="card-body">
				<p class="text-muted font-size-13">Creates a one-time, 30-minute support link into Tickets. Audited. Staff password not required.</p>
				<form method="post" class="vstack gap-2">
					<input type="hidden" name="action" value="support_session">
					<input type="hidden" name="id" value="<?php echo (int) $id; ?>">
					<label class="form-label mb-0">Staff username</label>
					<select name="target_username" class="form-select" required>
						<option value="">Select…</option>
						<?php foreach ($staffPack['rows'] as $u): ?>
							<?php if (strtolower((string) $u['status']) !== 'active') continue; ?>
							<option value="<?php echo control_h($u['username']); ?>"><?php echo control_h($u['username']); ?> · <?php echo control_h($u['role']); ?></option>
						<?php endforeach; ?>
					</select>
					<button type="submit" class="btn btn-warning">Generate support link</button>
				</form>
			</div>
		</div>
		<?php endif; ?>
	</div>
</div>
</div>

<?php include __DIR__ . '/includes/control_layout_end.php'; ?>
