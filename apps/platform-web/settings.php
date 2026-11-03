<?php
require_once __DIR__ . '/includes/auth_guard.php';

$error = null;
$canManage = control_can('manage_admins');
$canOps = control_can('manage_ops');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
	$action = (string) ($_POST['action'] ?? '');
	if ($action === 'create_admin' && $canManage) {
		$created = control_create_admin($db ?? null, !empty($control_db_ok), [
			'email' => $_POST['email'] ?? '',
			'name' => $_POST['name'] ?? '',
			'role' => $_POST['role'] ?? 'support',
			'password' => $_POST['password'] ?? '',
		], $control_user['email']);
		if ($created) {
			header('Location: settings.php?created=1');
			exit;
		}
		$error = 'Could not create admin (check email unique, password ≥ 8 chars).';
	} elseif ($action === 'set_status' && $canManage) {
		$id = (int) ($_POST['id'] ?? 0);
		$status = (string) ($_POST['status'] ?? '');
		if ($id === (int) ($control_user['id'] ?? 0) && $status === 'disabled') {
			$error = 'You cannot disable your own account.';
		} elseif (control_set_admin_status($db ?? null, !empty($control_db_ok), $id, $status, $control_user['email'])) {
			header('Location: settings.php?updated=1');
			exit;
		} else {
			$error = 'Status update failed.';
		}
	} elseif ($action === 'reset_password' && $canManage) {
		$id = (int) ($_POST['id'] ?? 0);
		$password = (string) ($_POST['password'] ?? '');
		if (control_reset_admin_password($db ?? null, !empty($control_db_ok), $id, $password, $control_user['email'])) {
			header('Location: settings.php?reset=1');
			exit;
		}
		$error = 'Password reset failed (min 8 characters).';
	} elseif ($action === 'save_notify' && $canOps) {
		if (control_save_notify_prefs($db ?? null, !empty($control_db_ok), $_POST, $control_user['email'])) {
			header('Location: settings.php?prefs=1');
			exit;
		}
		$error = 'Could not save notification preferences.';
	} elseif ($action === 'save_branding' && $canOps) {
		if (control_branding_save($db ?? null, !empty($control_db_ok), [
			'subtitle' => $_POST['subtitle'] ?? 'Control',
			'logo_url' => $_POST['logo_url'] ?? '',
		], $control_user['email'])) {
			header('Location: settings.php?brand=1');
			exit;
		}
		$error = 'Could not save Control branding.';
	} elseif ($action === 'scan_alerts' && $canOps) {
		control_alerts_scan($db ?? null, !empty($control_db_ok), true);
		header('Location: settings.php?scan=1');
		exit;
	} elseif ($_SERVER['REQUEST_METHOD'] === 'POST') {
		$error = 'Your role cannot perform that action.';
	}
}

control_ensure_bootstrap_admin($db ?? null, !empty($control_db_ok));
$admins = control_list_admins($db ?? null, !empty($control_db_ok));
$prefs = control_notify_prefs($db ?? null, !empty($control_db_ok));
$brand = control_branding_get($db ?? null, !empty($control_db_ok));
$cfg = control_config();

$__page_title = 'Settings | Tessera Control';
$control_nav = 'settings';
include __DIR__ . '/includes/control_layout_start.php';
?>

<div class="control-page">
<div class="row">
	<div class="col-12">
		<div class="page-title-box d-flex align-items-center justify-content-between">
			<div>
				<h4 class="mb-1">Settings</h4>
				<p class="text-muted mb-0 font-size-13">Platform admins · alerts · branding</p>
			</div>
			<span class="badge badge-soft-info text-uppercase"><?php echo control_h($control_user['role']); ?></span>
		</div>
	</div>
</div>

<?php if (isset($_GET['created'])): ?><div class="alert alert-success alert-dismissible fade show py-2">Admin created.<button type="button" class="btn-close" data-bs-dismiss="alert"></button></div><?php endif; ?>
<?php if (isset($_GET['updated'])): ?><div class="alert alert-success alert-dismissible fade show py-2">Status updated.<button type="button" class="btn-close" data-bs-dismiss="alert"></button></div><?php endif; ?>
<?php if (isset($_GET['reset'])): ?><div class="alert alert-success alert-dismissible fade show py-2">Password reset.<button type="button" class="btn-close" data-bs-dismiss="alert"></button></div><?php endif; ?>
<?php if (isset($_GET['prefs'])): ?><div class="alert alert-success alert-dismissible fade show py-2">Preferences saved.<button type="button" class="btn-close" data-bs-dismiss="alert"></button></div><?php endif; ?>
<?php if (isset($_GET['brand'])): ?><div class="alert alert-success alert-dismissible fade show py-2">Branding saved.<button type="button" class="btn-close" data-bs-dismiss="alert"></button></div><?php endif; ?>
<?php if (isset($_GET['scan'])): ?><div class="alert alert-success alert-dismissible fade show py-2">Alert scan ran.<button type="button" class="btn-close" data-bs-dismiss="alert"></button></div><?php endif; ?>
<?php if ($error): ?><div class="alert alert-danger alert-dismissible fade show"><?php echo control_h($error); ?><button type="button" class="btn-close" data-bs-dismiss="alert"></button></div><?php endif; ?>

