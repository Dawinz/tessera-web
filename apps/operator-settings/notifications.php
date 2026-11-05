<?php
/**
 * S-03 Notifications — TDS channels + event matrix.
 */
require_once __DIR__ . '/includes/boot.php';
tessera_require_capability($db, $username, 'notifications');

$settings_page_title = 'Notifications';
$settings_crumb = 'Notifications';
$settings_nav_active = 'notifications';
$settings_extra_css = [
	'/operator/css/tessera-design-tokens.css',
	'/operator/css/tessera-design-components.css',
];
require_once __DIR__ . '/includes/layout.php';

$jsV = @filemtime(__DIR__ . '/notifications.js') ?: time();
?>
<div class="tds-page" id="tds-settings-notifications-root" data-ui-slice="S-03" style="padding:0">
	<div class="tds-page-title-box" style="margin-bottom:var(--tds-space-4)">
		<div>
			<p class="tds-breadcrumb"><span>Settings</span><span>·</span><span>Notifications</span></p>
			<h1 class="tds-page-title">Notifications</h1>
			<p class="tds-page-lead">Use your own SMS / WhatsApp credentials. Push applies if you ship a customer app. Secrets are encrypted at rest — leave API key fields blank to keep the current value.</p>
		</div>
	</div>

	<div class="tds-alert tds-alert-danger" id="tds-settings-notifications-error" hidden role="alert"></div>
	<div class="tds-alert tds-alert-success" id="tds-settings-notifications-success" hidden role="status"></div>

	<form id="tds-settings-notifications-form">
		<div class="tds-table-panel" style="margin-bottom:var(--tds-space-4)">
			<div class="tds-toolbar"><strong>SMS</strong>
				<label class="tds-row" style="gap:var(--tds-space-2);align-items:center;margin-left:auto">
					<input type="checkbox" id="tds-notif-sms-enabled">
					<span class="tds-muted">Enabled</span>
				</label>
			</div>
			<div class="tds-dialog-body" style="padding:var(--tds-space-4)">
				<div class="tds-row" style="gap:var(--tds-space-3);flex-wrap:wrap">
					<div class="tds-field" style="flex:1 1 10rem">
						<label class="tds-label" for="tds-notif-sms-provider">Provider</label>
						<select class="tds-input" id="tds-notif-sms-provider"></select>
					</div>
					<div class="tds-field" style="flex:1 1 10rem">
						<label class="tds-label" for="tds-notif-sms-sender">Sender ID</label>
						<input class="tds-input" id="tds-notif-sms-sender" autocomplete="off">
					</div>
					<div class="tds-field" style="flex:1 1 10rem">
						<label class="tds-label" for="tds-notif-sms-user">API username</label>
						<input class="tds-input" id="tds-notif-sms-user" autocomplete="off">
					</div>
					<div class="tds-field" style="flex:1 1 14rem">
						<label class="tds-label" for="tds-notif-sms-key">API key <span class="tds-muted" id="tds-notif-sms-key-hint"></span></label>
						<input class="tds-input" type="password" id="tds-notif-sms-key" placeholder="Leave blank to keep" autocomplete="new-password">
					</div>
				</div>
			</div>
		</div>

		<div class="tds-table-panel" style="margin-bottom:var(--tds-space-4)">
			<div class="tds-toolbar"><strong>WhatsApp</strong>
				<label class="tds-row" style="gap:var(--tds-space-2);align-items:center;margin-left:auto">
					<input type="checkbox" id="tds-notif-wa-enabled">
					<span class="tds-muted">Enabled</span>
				</label>
			</div>
			<div class="tds-dialog-body" style="padding:var(--tds-space-4)">
				<div class="tds-row" style="gap:var(--tds-space-3);flex-wrap:wrap">
					<div class="tds-field" style="flex:1 1 10rem">
						<label class="tds-label" for="tds-notif-wa-provider">Provider</label>
						<input class="tds-input" id="tds-notif-wa-provider" placeholder="ultramsg / other" autocomplete="off">
					</div>
					<div class="tds-field" style="flex:1 1 10rem">
						<label class="tds-label" for="tds-notif-wa-instance">Instance</label>
						<input class="tds-input" id="tds-notif-wa-instance" autocomplete="off">
					</div>
					<div class="tds-field" style="flex:1 1 14rem">
						<label class="tds-label" for="tds-notif-wa-key">API token <span class="tds-muted" id="tds-notif-wa-key-hint"></span></label>
						<input class="tds-input" type="password" id="tds-notif-wa-key" placeholder="Leave blank to keep" autocomplete="new-password">
					</div>
				</div>
			</div>
		</div>

		<div class="tds-table-panel" style="margin-bottom:var(--tds-space-4)">
			<div class="tds-toolbar"><strong>App push</strong>
				<label class="tds-row" style="gap:var(--tds-space-2);align-items:center;margin-left:auto">
					<input type="checkbox" id="tds-notif-push-enabled">
					<span class="tds-muted">Enabled</span>
				</label>
			</div>
			<div class="tds-dialog-body" style="padding:var(--tds-space-4)">
				<div class="tds-row" style="gap:var(--tds-space-3);flex-wrap:wrap">
					<div class="tds-field" style="flex:2 1 16rem">
						<label class="tds-label" for="tds-notif-push-url">Push service URL</label>
						<input class="tds-input" id="tds-notif-push-url" autocomplete="off">
					</div>
					<div class="tds-field" style="flex:1 1 12rem">
						<label class="tds-label" for="tds-notif-push-key">Push API key</label>
						<input class="tds-input" type="password" id="tds-notif-push-key" placeholder="Leave blank to keep" autocomplete="new-password">
					</div>
				</div>
			</div>
		</div>

		<div class="tds-table-panel" style="margin-bottom:var(--tds-space-4)">
			<div class="tds-toolbar"><strong>Events</strong></div>
			<div class="table-responsive">
				<table class="tds-table">
					<thead>
						<tr>
							<th>Event</th>
							<th>SMS</th>
							<th>WhatsApp</th>
							<th>Push</th>
						</tr>
					</thead>
					<tbody id="tds-notif-events-tbody">
						<tr><td colspan="4" class="tds-muted">Loading…</td></tr>
					</tbody>
				</table>
			</div>
		</div>

		<div class="tds-row" style="justify-content:flex-end;gap:var(--tds-space-2)">
			<button type="submit" class="tds-btn tds-btn-primary" id="tds-notif-save">Save notifications</button>
		</div>
	</form>
</div>
<script src="/settings/notifications.js?v=<?php echo (int) $jsV; ?>"></script>
<?php settings_layout_end(); ?>
