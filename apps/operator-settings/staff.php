<?php
require_once __DIR__ . '/includes/boot.php';
require_once $ticketsRoot . '/includes/tessera_staff_sync.php';
tessera_require_capability($db, $username, 'staff');

$msg = null;
$err = null;
$roles = tessera_role_defaults();
$labels = [
	'owner' => 'Owner',
	'manager' => 'Manager',
	'tickets_agent' => 'Tickets agent',
	'cargo_agent' => 'Cargo agent',
	'accountant' => 'Accountant',
];
$roleDescriptions = [
	'owner' => 'Full access — settings, staff, all modules, API keys, payouts.',
	'manager' => 'Operations lead — staff, tickets, cargo, accounts, reports.',
	'tickets_agent' => 'Booking desk — tickets module only.',
	'cargo_agent' => 'Cargo desk — cargo module only.',
	'accountant' => 'Finance — accounts, reports, and payouts.',
];
$roleBadgeClass = [
	'owner' => 'bg-soft-danger text-danger',
	'manager' => 'bg-soft-primary text-primary',
	'tickets_agent' => 'bg-soft-success text-success',
	'cargo_agent' => 'bg-soft-warning text-warning',
	'accountant' => 'bg-soft-info text-info',
];

$tab = (string) ($_GET['tab'] ?? 'team');
if (!in_array($tab, ['team', 'menus'], true)) {
	$tab = 'team';
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
	$action = (string) ($_POST['action'] ?? 'assign');
	if ($action === 'create') {
		$newUser = trim((string) ($_POST['new_username'] ?? ''));
		$newPass = (string) ($_POST['new_password'] ?? '');
		$role = (string) ($_POST['tessera_role'] ?? '');
		$res = tessera_create_staff_everywhere([
			'username' => $newUser,
			'password' => $newPass,
			'firstname' => trim((string) ($_POST['new_firstname'] ?? $newUser)),
			'surname' => trim((string) ($_POST['new_surname'] ?? '')),
			'email' => trim((string) ($_POST['new_email'] ?? '')),
			'number' => trim((string) ($_POST['new_number'] ?? '')),
			'status' => 'active',
		], $role);
		if (!empty($res['ok'])) {
			$msg = 'Staff account created and synced across Tickets, Cargo, and Accounts.';
			$tab = 'team';
		} else {
			$err = $res['message'] ?? 'Could not create staff.';
		}
	} else {
		$target = trim((string) ($_POST['target_username'] ?? ''));
		$role = (string) ($_POST['tessera_role'] ?? '');
		if ($target === '' || !isset($roles[$role])) {
			$err = 'Pick a staff member and role.';
		} else {
			$res = tessera_apply_staff_role_everywhere($target, $role);
			if (!empty($res['ok'])) {
				$msg = 'Role updated for ' . $target . ' across all modules.';
				$tab = 'team';
			} else {
				$err = $res['message'] ?? 'Update failed.';
			}
		}
	}
}

$users = [];
$stats = ['total' => 0, 'active' => 0, 'owner' => 0, 'agent' => 0];
$hasCol = false;
$c = @$db->query("SHOW COLUMNS FROM users LIKE 'tessera_role'");
if ($c && $c->num_rows > 0) {
	$hasCol = true;
}
$sql = $hasCol
	? 'SELECT username, firstname, surname, role, tessera_role, status FROM users ORDER BY username ASC LIMIT 300'
	: 'SELECT username, firstname, surname, role, status FROM users ORDER BY username ASC LIMIT 300';
$q = @$db->query($sql);
if ($q) {
	while ($r = $q->fetch_assoc()) {
		$r['effective'] = !empty($r['tessera_role']) ? $r['tessera_role'] : tessera_map_legacy_role((string) ($r['role'] ?? ''));
		$users[] = $r;
		$stats['total']++;
		if (($r['status'] ?? '') === 'active') {
			$stats['active']++;
		}
		if (($r['effective'] ?? '') === 'owner') {
			$stats['owner']++;
		}
		if (in_array($r['effective'] ?? '', ['tickets_agent', 'cargo_agent'], true)) {
			$stats['agent']++;
		}
	}
}

$initialMenuUser = '';
foreach ($users as $u) {
	if (($u['status'] ?? '') === 'active') {
		$initialMenuUser = (string) $u['username'];
		break;
	}
}
if ($initialMenuUser === '' && !empty($users[0]['username'])) {
	$initialMenuUser = (string) $users[0]['username'];
}
$pickUser = trim((string) ($_GET['user'] ?? $initialMenuUser));

