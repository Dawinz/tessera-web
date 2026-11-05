<?php
/**
 * S-02 Staff & roles — TDS team directory + menu permissions.
 */
require_once __DIR__ . '/includes/boot.php';
tessera_require_capability($db, $username, 'staff');

$tab = (string) ($_GET['tab'] ?? 'team');
if (!in_array($tab, ['team', 'menus'], true)) {
	$tab = 'team';
}
$pickUser = trim((string) ($_GET['user'] ?? ''));

$settings_page_title = 'Staff & roles';
$settings_crumb = 'Staff & roles';
$settings_nav_active = 'staff';
$settings_extra_css = [
	'/operator/css/tessera-design-tokens.css',
	'/operator/css/tessera-design-components.css',
];
require_once __DIR__ . '/includes/layout.php';

$jsV = @filemtime(__DIR__ . '/staff.js') ?: time();
?>
<style>
#tds-settings-staff-root .tds-staff-user-list { max-height: 32rem; overflow-y: auto; }
#tds-settings-staff-root .tds-staff-user-item {
	display: flex; align-items: center; gap: var(--tds-space-3); width: 100%;
	text-align: left; border: none; background: transparent; color: inherit;
	padding: var(--tds-space-2) var(--tds-space-3); border-radius: var(--tds-radius-md);
	cursor: pointer;
}
#tds-settings-staff-root .tds-staff-user-item:hover,
#tds-settings-staff-root .tds-staff-user-item.is-active {
	background: color-mix(in srgb, var(--tds-tenant-accent, #3b82f6) 12%, transparent);
}
#tds-settings-staff-root .tds-staff-avatar {
	width: 2.25rem; height: 2.25rem; border-radius: 999px; flex-shrink: 0;
	display: inline-flex; align-items: center; justify-content: center;
	font-size: 0.8rem; font-weight: 700;
	background: color-mix(in srgb, var(--tds-tenant-accent, #3b82f6) 20%, transparent);
	color: var(--tds-tenant-accent, #93c5fd);
}
#tds-settings-staff-root .tds-menu-group {
	border: 1px solid var(--tds-border, rgba(255,255,255,.08));
	border-radius: var(--tds-radius-md); margin-bottom: var(--tds-space-3); overflow: hidden;
}
#tds-settings-staff-root .tds-menu-group__head {
	display: flex; justify-content: space-between; align-items: center;
	padding: var(--tds-space-2) var(--tds-space-3);
	background: color-mix(in srgb, var(--tds-surface-2, #1a2332) 80%, transparent);
	font-weight: 600;
}
#tds-settings-staff-root .tds-menu-row {
	display: flex; align-items: center; justify-content: space-between; gap: var(--tds-space-3);
	padding: var(--tds-space-2) var(--tds-space-3);
	border-top: 1px solid var(--tds-border, rgba(255,255,255,.06));
}
#tds-settings-staff-root .tds-menu-empty {
	text-align: center; padding: var(--tds-space-8) var(--tds-space-4); color: var(--tds-muted, #94a3b8);
}
#tds-settings-staff-root .tds-staff-toast-wrap {
	position: fixed; right: 1rem; bottom: 1rem; z-index: 1080; max-width: 22rem;
}
</style>

<div class="tds-page" id="tds-settings-staff-root" data-ui-slice="S-02" data-tab="<?php echo settings_h($tab); ?>" data-initial-user="<?php echo settings_h($pickUser); ?>" style="padding:0">
	<div class="tds-page-title-box" style="margin-bottom:var(--tds-space-4)">
		<div>
			<p class="tds-breadcrumb"><span>Settings</span><span>·</span><span>Staff &amp; roles</span></p>
			<h1 class="tds-page-title">Staff &amp; roles</h1>
			<p class="tds-page-lead">Assign capability roles across Tickets, Cargo, and Accounts. Fine-tune which screens appear in each person’s menu.</p>
		</div>
	</div>

	<div class="tds-alert tds-alert-danger" id="tds-settings-staff-error" hidden role="alert"></div>
	<div class="tds-alert tds-alert-success" id="tds-settings-staff-success" hidden role="status"></div>

	<div class="tds-row" id="tds-settings-staff-kpis" style="gap:var(--tds-space-3);flex-wrap:wrap;margin-bottom:var(--tds-space-4)">
		<div class="tds-table-panel" style="flex:1 1 8rem;min-width:7rem;padding:var(--tds-space-3)">
			<div class="tds-muted" style="font-size:0.75rem;text-transform:uppercase;letter-spacing:.04em">Staff</div>
			<div style="font-size:1.35rem;font-weight:700" data-kpi="total">—</div>
		</div>
		<div class="tds-table-panel" style="flex:1 1 8rem;min-width:7rem;padding:var(--tds-space-3)">
			<div class="tds-muted" style="font-size:0.75rem;text-transform:uppercase;letter-spacing:.04em">Active</div>
			<div style="font-size:1.35rem;font-weight:700" data-kpi="active">—</div>
		</div>
		<div class="tds-table-panel" style="flex:1 1 8rem;min-width:7rem;padding:var(--tds-space-3)">
			<div class="tds-muted" style="font-size:0.75rem;text-transform:uppercase;letter-spacing:.04em">Owners</div>
			<div style="font-size:1.35rem;font-weight:700" data-kpi="owner">—</div>
		</div>
		<div class="tds-table-panel" style="flex:1 1 8rem;min-width:7rem;padding:var(--tds-space-3)">
			<div class="tds-muted" style="font-size:0.75rem;text-transform:uppercase;letter-spacing:.04em">Agents</div>
			<div style="font-size:1.35rem;font-weight:700" data-kpi="agent">—</div>
		</div>
	</div>

	<div class="tds-row" style="gap:var(--tds-space-2);flex-wrap:wrap;margin-bottom:var(--tds-space-4)" role="tablist">
		<a class="tds-btn<?php echo $tab === 'team' ? ' tds-btn-primary' : ''; ?>" href="/settings/staff.php?tab=team" role="tab" aria-selected="<?php echo $tab === 'team' ? 'true' : 'false'; ?>">Team roles</a>
		<a class="tds-btn<?php echo $tab === 'menus' ? ' tds-btn-primary' : ''; ?>" href="/settings/staff.php?tab=menus<?php echo $pickUser !== '' ? '&user=' . rawurlencode($pickUser) : ''; ?>" role="tab" aria-selected="<?php echo $tab === 'menus' ? 'true' : 'false'; ?>">Menu permissions</a>
	</div>

<?php if ($tab === 'team'): ?>
	<div class="tds-row" style="gap:var(--tds-space-4);align-items:flex-start;flex-wrap:wrap">
		<div class="tds-table-panel" style="flex:1 1 18rem;min-width:16rem">
			<div class="tds-toolbar"><strong>Add staff</strong></div>
			<form id="tds-settings-staff-create" class="tds-dialog-body" style="padding:var(--tds-space-4)">
				<p class="tds-muted" style="margin:0 0 var(--tds-space-3)">Creates one login and applies the same role in Tickets, Cargo, and Accounts.</p>
				<div class="tds-row" style="gap:var(--tds-space-3);flex-wrap:wrap">
					<div class="tds-field" style="flex:1 1 8rem">
						<label class="tds-label" for="tds-staff-firstname">First name</label>
						<input class="tds-input" id="tds-staff-firstname" name="firstname" required>
					</div>
					<div class="tds-field" style="flex:1 1 8rem">
						<label class="tds-label" for="tds-staff-surname">Last name</label>
						<input class="tds-input" id="tds-staff-surname" name="surname">
					</div>
				</div>
				<div class="tds-field" style="margin-top:var(--tds-space-3)">
					<label class="tds-label" for="tds-staff-username">Username</label>
					<input class="tds-input" id="tds-staff-username" name="username" required autocomplete="off">
				</div>
				<div class="tds-field" style="margin-top:var(--tds-space-3)">
					<label class="tds-label" for="tds-staff-password">Password</label>
					<input class="tds-input" type="password" id="tds-staff-password" name="password" required autocomplete="new-password">
				</div>
				<div class="tds-row" style="gap:var(--tds-space-3);flex-wrap:wrap;margin-top:var(--tds-space-3)">
					<div class="tds-field" style="flex:1 1 8rem">
						<label class="tds-label" for="tds-staff-number">Phone</label>
						<input class="tds-input" id="tds-staff-number" name="number">
					</div>
					<div class="tds-field" style="flex:1 1 8rem">
						<label class="tds-label" for="tds-staff-email">Email</label>
						<input class="tds-input" type="email" id="tds-staff-email" name="email">
					</div>
				</div>
				<div class="tds-field" style="margin-top:var(--tds-space-3)">
					<label class="tds-label" for="tds-staff-create-role">Capability role</label>
					<select class="tds-input" id="tds-staff-create-role" name="role" required></select>
					<p class="tds-muted" id="tds-staff-create-role-hint" style="margin:var(--tds-space-2) 0 0"></p>
				</div>
				<div class="tds-row" style="justify-content:flex-end;margin-top:var(--tds-space-4)">
					<button type="submit" class="tds-btn tds-btn-primary" id="tds-staff-create-btn">Create staff account</button>
				</div>
			</form>
		</div>

		<div style="flex:2 1 28rem;min-width:18rem">
			<div class="tds-table-panel" style="margin-bottom:var(--tds-space-4)">
				<div class="tds-toolbar"><strong>Change capability role</strong></div>
				<form id="tds-settings-staff-assign" class="tds-dialog-body" style="padding:var(--tds-space-4)">
					<div class="tds-row" style="gap:var(--tds-space-3);flex-wrap:wrap;align-items:flex-end">
						<div class="tds-field" style="flex:1 1 12rem">
							<label class="tds-label" for="tds-staff-assign-user">Staff member</label>
							<select class="tds-input" id="tds-staff-assign-user" name="username" required>
								<option value="">Select staff…</option>
							</select>
						</div>
						<div class="tds-field" style="flex:1 1 12rem">
							<label class="tds-label" for="tds-staff-assign-role">New role</label>
							<select class="tds-input" id="tds-staff-assign-role" name="role" required></select>
						</div>
						<button type="submit" class="tds-btn tds-btn-primary" id="tds-staff-assign-btn">Save</button>
					</div>
				</form>
			</div>

			<div class="tds-table-panel">
				<div class="tds-toolbar" style="gap:var(--tds-space-3);flex-wrap:wrap">
					<strong>Team directory</strong>
					<input class="tds-input" type="search" id="tds-staff-search" placeholder="Search…" style="max-width:14rem;margin-left:auto" autocomplete="off">
				</div>
				<div class="table-responsive">
					<table class="tds-table" id="tds-staff-table">
						<thead>
							<tr>
								<th>Staff</th>
								<th>Capability role</th>
								<th>Legacy role</th>
								<th>Status</th>
								<th class="text-end">Menus</th>
							</tr>
						</thead>
						<tbody id="tds-staff-tbody">
							<tr><td colspan="5" class="tds-muted">Loading…</td></tr>
						</tbody>
					</table>
				</div>
			</div>
		</div>
	</div>

	<div class="tds-table-panel" style="margin-top:var(--tds-space-4)">
		<div class="tds-toolbar"><strong>Related settings</strong></div>
		<div class="tds-row" style="gap:var(--tds-space-3);flex-wrap:wrap;padding:var(--tds-space-4)">
			<a class="tds-btn" href="/tickets/assign_route.php">Assign routes to sellers</a>
			<a class="tds-btn" href="/settings/staff.php?tab=menus">Menu permissions</a>
		</div>
	</div>

<?php else: ?>
	<div class="tds-row" style="gap:var(--tds-space-4);align-items:flex-start;flex-wrap:wrap" id="tds-settings-staff-menus">
		<div class="tds-table-panel" style="flex:1 1 16rem;min-width:14rem">
			<div class="tds-toolbar">
				<input class="tds-input" type="search" id="tds-staff-user-search" placeholder="Search name or username…" autocomplete="off">
			</div>
			<div class="tds-staff-user-list" id="tds-staff-user-list">
				<div class="tds-menu-empty">Loading…</div>
			</div>
		</div>
		<div style="flex:2 1 24rem;min-width:16rem">
			<div class="tds-table-panel" style="margin-bottom:var(--tds-space-3)">
				<div class="tds-dialog-body" style="padding:var(--tds-space-3)">
					<strong>Menu permissions</strong>
					<p class="tds-muted" id="tds-menu-access-summary" style="margin:var(--tds-space-2) 0 0">Select a staff member to view and edit their menu.</p>
				</div>
			</div>
			<div class="tds-table-panel">
				<div class="tds-dialog-body" style="padding:var(--tds-space-2)" id="tds-menu-access-panel">
					<div class="tds-menu-empty">Select a staff member on the left.</div>
				</div>
			</div>
		</div>
	</div>
<?php endif; ?>

	<div class="tds-staff-toast-wrap" id="tds-staff-toast-wrap" aria-live="polite"></div>
</div>
<script src="/settings/staff.js?v=<?php echo (int) $jsV; ?>"></script>
<?php settings_layout_end(); ?>
