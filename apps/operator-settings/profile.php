<?php
/**
 * S-07 Profile — TDS self-service profile for signed-in staff.
 */
require_once __DIR__ . '/includes/boot.php';
/* Any authenticated staff may edit their own profile */

$settings_page_title = 'My profile';
$settings_crumb = 'Profile';
$settings_nav_active = 'profile';
$settings_extra_css = [
	'/operator/css/tessera-design-tokens.css',
	'/operator/css/tessera-design-components.css',
];
require_once __DIR__ . '/includes/layout.php';

$jsV = @filemtime(__DIR__ . '/profile.js') ?: time();
?>
<div class="tds-page" id="tds-settings-profile-root" data-ui-slice="S-07" style="padding:0">
	<div class="tds-page-title-box" style="margin-bottom:var(--tds-space-4)">
		<div>
			<p class="tds-breadcrumb"><span>Settings</span><span>·</span><span>Profile</span></p>
			<h1 class="tds-page-title">My profile</h1>
			<p class="tds-page-lead">Update how you appear across Tickets, Cargo, and Accounts. Password changes apply to all modules.</p>
		</div>
	</div>

	<div class="tds-alert tds-alert-danger" id="tds-settings-profile-error" hidden role="alert"></div>
	<div class="tds-alert tds-alert-success" id="tds-settings-profile-success" hidden role="status"></div>

	<div class="tds-row" style="gap:var(--tds-space-4);align-items:flex-start;flex-wrap:wrap">
		<div class="tds-table-panel" style="flex:0 1 16rem;min-width:14rem;text-align:center">
			<div class="tds-toolbar"><strong>Photo</strong></div>
			<div class="tds-dialog-body" style="padding:var(--tds-space-4)">
				<img id="tds-profile-avatar" src="/tickets/assets/images/users/user.png" alt="" width="112" height="112" style="width:7rem;height:7rem;border-radius:999px;object-fit:cover;border:2px solid var(--tds-border, rgba(255,255,255,.12))">
				<p class="tds-muted" id="tds-profile-fullname-label" style="margin:var(--tds-space-3) 0 var(--tds-space-1);font-weight:600"></p>
				<p class="tds-muted" id="tds-profile-role-label" style="margin:0 0 var(--tds-space-3);font-size:0.85rem"></p>
				<form id="tds-settings-profile-avatar">
					<input class="tds-input" type="file" id="tds-profile-avatar-file" accept="image/jpeg,image/png,image/webp" style="font-size:0.8rem">
					<button type="submit" class="tds-btn tds-btn-primary" id="tds-profile-avatar-btn" style="margin-top:var(--tds-space-3);width:100%">Upload photo</button>
				</form>
			</div>
		</div>

		<div style="flex:2 1 28rem;min-width:18rem">
			<div class="tds-table-panel" style="margin-bottom:var(--tds-space-4)">
				<div class="tds-toolbar"><strong>Details</strong></div>
				<form id="tds-settings-profile-form" class="tds-dialog-body" style="padding:var(--tds-space-4)">
					<div class="tds-row" style="gap:var(--tds-space-3);flex-wrap:wrap">
						<div class="tds-field" style="flex:1 1 10rem">
							<label class="tds-label" for="tds-profile-username">Username</label>
							<input class="tds-input" id="tds-profile-username" disabled>
						</div>
						<div class="tds-field" style="flex:1 1 10rem">
							<label class="tds-label" for="tds-profile-role">Role</label>
							<input class="tds-input" id="tds-profile-role" disabled>
						</div>
					</div>
					<div class="tds-row" style="gap:var(--tds-space-3);flex-wrap:wrap;margin-top:var(--tds-space-3)">
						<div class="tds-field" style="flex:1 1 10rem">
							<label class="tds-label" for="tds-profile-firstname">First name</label>
							<input class="tds-input" id="tds-profile-firstname" name="firstname" required>
						</div>
						<div class="tds-field" style="flex:1 1 10rem">
							<label class="tds-label" for="tds-profile-surname">Last name</label>
							<input class="tds-input" id="tds-profile-surname" name="surname">
						</div>
					</div>
					<div class="tds-row" style="gap:var(--tds-space-3);flex-wrap:wrap;margin-top:var(--tds-space-3)">
						<div class="tds-field" style="flex:1 1 12rem">
							<label class="tds-label" for="tds-profile-email">Email</label>
							<input class="tds-input" type="email" id="tds-profile-email" name="email" autocomplete="email">
						</div>
						<div class="tds-field" style="flex:1 1 10rem">
							<label class="tds-label" for="tds-profile-number">Phone</label>
							<input class="tds-input" id="tds-profile-number" name="number" autocomplete="tel">
						</div>
					</div>
					<div class="tds-row" style="justify-content:flex-end;margin-top:var(--tds-space-4)">
						<button type="submit" class="tds-btn tds-btn-primary" id="tds-profile-save">Save profile</button>
					</div>
				</form>
			</div>

			<div class="tds-table-panel">
				<div class="tds-toolbar"><strong>Change password</strong></div>
				<form id="tds-settings-profile-password" class="tds-dialog-body" style="padding:var(--tds-space-4)">
					<div class="tds-row" style="gap:var(--tds-space-3);flex-wrap:wrap">
						<div class="tds-field" style="flex:1 1 12rem">
							<label class="tds-label" for="tds-profile-current">Current password</label>
							<input class="tds-input" type="password" id="tds-profile-current" name="current_password" required autocomplete="current-password">
						</div>
						<div class="tds-field" style="flex:1 1 12rem">
							<label class="tds-label" for="tds-profile-new">New password</label>
							<input class="tds-input" type="password" id="tds-profile-new" name="new_password" required minlength="8" autocomplete="new-password">
						</div>
						<div class="tds-field" style="flex:1 1 12rem">
							<label class="tds-label" for="tds-profile-confirm">Confirm new password</label>
							<input class="tds-input" type="password" id="tds-profile-confirm" name="confirm_password" required minlength="8" autocomplete="new-password">
						</div>
					</div>
					<p class="tds-muted" style="margin:var(--tds-space-2) 0 0">At least 8 characters. Updates Tickets, Cargo, and Accounts logins.</p>
					<div class="tds-row" style="justify-content:flex-end;margin-top:var(--tds-space-4)">
						<button type="submit" class="tds-btn tds-btn-primary" id="tds-profile-password-btn">Update password</button>
					</div>
				</form>
			</div>
		</div>
	</div>
</div>
<script src="/settings/profile.js?v=<?php echo (int) $jsV; ?>"></script>
<?php settings_layout_end(); ?>