$cssFile = $ticketsRoot . '/assets/css/settings-staff.css';
$cssV = is_file($cssFile) ? filemtime($cssFile) : time();
$settings_extra_css = [$TICKETS_WEB . '/assets/css/settings-staff.css?v=' . (int) $cssV];

$settings_page_title = 'Staff & access control';
$settings_crumb = 'Staff & roles';
$settings_nav_active = 'staff';
require_once __DIR__ . '/includes/layout.php';
?>
<div class="tessera-staff-page">

<div class="card staff-hero mb-4">
	<div class="card-body py-4">
		<div class="row align-items-center g-3">
			<div class="col-lg-7">
				<h4 class="text-white mb-2">Staff & access control</h4>
				<p class="mb-0 opacity-75">Assign capability roles for Tickets, Cargo, and Accounts. Fine-tune which screens appear in each person’s menu.</p>
			</div>
			<div class="col-lg-5">
				<div class="row g-2">
					<div class="col-4"><div class="staff-stat"><div class="staff-stat__value"><?php echo (int) $stats['total']; ?></div><div class="staff-stat__label">Staff</div></div></div>
					<div class="col-4"><div class="staff-stat"><div class="staff-stat__value"><?php echo (int) $stats['active']; ?></div><div class="staff-stat__label">Active</div></div></div>
					<div class="col-4"><div class="staff-stat"><div class="staff-stat__value"><?php echo (int) $stats['owner']; ?></div><div class="staff-stat__label">Owners</div></div></div>
				</div>
			</div>
		</div>
	</div>
</div>

<?php if ($msg): ?><div class="alert alert-success"><?php echo settings_h($msg); ?></div><?php endif; ?>
<?php if ($err): ?><div class="alert alert-danger"><?php echo settings_h($err); ?></div><?php endif; ?>

<ul class="nav nav-tabs nav-tabs-custom staff-tabs mb-3" role="tablist">
	<li class="nav-item" role="presentation">
		<a class="nav-link<?php echo $tab === 'team' ? ' active' : ''; ?>" href="<?php echo settings_h($SETTINGS_BASE . '/staff.php?tab=team'); ?>" role="tab">
			<i class="uil uil-users-alt me-1"></i> Team roles
		</a>
	</li>
	<li class="nav-item" role="presentation">
		<a class="nav-link<?php echo $tab === 'menus' ? ' active' : ''; ?>" href="<?php echo settings_h($SETTINGS_BASE . '/staff.php?tab=menus'); ?>" role="tab">
			<i class="uil uil-lock-access me-1"></i> Menu permissions
		</a>
	</li>
</ul>