<ul class="nav nav-tabs nav-tabs-custom mb-3" role="tablist">
	<li class="nav-item"><button class="nav-link active" data-bs-toggle="tab" data-bs-target="#set-admins" type="button">Admins</button></li>
	<li class="nav-item"><button class="nav-link" data-bs-toggle="tab" data-bs-target="#set-alerts" type="button">Alerts</button></li>
	<li class="nav-item"><button class="nav-link" data-bs-toggle="tab" data-bs-target="#set-access" type="button">Invite & branding</button></li>
	<li class="nav-item"><button class="nav-link" data-bs-toggle="tab" data-bs-target="#set-host" type="button">Production</button></li>
</ul>

<div class="tab-content">
	<div class="tab-pane fade show active" id="set-admins">
		<div class="card control-table-panel">
			<div class="card-header bg-transparent border-bottom"><h5 class="control-panel-title mb-0">Platform admins</h5></div>
			<?php if (!$admins): ?>
				<p class="text-muted p-3 mb-0">No admins in registry yet.</p>
			<?php else: ?>
				<div class="control-table-scroll" style="max-height:400px">
					<table class="table table-hover mb-0 table-control">
						<thead class="table-light sticky-top">
							<tr><th>Name</th><th>Role</th><th>Status</th><th>Last login</th><th></th></tr>
						</thead>
						<tbody>
							<?php foreach ($admins as $a): ?>
								<tr>
									<td>
										<div class="fw-semibold font-size-13"><?php echo control_h($a['name']); ?></div>
										<div class="text-muted font-size-12"><?php echo control_h($a['email']); ?></div>
									</td>
									<td><span class="badge badge-soft-primary"><?php echo control_h($a['role']); ?></span></td>
									<td><span class="badge badge-soft-<?php echo $a['status'] === 'active' ? 'success' : 'danger'; ?>"><?php echo control_h($a['status']); ?></span></td>
									<td class="text-muted font-size-12"><?php echo control_h($a['last_login_at'] ?: '—'); ?></td>
									<td class="text-end">
										<?php if ($canManage): ?>
											<div class="dropdown">
												<button class="btn btn-sm btn-soft-secondary dropdown-toggle" type="button" data-bs-toggle="dropdown">Actions</button>
												<div class="dropdown-menu dropdown-menu-end">
													<?php if ($a['status'] === 'active'): ?>
														<form method="post" class="px-3 py-1">
															<input type="hidden" name="action" value="set_status">
															<input type="hidden" name="id" value="<?php echo (int) $a['id']; ?>">
															<input type="hidden" name="status" value="disabled">
															<button class="dropdown-item text-danger p-0 border-0 bg-transparent w-100 text-start" type="submit">Disable</button>
														</form>
													<?php else: ?>
														<form method="post" class="px-3 py-1">
															<input type="hidden" name="action" value="set_status">
															<input type="hidden" name="id" value="<?php echo (int) $a['id']; ?>">
															<input type="hidden" name="status" value="active">
															<button class="dropdown-item p-0 border-0 bg-transparent w-100 text-start" type="submit">Enable</button>
														</form>
													<?php endif; ?>
													<div class="dropdown-divider"></div>
													<form method="post" class="px-3 py-2" style="min-width:200px">
														<input type="hidden" name="action" value="reset_password">
														<input type="hidden" name="id" value="<?php echo (int) $a['id']; ?>">
														<input type="password" name="password" class="form-control form-control-sm mb-2" minlength="8" required placeholder="New password">
														<button class="btn btn-sm btn-primary w-100" type="submit">Reset</button>
													</form>
												</div>
											</div>
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

	<div class="tab-pane fade" id="set-alerts">
		<div class="card control-chart-card">
			<div class="card-header d-flex justify-content-between align-items-center">
				<h5 class="control-panel-title mb-0">Notification preferences</h5>
				<?php if ($canOps): ?>
					<form method="post" class="m-0"><input type="hidden" name="action" value="scan_alerts"><button type="submit" class="btn btn-sm btn-soft-secondary">Scan now</button></form>
				<?php endif; ?>
			</div>
			<div class="card-body">
				<?php if (!$canOps): ?>
					<p class="text-muted mb-0">Only owners can change alert preferences.</p>
				<?php else: ?>
					<form method="post" class="row g-3">
						<input type="hidden" name="action" value="save_notify">
						<div class="col-md-6">
							<label class="form-label">Alert email</label>
							<input type="email" name="email_to" class="form-control form-control-sm" value="<?php echo control_h($prefs['email_to']); ?>" required>
						</div>
						<div class="col-md-6">
							<label class="form-label">Provisioning days</label>
							<input type="number" name="provisioning_days" class="form-control form-control-sm" min="1" max="90" value="<?php echo (int) $prefs['provisioning_days']; ?>">
						</div>
						<div class="col-12 d-flex flex-wrap gap-3">
							<div class="form-check"><input class="form-check-input" type="checkbox" name="email_suspend" value="1" id="email_suspend" <?php echo !empty($prefs['email_suspend']) ? 'checked' : ''; ?>><label class="form-check-label" for="email_suspend">Suspend alerts</label></div>
							<div class="form-check"><input class="form-check-input" type="checkbox" name="email_webhook_fail" value="1" id="email_webhook_fail" <?php echo !empty($prefs['email_webhook_fail']) ? 'checked' : ''; ?>><label class="form-check-label" for="email_webhook_fail">Webhook failures</label></div>
							<div class="form-check"><input class="form-check-input" type="checkbox" name="email_stuck_provisioning" value="1" id="email_stuck_provisioning" <?php echo !empty($prefs['email_stuck_provisioning']) ? 'checked' : ''; ?>><label class="form-check-label" for="email_stuck_provisioning">Stuck provisioning</label></div>
						</div>
						<div class="col-12"><button type="submit" class="btn btn-primary btn-sm">Save preferences</button></div>
					</form>
				<?php endif; ?>
			</div>
		</div>
	</div>

	<div class="tab-pane fade" id="set-access">
		<div class="row g-3">
			<div class="col-md-6">
				<div class="card control-chart-card h-100">
					<div class="card-header"><h5 class="control-panel-title mb-0">Invite admin</h5></div>
					<div class="card-body">
						<?php if (!$canManage): ?>
							<p class="text-muted mb-0">Only owners can create admins.</p>
						<?php else: ?>
							<form method="post" class="vstack gap-2">
								<input type="hidden" name="action" value="create_admin">
								<input type="text" name="name" class="form-control form-control-sm" placeholder="Name" required>
								<input type="email" name="email" class="form-control form-control-sm" placeholder="Email" required>
								<select name="role" class="form-select form-select-sm">
									<option value="support" selected>Support</option>
									<option value="owner">Owner</option>
									<option value="readonly">Readonly</option>
								</select>
								<input type="password" name="password" class="form-control form-control-sm" minlength="8" placeholder="Temp password" required>
								<button type="submit" class="btn btn-primary btn-sm">Create admin</button>
							</form>
						<?php endif; ?>
					</div>
				</div>
			</div>
			<div class="col-md-6">
				<div class="card control-chart-card h-100">
					<div class="card-header"><h5 class="control-panel-title mb-0">Control branding</h5></div>
					<div class="card-body">
						<?php if (!$canOps): ?>
							<p class="mb-0 text-muted">Subtitle: <?php echo control_h($brand['subtitle']); ?></p>
						<?php else: ?>
							<form method="post" class="vstack gap-2">
								<input type="hidden" name="action" value="save_branding">
								<input type="text" name="subtitle" class="form-control form-control-sm" value="<?php echo control_h($brand['subtitle']); ?>" placeholder="Control">
								<input type="url" name="logo_url" class="form-control form-control-sm" value="<?php echo control_h($brand['logo_url']); ?>" placeholder="Logo URL (optional)">
								<button type="submit" class="btn btn-soft-primary btn-sm">Save branding</button>
							</form>
						<?php endif; ?>
					</div>
				</div>
			</div>
		</div>
	</div>

	<div class="tab-pane fade" id="set-host">
		<div class="card control-chart-card">
			<div class="card-header"><h5 class="control-panel-title mb-0">Production host</h5></div>
			<div class="card-body text-muted font-size-13">
				<p class="mb-2">Canonical URL: <code><?php echo control_h($cfg['control_public_url']); ?></code></p>
				<ul class="mb-0 ps-3">
					<li><code>CONTROL_PUBLIC_HOST=console.tessera.co.tz</code></li>
					<li><code>CONTROL_ISOLATE=1</code></li>
					<li><code>CONTROL_ALLOWED_HOSTS=console.tessera.co.tz,localhost</code></li>
				</ul>
			</div>
		</div>
	</div>
</div>
</div>

<?php include __DIR__ . '/includes/control_layout_end.php'; ?>
