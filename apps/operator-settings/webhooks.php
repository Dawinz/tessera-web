<?php
/**
 * S-06 Webhooks — TDS create / list / disable.
 */
require_once __DIR__ . '/includes/boot.php';
tessera_require_capability($db, $username, 'api_keys');

$settings_page_title = 'Webhooks';
$settings_crumb = 'Webhooks';
$settings_nav_active = 'webhooks';
$settings_extra_css = [
	'/operator/css/tessera-design-tokens.css',
	'/operator/css/tessera-design-components.css',
];
require_once __DIR__ . '/includes/layout.php';

$jsV = @filemtime(__DIR__ . '/webhooks.js') ?: time();
?>
<div class="tds-page" id="tds-settings-webhooks-root" data-ui-slice="S-06" style="padding:0">
	<div class="tds-page-title-box" style="margin-bottom:var(--tds-space-4)">
		<div>
			<p class="tds-breadcrumb"><span>Settings</span><span>·</span><span>Webhooks</span></p>
			<h1 class="tds-page-title">Webhooks</h1>
			<p class="tds-page-lead">Tessera POSTs signed JSON to your HTTPS endpoint. Verify <code id="tds-webhooks-sig-hint">X-Tessera-Signature: sha256=…</code> with your secret.</p>
		</div>
	</div>

	<div class="tds-alert tds-alert-danger" id="tds-settings-webhooks-error" hidden role="alert"></div>
	<div class="tds-alert tds-alert-success" id="tds-settings-webhooks-success" hidden role="status"></div>
	<div class="tds-alert tds-alert-warning" id="tds-settings-webhooks-secret" hidden role="status">
		<strong>Signing secret (copy once):</strong>
		<code id="tds-settings-webhooks-secret-value" style="display:block;margin-top:var(--tds-space-2);word-break:break-all"></code>
	</div>

	<div class="tds-table-panel" style="margin-bottom:var(--tds-space-4)">
		<div class="tds-toolbar"><strong>Add webhook</strong></div>
		<form id="tds-settings-webhooks-create" class="tds-dialog-body" style="padding:var(--tds-space-4)">
			<div class="tds-field">
				<label class="tds-label" for="tds-webhooks-url">HTTPS URL</label>
				<input class="tds-input" type="url" id="tds-webhooks-url" name="url" placeholder="https://yoursite.example/hooks/tessera" required autocomplete="off">
				<p class="tds-muted" style="margin:var(--tds-space-2) 0 0">Localhost HTTP is allowed for development only.</p>
			</div>
			<div class="tds-field" style="margin-top:var(--tds-space-3)">
				<label class="tds-label">Events</label>
				<div class="tds-row" id="tds-webhooks-events" style="gap:var(--tds-space-3);flex-wrap:wrap;padding-top:var(--tds-space-2)"></div>
			</div>
			<div class="tds-row" style="justify-content:flex-end;margin-top:var(--tds-space-4)">
				<button type="submit" class="tds-btn tds-btn-primary" id="tds-webhooks-create-btn">Add webhook</button>
			</div>
		</form>
	</div>

	<div class="tds-table-panel">
		<div class="tds-toolbar"><strong>Endpoints</strong></div>
		<div class="table-responsive">
			<table class="tds-table">
				<thead>
					<tr>
						<th>URL</th>
						<th>Events</th>
						<th>Secret</th>
						<th>Status</th>
						<th></th>
					</tr>
				</thead>
				<tbody id="tds-webhooks-tbody">
					<tr><td colspan="5" class="tds-muted">Loading…</td></tr>
				</tbody>
			</table>
		</div>
		<p class="tds-muted" style="padding:var(--tds-space-3) var(--tds-space-4);margin:0">
			Need credentials? Manage <a href="/settings/api.php">API keys</a>.
		</p>
	</div>
</div>
<script src="/settings/webhooks.js?v=<?php echo (int) $jsV; ?>"></script>
<?php settings_layout_end(); ?>