<?php if ($tab === 'team'): ?>
<div class="row g-3">
	<div class="col-xl-4">
		<div class="card h-100">
			<div class="card-header border-bottom">
				<h5 class="card-title mb-0"><i class="uil uil-user-plus me-1"></i> Add staff</h5>
			</div>
			<div class="card-body">
				<p class="text-muted small">Creates one login and applies the same role in Tickets, Cargo, and Accounts.</p>
				<form method="post" class="vstack gap-3">
					<input type="hidden" name="action" value="create">
					<div class="row g-2">
						<div class="col-6">
							<label class="form-label">First name</label>
							<input name="new_firstname" class="form-control form-control-sm" required>
						</div>
						<div class="col-6">
							<label class="form-label">Last name</label>
							<input name="new_surname" class="form-control form-control-sm">
						</div>
					</div>
					<div>
						<label class="form-label">Username</label>
						<input name="new_username" class="form-control form-control-sm" required autocomplete="off">
					</div>
					<div>
						<label class="form-label">Password</label>
						<input type="password" name="new_password" class="form-control form-control-sm" required autocomplete="new-password">
					</div>
					<div class="row g-2">
						<div class="col-6">
							<label class="form-label">Phone</label>
							<input name="new_number" class="form-control form-control-sm">
						</div>
						<div class="col-6">
							<label class="form-label">Email</label>
							<input type="email" name="new_email" class="form-control form-control-sm">
						</div>
					</div>
					<div>
						<label class="form-label">Capability role</label>
						<select name="tessera_role" class="form-select form-select-sm" required id="create-role-select">
							<?php foreach ($labels as $code => $lab): ?>
								<option value="<?php echo settings_h($code); ?>"><?php echo settings_h($lab); ?></option>
							<?php endforeach; ?>
						</select>
						<div class="form-text" id="create-role-hint"><?php echo settings_h($roleDescriptions['owner']); ?></div>
					</div>
					<button type="submit" class="btn btn-primary btn-sm w-100">Create staff account</button>
				</form>
			</div>
		</div>
	</div>
	<div class="col-xl-8">
		<div class="card mb-3">
			<div class="card-header border-bottom d-flex flex-wrap align-items-center justify-content-between gap-2">
				<h5 class="card-title mb-0"><i class="uil uil-shield-check me-1"></i> Change capability role</h5>
			</div>
			<div class="card-body">
				<form method="post" class="row g-2 align-items-end">
					<input type="hidden" name="action" value="assign">
					<div class="col-md-5">
						<label class="form-label">Staff member</label>
						<select name="target_username" class="form-select form-select-sm" required>
							<option value="">Select staff…</option>
							<?php foreach ($users as $u): ?>
								<option value="<?php echo settings_h($u['username']); ?>"><?php echo settings_h($u['username'] . ' — ' . trim($u['firstname'] . ' ' . $u['surname'])); ?></option>
							<?php endforeach; ?>
						</select>
					</div>
					<div class="col-md-5">
						<label class="form-label">New role</label>
						<select name="tessera_role" class="form-select form-select-sm" required>
							<?php foreach ($labels as $code => $lab): ?>
								<option value="<?php echo settings_h($code); ?>"><?php echo settings_h($lab); ?></option>
							<?php endforeach; ?>
						</select>
					</div>
					<div class="col-md-2">
						<button type="submit" class="btn btn-primary btn-sm w-100">Save</button>
					</div>
				</form>
			</div>
		</div>
		<div class="card">
			<div class="card-header border-bottom">
				<h5 class="card-title mb-0">Team directory</h5>
			</div>
			<div class="card-body p-0">
				<div class="table-responsive">
					<table class="table table-hover table-centered staff-table mb-0">
						<thead class="table-light">
							<tr>
								<th>Staff</th>
								<th>Capability role</th>
								<th>Legacy role</th>
								<th>Status</th>
								<th class="text-end">Menus</th>
							</tr>
						</thead>
						<tbody>
						<?php foreach ($users as $u):
							$eff = (string) ($u['effective'] ?? '');
							$initials = strtoupper(substr((string) ($u['firstname'] ?: $u['username']), 0, 1) . substr((string) ($u['surname'] ?? ''), 0, 1));
							$badge = $roleBadgeClass[$eff] ?? 'bg-soft-secondary text-secondary';
						?>
							<tr>
								<td>
									<div class="d-flex align-items-center gap-2">
										<span class="staff-user-avatar"><?php echo settings_h($initials ?: '?'); ?></span>
										<div>
											<div class="fw-semibold"><?php echo settings_h($u['username']); ?></div>
											<div class="small text-muted"><?php echo settings_h(trim($u['firstname'] . ' ' . $u['surname'])); ?></div>
										</div>
									</div>
								</td>
								<td><span class="badge role-badge <?php echo settings_h($badge); ?>"><?php echo settings_h($labels[$eff] ?? $eff); ?></span></td>
								<td class="small text-muted"><?php echo settings_h($u['role'] ?? ''); ?></td>
								<td>
									<?php if (($u['status'] ?? '') === 'active'): ?>
										<span class="badge bg-soft-success text-success">Active</span>
									<?php else: ?>
										<span class="badge bg-soft-secondary text-secondary"><?php echo settings_h($u['status'] ?? 'off'); ?></span>
									<?php endif; ?>
								</td>
								<td class="text-end">
									<a class="btn btn-soft-primary btn-sm" href="<?php echo settings_h($SETTINGS_BASE . '/staff.php?tab=menus&user=' . rawurlencode($u['username'])); ?>">Edit menus</a>
								</td>
							</tr>
						<?php endforeach; ?>
						</tbody>
					</table>
				</div>
			</div>
		</div>
	</div>
</div>

<div class="card mt-3">
	<div class="card-header border-bottom"><h5 class="card-title mb-0">Related settings</h5></div>
	<div class="card-body">
		<div class="row g-3">
			<div class="col-md-6">
				<a class="border rounded p-3 d-flex gap-3 h-100 text-decoration-none align-items-start" href="<?php echo settings_h($TICKETS_WEB); ?>/assign_route.php">
					<span class="avatar-xs"><span class="avatar-title rounded-circle bg-soft-primary text-primary"><i class="uil uil-map-marker"></i></span></span>
					<div>
						<h6 class="text-dark mb-1">Assign routes to sellers</h6>
						<p class="text-muted small mb-0">Limit which route names each booking agent can sell.</p>
					</div>
				</a>
			</div>
			<div class="col-md-6">
				<a class="border rounded p-3 d-flex gap-3 h-100 text-decoration-none align-items-start" href="<?php echo settings_h($SETTINGS_BASE . '/staff.php?tab=menus'); ?>">
					<span class="avatar-xs"><span class="avatar-title rounded-circle bg-soft-warning text-warning"><i class="uil uil-lock-access"></i></span></span>
					<div>
						<h6 class="text-dark mb-1">Menu permissions</h6>
						<p class="text-muted small mb-0">Toggle individual screens in the top navigation per user.</p>
					</div>
				</a>
			</div>
		</div>
	</div>
</div>

<?php else: ?>
<div class="row g-3">
	<div class="col-lg-4">
		<div class="card mb-0">
			<div class="card-header border-bottom py-3">
				<label class="form-label small text-muted mb-1" for="staff-user-search">Staff</label>
				<input type="search" id="staff-user-search" class="form-control form-control-sm" placeholder="Search name or username…" autocomplete="off">
			</div>
			<div class="card-body p-2 staff-user-list">
				<?php foreach ($users as $u):
					$search = strtolower($u['username'] . ' ' . $u['firstname'] . ' ' . $u['surname']);
					$initials = strtoupper(substr((string) ($u['firstname'] ?: $u['username']), 0, 1) . substr((string) ($u['surname'] ?? ''), 0, 1));
				?>
				<div class="staff-user-list-item">
					<button type="button" class="staff-user-item<?php echo $pickUser === $u['username'] ? ' is-active' : ''; ?>"
						data-username="<?php echo settings_h($u['username']); ?>"
						data-search="<?php echo settings_h($search); ?>">
						<span class="staff-user-avatar"><?php echo settings_h($initials ?: '?'); ?></span>
						<span class="flex-grow-1 min-w-0">
							<span class="d-block fw-semibold text-truncate"><?php echo settings_h($u['username']); ?></span>
							<span class="d-block small text-muted text-truncate"><?php echo settings_h(trim($u['firstname'] . ' ' . $u['surname'])); ?> · <?php echo settings_h($u['role'] ?? ''); ?></span>
						</span>
					</button>
				</div>
				<?php endforeach; ?>
			</div>
		</div>
	</div>
	<div class="col-lg-8">
		<div class="card mb-3">
			<div class="card-body py-3">
				<h5 class="mb-1">Menu permissions</h5>
				<p class="text-muted small mb-0" id="menu-access-summary">Select a staff member to view and edit their menu.</p>
			</div>
		</div>
		<div class="card">
			<div class="card-body p-2" id="menu-access-panel">
				<div class="menu-access-empty"><i class="uil uil-user-check d-block fs-2 mb-2"></i>Select a staff member on the left.</div>
			</div>
		</div>
	</div>
</div>
<?php endif; ?>

</div>

<div class="staff-toast-wrap" id="staff-toast-wrap" aria-live="polite"></div>

<script>
window.TESSERA_STAFF_CFG = {
	apiBase: <?php echo json_encode($TICKETS_WEB . '/'); ?>,
	initialUser: <?php echo json_encode($tab === 'menus' ? $pickUser : ''); ?>,
	roleHints: <?php echo json_encode($roleDescriptions, JSON_UNESCAPED_UNICODE); ?>
};
(function () {
	var sel = document.getElementById('create-role-select');
	var hint = document.getElementById('create-role-hint');
	var hints = window.TESSERA_STAFF_CFG.roleHints || {};
	if (sel && hint) {
		var sync = function () { hint.textContent = hints[sel.value] || ''; };
		sel.addEventListener('change', sync);
		sync();
	}
})();
</script>
<?php
$jsFile = $ticketsRoot . '/assets/js/settings-staff.js';
$jsV = is_file($jsFile) ? filemtime($jsFile) : time();
$settings_extra_scripts = '<script src="' . settings_h($TICKETS_WEB . '/assets/js/settings-staff.js?v=' . (int) $jsV) . '"></script>';
settings_layout_end();
